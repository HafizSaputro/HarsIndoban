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
    Schema::create('barang', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kategori_id')->constrained('kategoris');
        $table->string('nama_barang')->unique();
        $table->integer('harga_beli');
        $table->integer('harga_jual');
        $table->integer('stok');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
