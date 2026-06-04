<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->increments('id_detail_jual');
            $table->unsignedInteger('id_penjualan');
            $table->unsignedInteger('id_barang');
            $table->decimal('kuantitas', 10, 2)->default(0.00);
            $table->decimal('harga_satuan', 15, 2)->default(0.00);
            $table->decimal('sub_total', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
