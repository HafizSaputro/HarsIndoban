<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanPembelianController;


use Illuminate\Auth\Events\Login;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/barang', [BarangController::class, 'index'])->name('barang')->middleware('auth');
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/barang', function () {
//         return view('admin.barang.index');
//     });
// });
// Route::get('/admin', function () {
//     return view('admin.barang.index');  // Perbaiki path view
// });
// Route::middleware(['auth', 'role:user'])->group(function () {
//     Route::get('/kasir/barang', function () {
//         return "Selamat datang, Kasir!";
//     })->name('kasir.barang');
// });

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/grafik-data', [DashboardController::class, 'grafikData'])->name('dashboard.grafik-data');
    Route::get('/dashboard/ringkasan-data', [DashboardController::class, 'ringkasanData']);
    Route::get('/dashboard/export/{format}', [DashboardController::class, 'export'])->name('laporan.export');
    // Barang
    Route::get('/barang/lowstock', [BarangController::class, 'lowStock'])->name('barang.lowstock');
    Route::get('/barang/cari', [BarangController::class, 'cari'])->name('barang.cari');
    Route::resource('barang', BarangController::class);

    // kategori
    Route::get('/kategori/cari', [KategoriController::class, 'cari'])->name('kategori.cari');
    Route::resource('kategori', KategoriController::class);

    
    Route::get('/supplier/cari', [SupplierController::class, 'cari'])->name('supplier.cari');
    Route::get('/supplier/beta', [SupplierController::class, 'beta'])->name('supplier.beta');
    Route::resource('supplier', SupplierController::class);
    
    Route::get('/user/cari', [UserController::class, 'cari'])->name('user.cari');
    Route::resource('user', UserController::class);
    
    // Route::post('/transaksi-penjualan/store', [TransaksiPenjualanController::class, 'store'])->name('transaksi.store');
    // Route::post('/cart/add', [TransaksiPenjualanController::class, 'addToCart'])->name('cart.add');
    // Route::resource('transaksi-penjualan', TransaksiPenjualanController::class);

    
    Route::get('/transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::resource('transaksi', TransaksiController::class);

    Route::resource('barang-masuk', BarangMasukController::class);
    
    Route::get('/laporan-penjualan/export', [LaporanPenjualanController::class, 'exportPDF'])->name('laporan-penjualan.export');
    Route::resource('laporan-penjualan', LaporanPenjualanController::class);

    Route::get('/laporan-pembelian/export', [LaporanPembelianController::class, 'exportPDF'])->name('laporan-pembelian.export');
    Route::resource('laporan-pembelian', LaporanPembelianController::class);
        

});
// barang

// Route::get('/supplier', [SupplierController::class, 'index']);





Route::resource('barang', BarangController::class);
Route::get('/admin', function () {
    return view('admin.barang.index');  // Perbaiki path view
});

Route::resource('barang', BarangController::class);





// Route::get('/barang', function () {
//     return view('admin.barang'); // Pastikan Anda memiliki file `dashboard.blade.php`
// })->middleware('auth'); // Hanya pengguna yang login bisa mengakses


// Route::get('/barang', [BarangController::class, 'index'])->name('admin.barang');
// Route::get('/kasir', [KasirController::class, 'index'])->name('kasir
// .barang');
// Route::get('/barang', [BarangController::class, 'index'])->name('admin.barang');
// Route::middleware('auth')->get('/barang', [BarangController::class, 'index'])->name('admin.barang');


// // Route untuk admin
// Route::middleware('auth')->get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang');

// // Route untuk user biasa
// Route::middleware('auth')->get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

// // Route umum jika pengguna tidak sesuai role
// Route::middleware('auth')->get('/home', [HomeController::class, 'index'])->name('home');

