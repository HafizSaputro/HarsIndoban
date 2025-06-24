<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $kategoris = kategori::all();        
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated =  $request->validate([
                'nama' => 'required|string|unique:kategori|max:255',
            ], [
                'nama.unique' => 'Nama Kategori sudah terdaftar!!!',
            ]);

            Kategori::create($validated);

            // Mengarahkan ke view sukses
            return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan!!!');
        } catch (QueryException $e) {
            // Menangani error duplikasi email
            return back()->withErrors(['nama' => 'Barang sudah ada'])->withInput();
        }

        
    }
    public function cari(Request $request)
    {
        $query = $request->get('search');
        $kategoris = Kategori::where('nama', 'like', "%{$query}%")
                                ->get(['id', 'nama']);
    
        return response()->json($kategoris);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         // Mencari kategori berdasarkan ID
         $kategori = Kategori::findOrFail($id);

         // Menampilkan view dengan data kategori
         return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validasi data kategori
         $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Mencari kategori berdasarkan ID
        $kategori = Kategori::findOrFail($id);

        // Memperbarui kategori
        $kategori->update([
            'nama' => $request->input('nama'),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();
    
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
        } catch (QueryException $e) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data barang !!!');
        }
    }
    
}
