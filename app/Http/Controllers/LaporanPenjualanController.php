<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User; 
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    //
    public function index(Request $request)
    {
        // Menyusun query
        $query = Transaksi::query();
    
        // Filter berdasarkan Kasir
        if ($request->has('user') && $request->user != '') {
            $query->where('user_id', $request->user);
        }
    
        // Filter berdasarkan Tanggal Mulai
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
    
        // Filter berdasarkan Tanggal Akhir
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    
        // Ambil data transaksi sesuai filter
        $transaksis = $query->get();
    
        // Ambil daftar user untuk dropdown filter
        $users = User::all(); // Misalnya role user ada di tabel users
    
        return view('laporan-penjualan.index', compact('transaksis', 'users'));
    }



    public function exportPDF(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();
    
        $query = Transaksi::with('user')->whereBetween('created_at', [$start, $end]);
    
        if ($request->user) {
            $query->where('user_id', $request->user);
        }
    
        $transaksis = $query->get();
    
        $pdf = PDF::loadView('laporan-penjualan.exportPDF', compact('transaksis', 'start', 'end'));
        return $pdf->download('laporan_penjualan_' . now()->format('Ymd_His') . '.pdf');
    }
    
    

}
