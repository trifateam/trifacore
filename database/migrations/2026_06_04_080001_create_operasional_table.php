<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operasional', function (Blueprint $table) {
            $table->increments('id_operasional');
            $table->string('kode_operasional', 30)->unique();
            $table->unsignedInteger('id_pengguna');
            $table->unsignedInteger('id_kategori_biaya')->nullable();
            $table->unsignedInteger('id_akun')->nullable();
            $table->date('tanggal_operasional');
            $table->string('nama_pengeluaran', 100);
            $table->decimal('biaya_operasional', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_kategori_biaya')->references('id_kategori_biaya')->on('kategori_biaya')->onDelete('set null');
            $table->foreign('id_akun')->references('id_akun')->on('akun_kas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasional');
    }
};
