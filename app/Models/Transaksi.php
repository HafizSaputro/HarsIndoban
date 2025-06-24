<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Transaksi extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama tabel tidak sesuai konvensi plural)
    protected $table = 'transaksi';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'user_id',
        'grand_total',
    ];

    // Relasi: Transaksi memiliki banyak DetailTransaksi
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // Relasi: Transaksi dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
{
    return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
}

}
