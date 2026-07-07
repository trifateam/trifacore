<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->string('no_faktur_jual', 30)->unique();
            $table->unsignedInteger('id_pelanggan');
            $table->unsignedInteger('id_pengguna');
            $table->dateTime('tanggal_penjualan')->useCurrent();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->decimal('total_harga', 15, 2)->default(0.00);
            $table->string('kategori_penjualan', 50)->nullable();
            $table->unsignedInteger('id_kandang')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status_order', 20)->default('Menunggu');
            $table->unsignedInteger('id_pengguna_gudang')->nullable();
            $table->dateTime('tanggal_proses')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_kandang')->references('id_kandang')->on('kandang')->onDelete('set null');
            $table->foreign('id_pengguna_gudang')->references('id_pengguna')->on('pengguna')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
