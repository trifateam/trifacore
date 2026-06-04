<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suhu_kandang', function (Blueprint $table) {
            $table->increments('id_suhu');
            $table->string('kode_suhu', 30)->unique();
            $table->unsignedInteger('id_kandang');
            $table->unsignedInteger('id_pengguna');
            $table->dateTime('tanggal_waktu')->useCurrent();
            $table->decimal('suhu', 5, 2)->default(0.00);
            $table->decimal('suhu_min', 5, 2)->nullable();
            $table->decimal('suhu_max', 5, 2)->nullable();
            $table->decimal('kelembaban', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_kandang')->references('id_kandang')->on('kandang')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suhu_kandang');
    }
};
