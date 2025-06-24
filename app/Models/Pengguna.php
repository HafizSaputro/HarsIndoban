<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;
    protected $table = 'pengguna';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    // Relasi ke TransaksiPenjualan
    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'pengguna_id_pengguna');
    }
}
