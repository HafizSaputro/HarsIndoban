<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Kategori;

class BarangSeeder extends Seeder
{
    public function run()
    {
        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Menghapus semua data barang
        DB::table('barang')->truncate();

        // Mengaktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data seeder barang
        $data = [
            [
                'kategori' => 'Oli',
                'nama_barang' => 'Oli Mobil Full Synthetic 5W-30',
                'harga_beli' => 60000,
                'harga_jual' => 85000,
                'stok' => 70,
            ],
            [
                'kategori' => 'Sparepart Mesin',
                'nama_barang' => 'Radiator Mobil',
                'harga_beli' => 300000,
                'harga_jual' => 400000,
                'stok' => 40,
            ],
            [
                'kategori' => 'Aki dan Kelistrikan',
                'nama_barang' => 'Lampu Depan Mobil LED',
                'harga_beli' => 150000,
                'harga_jual' => 220000,
                'stok' => 60,
            ],
            [
                'kategori' => 'Ban dan Velg',
                'nama_barang' => 'Velg Mobil 17 inci',
                'harga_beli' => 1200000,
                'harga_jual' => 1500000,
                'stok' => 25,
            ],
            [
                'kategori' => 'Aksesori Eksterior',
                'nama_barang' => 'Lampu Senja Mobil',
                'harga_beli' => 75000,
                'harga_jual' => 120000,
                'stok' => 90,
            ],
            [
                'kategori' => 'Oli Pelumas',
                'nama_barang' => 'Oli Mesin 10W-40',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
                'stok' => 100,
            ],
            [
                'kategori' => 'Oli Pelumas',
                'nama_barang' => 'Pelumas Rantai',
                'harga_beli' => 30000,
                'harga_jual' => 45000,
                'stok' => 50,
            ],
            [
                'kategori' => 'Sparepart Mesin',
                'nama_barang' => 'Busi Mobil',
                'harga_beli' => 25000,
                'harga_jual' => 40000,
                'stok' => 200,
            ],
            [
                'kategori' => 'Aki dan Kelistrikan',
                'nama_barang' => 'Aki Kering 12V',
                'harga_beli' => 500000,
                'harga_jual' => 650000,
                'stok' => 30,
            ],
            [
                'kategori' => 'Ban dan Velg',
                'nama_barang' => 'Ban Mobil 185/65 R15',
                'harga_beli' => 700000,
                'harga_jual' => 900000,
                'stok' => 20,
            ],
            [
                'kategori' => 'Aksesori Interior',
                'nama_barang' => 'Karpet Mobil',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
                'stok' => 50,
            ],
            [
                'kategori' => 'Aksesori Eksterior',
                'nama_barang' => 'Spoiler Mobil',
                'harga_beli' => 300000,
                'harga_jual' => 450000,
                'stok' => 10,
            ],
            [
                'kategori' => 'Sparepart Mesin',
                'nama_barang' => 'Kampas Rem Mobil',
                'harga_beli' => 150000,
                'harga_jual' => 200000,
                'stok' => 150,
            ],
            [
                'kategori' => 'Aki dan Kelistrikan',
                'nama_barang' => 'Aki Basah 12V',
                'harga_beli' => 350000,
                'harga_jual' => 500000,
                'stok' => 25,
            ],
            [
                'kategori' => 'Ban dan Velg',
                'nama_barang' => 'Ban Mobil 205/55 R16',
                'harga_beli' => 800000,
                'harga_jual' => 1000000,
                'stok' => 15,
            ],
        ];

        foreach ($data as $barang) {
            // Mendapatkan kategori_id berdasarkan nama kategori
            $kategori = Kategori::where('nama', $barang['kategori'])->first();
            
            // Memasukkan data barang ke tabel barang, hanya jika kategori ditemukan
            if ($kategori) {
                DB::table('barang')->insert([
                    'kategori_id' => $kategori->id,
                    'nama_barang' => $barang['nama_barang'],
                    'harga_beli' => $barang['harga_beli'],
                    'harga_jual' => $barang['harga_jual'],
                    'stok' => $barang['stok'],
                ]);
            }
        }
    }
}
