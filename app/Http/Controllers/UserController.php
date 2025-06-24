<?php

namespace App\Http\Controllers;

// use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        // Kirimkan data pengguna ke view di folder admin/pengguna

        return view('user.index', compact('users'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $users = User::all(); // Ambil data user (jika ada hubungan dengan user)
        return view('user.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated =  $request->validate([
                'username' => 'required|string|max:255|unique:User',
                'password' => 'required|string|max:255',
                'role' => 'required|string|max:255',
            ], [
                'username.unique' => 'Username sudah terdaftar!!!',
            ]);

            // Hash password
            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            // Mengarahkan ke view sukses
            return redirect()->route('user.index')->with('success', 'User baru berhasil ditambahkan!!!');
        } catch (QueryException $e) {
            // Menangani error duplikasi email
            return back()->withErrors(['nama' => 'Username sudah ada'])->withInput();
        }

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
        //
        $users = User::findOrFail($id);
        return view('user.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $users = User::findOrFail($id);
        
        $users->update([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            // 'role' => $request->input('role'),
        ]);
    ;
        
        return redirect()->route('user.index')->with('success', 'Supplier berhasil diperbarui');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $users = User::findOrFail($id);
        
            // Menghapus supplier
            $users->delete();
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
        } catch (QueryException $e) {
            return redirect()->route('user.index')->with('error', 'User tidak dapat dihapus karena masih digunakan oleh data transaksi !!!');
        }
        // Mencari supplier berdasarkan ID


        // Mengarahkan kembali ke halaman daftar supplier dengan pesan sukses

    }

    public function cari(Request $request)
    {
        $query = User::query();
    
        if ($request->search) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }
    
        if ($request->role) {
            $query->where('role', $request->role);
        }
    
        return response()->json($query->get());
    }
    
}
