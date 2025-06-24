<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telepon',
    ];

    // Relasi ke BarangMasuk
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'supplier_id_supplier');
    }
}
