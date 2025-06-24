<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\supplier;
use App\Models\BarangMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPembelianController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'supplier']);
    
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
    
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
    
        if ($request->filled('sort')) {
            $query->orderBy('jumlah', $request->sort);
        } else {
            $query->latest(); // default sorting
        }
    
        return view('laporan-pembelian.index', [
            'laporan' => $query->get(),
            'suppliers' => Supplier::all()
        ]);
    }

    public function exportPDF(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();
    
        $pembelians = BarangMasuk::with('barang')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $pdf = PDF::loadView('laporan-pembelian.exportPDF', compact('pembelians', 'start', 'end'));
        return $pdf->download('laporan_pembelian_' . now()->format('Ymd_His') . '.pdf');
    }
    
    
}
