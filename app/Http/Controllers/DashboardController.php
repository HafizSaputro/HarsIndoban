<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\BarangMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::today()->subDays(30);
    
        $end = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::today()->endOfDay();
    
        // Grafik mode
        $mode = $request->mode ?? 'harian';
        $diffInDays = $start->diffInDays($end);
        $diffInYears = $start->diffInYears($end);
    
        // Validasi mode sesuai rentang
        switch ($mode) {
            case 'harian':
                if ($diffInDays > 31) $mode = 'harian';
                break;
            case 'mingguan':
                if ($diffInDays < 14 || $diffInDays > 31) $mode = 'harian';
                break;
            case 'bulanan':
                if ($diffInDays < 31 || $diffInYears > 1) $mode = 'harian';
                break;
            case 'tahunan':
                if ($diffInYears < 2 || $diffInYears > 10) $mode = 'harian';
                break;
        }
    
        // Format label grafik berdasarkan mode
        switch ($mode) {
            case 'mingguan':
                $dateFormat = '%x-%v'; // ISO week number
                break;
            case 'bulanan':
                $dateFormat = '%Y-%m';
                break;
            case 'tahunan':
                $dateFormat = '%Y';
                break;
            default:
                $dateFormat = '%Y-%m-%d';
                break;
        }
    
        // Transaksi & Penjualan
        $transaksiDetail = Transaksi::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();
        $totalPenjualan = $transaksiDetail->sum('grand_total');
        $jumlahTransaksi = $transaksiDetail->count();
    
        // Pengeluaran & Pemasukan
        $pengeluaranQuery = BarangMasuk::whereBetween('created_at', [$start, $end]);
        $totalPengeluaran = $pengeluaranQuery->sum(DB::raw('harga_satuan * jumlah'));
        $totalPemasukan = $totalPenjualan - $totalPengeluaran;
    
        // Barang Terlaris
        $barangTerlaris = DetailTransaksi::select('barang_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('transaksi', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->first();
    
        // Grafik penjualan
        $penjualan = Transaksi::selectRaw("DATE_FORMAT(created_at, '$dateFormat') as label, SUM(grand_total) as total")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('label')
            ->orderBy('label')
            ->get();
    
        $pembelian = BarangMasuk::selectRaw("DATE_FORMAT(created_at, '$dateFormat') as label, SUM(harga_satuan * jumlah) as total")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('label')
            ->orderBy('label')
            ->get();
    
        $allLabels = collect($penjualan->pluck('label'))
            ->merge($pembelian->pluck('label'))
            ->unique()
            ->sort()
            ->values();
    
        $penjualanMap = $penjualan->pluck('total', 'label');
        $pembelianMap = $pembelian->pluck('total', 'label');
    
        $labels = $allLabels->toArray();
        $penjualanData = array_map(fn($lbl) => $penjualanMap[$lbl] ?? 0, $labels);
        $pembelianData = array_map(fn($lbl) => $pembelianMap[$lbl] ?? 0, $labels);
    
        // 5 Barang Terlaris
        $topBarang = DetailTransaksi::select('barang_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('transaksi', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->with('barang')
            ->take(5)
            ->get();
    
        return view('dashboard.index', compact(
            'start',
            'end',
            'mode',
            'transaksiDetail',
            'totalPenjualan',
            'jumlahTransaksi',
            'totalPengeluaran',
            'totalPemasukan',
            'barangTerlaris',
            'labels',
            'penjualanData',
            'pembelianData',
            'topBarang'
        ));
    }
    
    public function grafikData(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today()->subDays(30);
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();
        $mode = $request->mode ?? 'harian';
    
        $diffInDays = $start->diffInDays($end);
        $diffInMonths = $start->diffInMonths($end);
        $diffInYears = $start->diffInYears($end);
        
        // Validasi mode berdasarkan durasi
        switch ($mode) {
            case 'harian':
                if ($diffInDays > 31) {
                    return response()->json(['error' => 'Mode harian hanya bisa digunakan untuk maksimal 31 hari'], 422);
                }
                break;
        
            case 'mingguan':
                if ($diffInMonths > 3 || $diffInDays < 14) {
                    return response()->json(['error' => 'Mode mingguan hanya bisa digunakan untuk rentang 2 minggu hingga 3 bulan'], 422);
                }
                break;
        
            case 'bulanan':
                if ($diffInYears > 5 || $diffInMonths < 1) {
                    return response()->json(['error' => 'Mode bulanan hanya bisa digunakan untuk rentang 1 bulan hingga 5 tahun'], 422);
                }
                break;
        
            case 'tahunan':
                if ($diffInYears > 10 || $diffInYears < 1) {
                    return response()->json(['error' => 'Mode tahunan hanya bisa digunakan untuk rentang 1 hingga 10 tahun'], 422);
                }
                break;
        
            default:
                $mode = 'harian';
        }
        
    
        // Format untuk query
        if ($mode === 'harian') {
            $format = '%Y-%m-%d';
        } elseif ($mode === 'mingguan') {
            $format = '%Y-%u'; // Tahun-Minggu
        } elseif ($mode === 'bulanan') {
            $format = '%Y-%m';
        } else {
            $format = '%Y';
        }
    
        // Ambil data dari DB
        $penjualan = Transaksi::selectRaw("DATE_FORMAT(created_at, '$format') as periode, SUM(grand_total) as total")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();
    
        $pembelian = BarangMasuk::selectRaw("DATE_FORMAT(created_at, '$format') as periode, SUM(harga_satuan * jumlah) as total")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();
    
        // Membuat semua periode meskipun tidak ada data
        $periodeKeys = [];
        $current = $start->copy();
    
        while ($current <= $end) {
            if ($mode === 'harian') {
                $periodeKeys[] = $current->format('Y-m-d');
                $current->addDay();
            } elseif ($mode === 'mingguan') {
                $periodeKeys[] = $current->format('Y') . '-' . $current->format('W');
                $current->addWeek();
            } elseif ($mode === 'bulanan') {
                $periodeKeys[] = $current->format('Y-m');
                $current->addMonth();
            } else {
                $periodeKeys[] = $current->format('Y');
                $current->addYear();
            }
        }
    
        $penjualanMap = $penjualan->pluck('total', 'periode');
        $pembelianMap = $pembelian->pluck('total', 'periode');
    
        // Format label user-friendly
        $labels = collect($periodeKeys)->map(function ($key) use ($mode) {
            if ($mode === 'harian') {
                return Carbon::createFromFormat('Y-m-d', $key)->translatedFormat('d M Y');
            } elseif ($mode === 'mingguan') {
                [$year, $week] = explode('-', $key);
                $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $endOfWeek = (clone $startOfWeek)->endOfWeek();
                return $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M');
            } elseif ($mode === 'bulanan') {
                return Carbon::createFromFormat('Y-m', $key)->translatedFormat('F Y');
            } else {
                return $key;
            }
        });
    
        $penjualanData = collect($periodeKeys)->map(fn($k) => $penjualanMap[$k] ?? 0);
        $pembelianData = collect($periodeKeys)->map(fn($k) => $pembelianMap[$k] ?? 0);
    
        return response()->json([
            'labels' => $labels,
            'penjualanData' => $penjualanData,
            'pembelianData' => $pembelianData,
        ]);
    }
    


    public function export(Request $request, $format)
    {
        $filter = $request->get('filter', 'harian');
        $today = Carbon::today();

        if ($filter == 'mingguan') {
            $start = $today->copy()->startOfWeek();
            $end = $today->copy()->endOfWeek();
        } elseif ($filter == 'bulanan') {
            $start = $today->copy()->startOfMonth();
            $end = $today->copy()->endOfMonth();
        } else {
            $start = $today->copy();
            $end = $today->copy()->endOfDay();
        }

        $data = Transaksi::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('dashboard.laporan', [
                'transaksis' => $data,
                'filter' => $filter
            ]);
            return $pdf->download('laporan-transaksi.pdf');
        }

        abort(404, 'Format tidak tersedia');
    }
}
