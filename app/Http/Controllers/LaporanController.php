<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function export(Request $request)
    {
        $filter = $request->get('filter', 'harian');
        $format = $request->get('format', 'pdf');

        $query = Transaksi::with('user');

        if ($filter === 'harian') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'mingguan') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter === 'bulanan') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('dashboard.laporan', [
            'transaksis' => $data,
            'filter' => $filter
        ]);
        
        return $pdf->stream('laporan-transaksi.pdf');
        
    }
}
