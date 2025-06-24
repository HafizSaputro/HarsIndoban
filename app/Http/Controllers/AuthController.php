<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan Anda membuat file `login.blade.php` di folder `resources/views/auth`
    }

    public function login(Request $request)
        {
            // Validasi input
            $credentials = $request->validate([
                'username' => 'required|string', // Validasi username
                'password' => 'required',
            ]);

            // Proses autentikasi
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard'); // Ganti dengan halaman tujuan
            }

            // Jika gagal
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ]);
        }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function username()
    {
        return 'username'; // Ganti 'username' dengan nama kolom Anda
    }

    }
