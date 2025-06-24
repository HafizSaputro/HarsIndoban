<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan kategori ke tabel kategoris
        Kategori::create([
            'nama' => 'Ban',
        ]);
        Kategori::create([
            'nama' => 'Oli',
        ]);
        Kategori::create([
            'nama' => 'Aksesoris',
        ]);

        $data = [
            ['nama' => 'Oli Pelumas'],
            ['nama' => 'Sparepart Mesin'],
            ['nama' => 'Aki Kelistrikan'],
            ['nama' => 'Ban Velg'],
            ['nama' => 'Aksesori Interior'],
            ['nama' => 'Aksesori Eksterior'],
        ];

        Kategori::insert($data);
    }
}
