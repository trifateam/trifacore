<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hutang', function (Blueprint $table) {
            $table->increments('id_hutang');
            $table->unsignedInteger('id_pembelian');
            $table->decimal('jumlah_hutang', 15, 2)->default(0.00);
            $table->decimal('sisa_hutang', 15, 2)->default(0.00);
            $table->string('status_hutang', 20)->default('Belum Lunas');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->dateTime('tanggal_pelunasan')->nullable();
            $table->timestamps();

            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hutang');
    }
};
