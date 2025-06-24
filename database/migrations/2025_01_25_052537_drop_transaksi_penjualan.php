<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
          // Hapus tabel 'transaksi'
          Schema::dropIfExists('transaksi_penjualan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
