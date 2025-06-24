<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Periksa apakah pengguna memiliki peran yang sesuai
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        // Redirect jika pengguna tidak sesuai role
        return redirect()->route('login.login');
    }
}
