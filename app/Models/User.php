<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Transaksi;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nama tabel (opsional jika nama tabel tidak sesuai konvensi plural)
    protected $table = 'user';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    // Kolom yang harus disembunyikan saat serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    // Relasi: User memiliki banyak Transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }
}
