<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_hutang', function (Blueprint $table) {
            $table->increments('id_pembayaran_hutang');
            $table->string('no_kuitansi_hutang', 30)->unique();
            $table->unsignedInteger('id_hutang');
            $table->unsignedInteger('id_pengguna');
            $table->unsignedInteger('id_akun')->nullable();
            $table->dateTime('tanggal_pembayaran')->useCurrent();
            $table->decimal('jumlah_bayar', 15, 2)->default(0.00);
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_hutang')->references('id_hutang')->on('hutang')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_akun')->references('id_akun')->on('akun_kas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutang');
    }
};
