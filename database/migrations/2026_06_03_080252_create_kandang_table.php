<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kandang', function (Blueprint $table) {
            $table->increments('id_kandang');
            $table->unsignedInteger('id_pengguna');
            $table->string('nama_kandang', 50);
            $table->integer('populasi_saat_ini')->default(0);
            $table->year('tahun_masuk')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kandang');
    }
};
