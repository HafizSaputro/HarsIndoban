<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\supplier;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'supplier']);
    
        if ($request->tanggal) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        }
    
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }
    
        if ($request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }
    
        $barangMasuk = $query->latest()->get();
        $barangs = Barang::all();
        $suppliers = Supplier::all();

        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $barangs = Barang::orderBy('nama_barang')->get();

    
        return view('barang-masuk.index', compact('barangMasuk', 'barangs', 'suppliers'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:supplier,id',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:barang,id',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
        ]);
    
        foreach ($request->barang_id as $index => $barangId) {
            BarangMasuk::create([
                'supplier_id' => $request->supplier_id,
                'barang_id' => $barangId,
                'jumlah' => $request->jumlah[$index],
                'harga_satuan' => $request->harga_satuan[$index],
            ]);
        }
    
        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);

        // Update stok (selisih jumlah lama dan baru)
        $barang = Barang::find($barangMasuk->barang_id);
        $barang->stok -= $barangMasuk->jumlah; // kurangi stok lama
        $barang->stok += $request->jumlah; // tambah stok baru
        $barang->save();

        // Update data barang masuk
        $barangMasuk->update([
            'tanggal_masuk' => $request->tanggal_masuk,
            'barang_id' => $request->barang_id,
            'supplier_id' => $request->supplier_id,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
        ]);

        return redirect()->back()->with('success', 'Data barang masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);

        // Kembalikan stok
        $barang = Barang::find($barangMasuk->barang_id);
        if ($barang) {
            $barang->stok -= $barangMasuk->jumlah;
            $barang->save();
        }

        $barangMasuk->delete();

        return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus.');
    }

    
}
