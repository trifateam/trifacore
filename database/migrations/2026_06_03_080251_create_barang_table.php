<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->unsignedInteger('id_pengguna');
            $table->string('nama_barang', 100);
            $table->string('kategori_barang', 50);
            $table->string('sku', 50)->nullable();
            $table->string('satuan', 20)->nullable();
            $table->decimal('stok_barang', 10, 2)->default(0.00);
            $table->decimal('stok_minimum', 10, 2)->default(0.00);
            $table->decimal('harga', 15, 2)->default(0.00);
            $table->boolean('dapat_dijual')->default(false);
            $table->boolean('dapat_dibeli')->default(false);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
