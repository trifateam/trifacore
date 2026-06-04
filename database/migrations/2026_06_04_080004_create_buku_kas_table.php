<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kas', function (Blueprint $table) {
            $table->increments('id_buku_kas');
            $table->string('kode_jurnal', 30)->unique();
            $table->unsignedInteger('id_akun');
            $table->unsignedInteger('id_pengguna');
            $table->dateTime('tanggal_transaksi')->useCurrent();
            $table->enum('jenis', ['Masuk', 'Keluar']);
            $table->string('tipe_referensi', 50)->nullable();
            $table->unsignedInteger('id_referensi')->nullable();
            $table->decimal('nominal', 15, 2)->default(0.00);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_akun')->references('id_akun')->on('akun_kas')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kas');
    }
};
