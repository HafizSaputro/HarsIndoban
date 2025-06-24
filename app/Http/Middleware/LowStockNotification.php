<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Models\Barang;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LowStockNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $lowStockCount = Barang::where('stok', '<=', 10)->count();
        View::share('lowStockCount', $lowStockCount); // Bagikan ke semua view
        return $next($request);
    }
}
