<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\detailTransaksi;
use App\Models\Kategori;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';

    protected $fillable = [
        'kategori_id',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'kategori_id');
    }
    

        // Relasi ke TransaksiPenjualan
     public function detailTransaksi()
        {
            return $this->hasMany(DetailTransaksi::class, 'barang_id');
        }
    
        // Relasi ke BarangMasuk
        public function barangMasuk()
        {
            return $this->hasMany(BarangMasuk::class, 'barang_id_barang');
        }
}
