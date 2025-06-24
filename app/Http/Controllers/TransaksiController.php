<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Menampilkan halaman transaksi
    public function index()
    {
        $barangs = Barang::all();
        return view('transaksi-penjualan.index', compact('barangs'));
    }

    // Proses menyimpan transaksi
    public function store(Request $request)
{
    // Validasi data
    $request->validate([
        'items' => 'required|array',
        'grandTotal' => 'required|numeric',
        'bayar' => 'required|numeric',
        'kembalian' => 'required|numeric',
    ]);

    try {
        DB::beginTransaction();

        // Simpan transaksi utama
        $transaksi = Transaksi::create([
            'user_id' => Auth::id(), // Ambil ID user yang login
            'grand_total' => $request->grandTotal,
        ]);

        // Simpan detail transaksi
        foreach ($request->items as $item) {
            // Cari ID barang berdasarkan nama
            $barang = Barang::where('nama_barang', $item['namaBarang'])->first();

            if (!$barang) {
                return response()->json(['message' => 'Barang tidak ditemukan'], 404);
            }

            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $barang->id,
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);

                        // Kurangi stok barang
                        $barang->stok -= $item['qty'];
                        $barang->save();
        }

        DB::commit();
        return response()->json(['message' => 'Transaksi berhasil disimpan!'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}



public function history(Request $request)
{
    $query = Transaksi::with('user')->latest();

    // Filter berdasarkan username (user_id)
    if ($request->filled('kasir')) {
        $query->where('user_id', $request->kasir);
    }

    // Filter berdasarkan tanggal
    if ($request->filled('tanggal')) {
        $query->whereDate('created_at', $request->tanggal);
    }

    $transaksis = $query->get();

    // Ambil daftar kasir (yang pernah transaksi)
    $kasirs = User::whereHas('transaksi')->get();

    return view('transaksi-penjualan.riwayat', compact('transaksis', 'kasirs'));
}






public function show($id)
{
    $transaksi = Transaksi::with(['user', 'details.barang'])->find($id);
    if (!$transaksi) {
        return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
    }
    return response()->json($transaksi);
}



}
