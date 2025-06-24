<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiPenjualanController extends Controller
{
   // Menampilkan daftar transaksi
   public function index()
   {
       $transaksi = Transaksi::all();
       $barangs = Barang::all(); 
       return view('admin.transaksi-penjualan.index', compact('transaksi','barangs'));
   }

   // Menampilkan form tambah transaksi
   public function create()
   {
       return view('transaksi-penjualan.create');
   }

   public function store(Request $request)
   {
       // Validasi input
       $request->validate([
           'grandtotal' => 'required|numeric|min:1',
           'items' => 'required|array|min:1',
           'items.*.nama_barang' => 'required|string',
           'items.*.qty' => 'required|integer|min:1',
           'items.*.subtotal' => 'required|numeric|min:0'
       ]);
   
       try {
           DB::beginTransaction();
   
           // Simpan Transaksi
           $transaksi = Transaksi::create([
               'kode_transaksi' => 'TRX-' . time(),
               'user_id' => Auth::id(),
               'grand_total' => $request->grandtotal,
           ]);
   
           // Simpan Detail Transaksi
           foreach ($request->items as $item) {
               // Cari barang berdasarkan nama
               $barang = Barang::where('nama_barang', $item['nama_barang'])->first();
   
               // Jika barang tidak ditemukan, batalkan transaksi
               if (!$barang) {
                   DB::rollBack();
                   return response()->json([
                       'success' => false,
                       'message' => "Barang '{$item['nama_barang']}' tidak ditemukan.",
                   ], 404);
               }
   
               // Cek apakah stok cukup
               if ($barang->stok < $item['qty']) {
                   DB::rollBack();
                   return response()->json([
                       'success' => false,
                       'message' => "Stok barang '{$barang->nama_barang}' tidak mencukupi.",
                   ], 400);
               }
   
               // Simpan Detail Transaksi
               DetailTransaksi::create([
                   'transaksi_id' => $transaksi->id,
                   'barang_id' => $barang->id,
                   'qty' => $item['qty'],
                   'subtotal' => $item['subtotal']
               ]);
   
               // Kurangi stok barang
               $barang->stok -= $item['qty'];
               $barang->save();
           }
   
           DB::commit();
           return response()->json([
               'success' => true,
               'message' => 'Transaksi berhasil disimpan!',
               'transaksi_id' => $transaksi->id,
           ]);
       } catch (\Exception $e) {
           DB::rollBack();
           return response()->json([
               'success' => false,
               'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
           ], 500);
       }
   }
   

   // Menyimpan data transaksi baru
//    public function store(Request $request)
//    {
//         // Validasi data yang diterima
//         // $request->validate([
//         //     'grandtotal' => 'required|numeric',
//         //     'bayar' => 'required|numeric',
//         //     'items' => 'required|array',
//         //     'items.*.barang_id' => 'required|integer',
//         //     'items.*.qty' => 'required|integer|min:1',
//         //     'items.*.subtotal' => 'required|numeric',
//         // ]);
//          // Validasi input
//          $request->validate([
//             'items' => 'required|array',
//             'items.*.barang_id' => 'required|exists:barang,id',
//             'items.*.qty' => 'required|integer|min:1',
//             'grand_total' => 'required|numeric|min:1',
//         ]);

//         // Generate kode transaksi unik
//         $kodeTransaksi = 'TRX-' . time();

//         // Simpan transaksi utama
//         $transaksi = Transaksi::create([
//             'kode_transaksi' => $kodeTransaksi,
//             'user_id' => Auth::id(), // Ambil ID user yang sedang login
//             'grand_total' => $request->grand_total,
//         ]);


//         // Simpan detail transaksi
//         foreach ($request->items as $item) {
//             $barang = Barang::find($item['barang_id']);

//             DetailTransaksi::create([
//                 'transaksi_id' => $transaksi->id,
//                 'barang_id' => $item['barang_id'],
//                 'qty' => $item['qty'],
//                 'subtotal' => $barang->harga * $item['qty'], // Hitung subtotal
//             ]);
//         }

//          return response()->json([
//             'success' => true,
//             'message' => 'Transaksi berhasil disimpan!',
//             'transaksi_id' => $transaksi->id
//         ]);

//     }

   // Menampilkan detail transaksi
   public function show($id)
   {
       $transaksi = Transaksi::findOrFail($id);
       return view('transaksi-penjualan.show', compact('transaksi'));
   }

   // Menampilkan form edit transaksi
   public function edit($id)
   {
       $transaksi = Transaksi::findOrFail($id);
       return view('transaksi-penjualan.edit', compact('transaksi'));
   }

   // Memperbarui data transaksi
   public function update(Request $request, $id)
   {
       $request->validate([
           'tanggal' => 'required|date',
           'total_item' => 'required|integer',
           'total_harga' => 'required|numeric',
           'kasir' => 'required|string|max:255',
       ]);

       $transaksi = Transaksi::findOrFail($id);
       $transaksi->update($request->all());

       return redirect()->route('transaksi-penjualan.index')->with('success', 'Transaksi berhasil diperbarui.');
   }

   // Menghapus transaksi
   public function destroy($id)
   {
       $transaksi = Transaksi::findOrFail($id);
       $transaksi->delete();

       return redirect()->route('transaksi-penjualan.index')->with('success', 'Transaksi berhasil dihapus.');
   }

//    public function add(Request $request)
// {
//     $nama_barang = $request->nama_barang;
//     $qty = $request->qty;

//     // Ambil data produk berdasarkan barcode
//     $barang = Barang::where('nama_barang', $nama_barang)->first();

//     if (!$barang) {
//         return response()->json(['error' => 'Produk tidak ditemukan'], 404);
//     }

//     // Hitung total
//     $total = $barang->price * $qty;

//     // Tambahkan ke keranjang (sesi atau database)
//     $cart = session()->get('cart', []);
//     $cart[] = [
//         'name' => $barang->name,
//         'price' => $barang->price,
//         'qty' => $qty,
//         'total' => $total,
//     ];
//     session()->put('cart', $cart);

//     // Kirim respons HTML untuk memperbarui tabel keranjang
//     $html = view('partials.cart-items', ['cart' => $cart])->render();
//     $subtotal = array_sum(array_column($cart, 'total'));

//     return response()->json(['html' => $html, 'subtotal' => $subtotal]);
// }

// public function tambahBarang(Request $request)
// {
//     $barang = Barang::find($request->barang_id);

//     if (!$barang) {
//         return response()->json(['error' => 'Barang tidak ditemukan'], 404);
//     }

//     $item = [
//         'id' => $barang->id,
//         'nama_barang' => $barang->nama,
//         'harga' => $barang->harga,
//         'qty' => $request->qty,
//         'total' => $barang->harga * $request->qty,
//     ];

//     return response()->json(['success' => true, 'item' => $item]);
// }

// Memproses transaksi dan menyimpan ke database
// public function prosesTransaksi(Request $request)
// {
//     $request->validate([
//         'items' => 'required|array',
//         'cash' => 'required|numeric|min:0',
//         'subtotal' => 'required|numeric|min:0',
//         'grand_total' => 'required|numeric|min:0',
//     ]);

//     $transaksi = TransaksiPenjualan::create([
//         'user_id' => auth()->user()->id,
//         'tanggal' => now(),
//         'total_harga' => $request->grand_total,
//     ]);

//     foreach ($request->items as $item) {
//         $transaksi->detail()->create([
//             'barang_id' => $item['id'],
//             'jumlah' => $item['qty'],
//             'total_harga' => $item['total'],
//         ]);
//     }

//     return response()->json(['success' => true, 'message' => 'Transaksi berhasil diproses']);
// }

public function addToCart(Request $request)
    {
        // Validasi input
        $request->validate([
            'barang' => 'required|string|exists:barang,nama_barang',
            'qty' => 'required|integer|min:1',
        ]);

        // Cari barang berdasarkan nama
        $barang = Barang::where('nama_barang', $request->barang)->first();

        if ($barang) {
            return response()->json([
                'success' => true,
                'barang' => $barang->nama_barang,
                'harga' => $barang->harga_jual,
                'qty' => $request->qty,
                'total' => $barang->harga_jual * $request->qty,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan',
        ], 404);
    }

}
