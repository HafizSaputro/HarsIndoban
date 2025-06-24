<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;


class DetailTransaksi extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama tabel tidak sesuai konvensi plural)
    protected $table = 'detail_transaksi';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'qty',
        'subtotal',
    ];

    // Relasi: DetailTransaksi dimiliki oleh satu Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    // Relasi: DetailTransaksi dimiliki oleh satu Produk
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
