<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
    ];

    // Relasi ke Barang
    public function barang()
{
    return $this->hasMany(Barang::class,'kategori_id');
}

}
