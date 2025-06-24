<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori')->get(); // Ambil data barang beserta kategori
        $kategoris = Kategori::all(); 
        // Kirimkan data barang ke view di folder admin/barang
        return view('barang.index', compact('barangs', 'kategoris'));
    }


    public function create()
    {
        $kategoris = Kategori::all(); // Ambil data kategori (jika ada hubungan dengan kategori)
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {

        try {
            $validated =  $request->validate([
                'nama_barang' => 'required|string|unique:Barang|max:255',
                'kategori_id' => 'required|exists:kategori,id', // Pastikan ini sesuai dengan nama tabel Anda
                'stok' => 'required|integer|min:0',
                'harga_jual' => 'required|numeric|min:0',
                'harga_beli' => 'required|numeric|min:0',
            ], [
                'nama_barang.unique' => 'Nama barang sudah terdaftar!!!',
            ]);
            Barang::create($validated);
            // Mengarahkan ke view sukses
            return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan!!!');
        } catch (QueryException $e) {
            // Menangani error duplikasi email
            return back()->withErrors(['nama_barang' => 'Barang sudah ada'])->withInput();
        }
    }
        public function edit($id)
    {
        // Ambil data barang berdasarkan ID
        $barangs = Barang::findOrFail($id);
        
        // Mengambil semua data kategori
        $kategoris = Kategori::all();
        

        // Tampilkan view edit dengan data barang
        return view('barang.edit', compact('barangs','kategoris'));
    }


    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->input('nama_barang'),
            'kategori_id' => $request->input('kategori_id'),
            'stok' => $request->input('stok'),
            'harga_beli' => $request->input('harga_beli'),
            'harga_jual' => $request->input('harga_jual'),
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }


    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();
            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi!!!');
        }
    }

    public function lowstock()
    {
        $lowStockProducts = Barang::where('stok', '<=', 10)->get();
        $jumlah = $lowStockProducts->count();
    
        return view('barang.low_stock', compact('lowStockProducts'));
    }


    public function cari(Request $request)
    {
        $query = Barang::with('kategori');
    
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
    
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }
    
        return response()->json($query->get());
    }
    
}
