<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SupplierController extends Controller
{
    // Menampilkan daftar supplier
    public function index()
    {
        // Mengambil semua data supplier
        $suppliers = supplier::all();
        
        // Mengembalikan view dengan data supplier
        return view('supplier.index', compact('suppliers'));
    }

    // Menampilkan form untuk menambah supplier
    public function create()
    {
        return view('supplier.create');
    }

    // Menyimpan supplier baru
    public function store(Request $request)
    {
        // Validasi data
        try {
            $validated =  $request->validate([
                'nama_supplier' => 'required|string|unique:Supplier|max:255',
                'alamat' => 'required|string|max:255',
                'no_telepon' => 'required|string|max:20',
            ], [
                'nama_supplier.unique' => 'Nama Supplier sudah terdaftar!!!',
            ]);

            Supplier::create($validated);

            // Mengarahkan ke view sukses
            return redirect()->route('supplier.index')->with('success', 'Supplier baru berhasil ditambahkan!!!');
        } catch (QueryException $e) {
            // Menangani error duplikasi email
            return back()->withErrors(['nama_supplier' => 'Supplier sudah ada'])->withInput();
        }
    }
    // Menampilkan form untuk mengedit supplier
    public function edit($id)
    {
        // Mencari supplier berdasarkan ID
        $supplier = Supplier::findOrFail($id);
        
        return view('supplier.edit', compact('supplier'));
    }

    // Mengupdate data supplier
    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
        ]);

        // Mencari supplier berdasarkan ID
        $supplier = Supplier::findOrFail($id);
        
        // Update data supplier
        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);

        // Mengarahkan kembali ke halaman daftar supplier dengan pesan sukses
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui');
    }

    // Menghapus supplier
    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
    
            return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus');
        } catch (QueryException $e) {
            return redirect()->route('supplier.index')->with('error', 'Supplier tidak dapat dihapus karena masih digunakan oleh data barang !!!');
        }   
    }

    public function cari(Request $request)
    {
        $query = $request->get('search');
        $suppliers = Supplier::where('nama_supplier', 'like', "%{$query}%")
                                ->get(['id', 'nama_supplier', 'alamat', 'no_telepon']);
    
        return response()->json($suppliers);
    }
}
