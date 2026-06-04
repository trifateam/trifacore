<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');
            $table->string('no_faktur_beli', 30)->unique();
            $table->unsignedInteger('id_supplier');
            $table->unsignedInteger('id_pengguna');
            $table->dateTime('tanggal_pembelian')->useCurrent();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->decimal('total_pembelian', 15, 2)->default(0.00);
            $table->string('kategori_pembelian', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
