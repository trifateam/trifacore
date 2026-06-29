<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piutang', function (Blueprint $table) {
            $table->increments('id_piutang');
            $table->unsignedInteger('id_penjualan');
            $table->decimal('jumlah_piutang', 15, 2)->default(0.00);
            $table->decimal('sisa_piutang', 15, 2)->default(0.00);
            $table->string('status_piutang', 20)->default('Belum Lunas');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->dateTime('tanggal_pelunasan')->nullable();
            $table->timestamps();

            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piutang');
    }
};
