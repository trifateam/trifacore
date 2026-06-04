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
        Schema::create('produksi_pupuk_kandang', function (Blueprint $table) {
            $table->increments('id_pupuk');
            $table->string('kode_pupuk', 30)->unique();
            $table->unsignedInteger('id_kandang');
            $table->unsignedInteger('id_pengguna');
            $table->date('tanggal_kumpul');
            $table->integer('jumlah_karung')->default(0);
            $table->decimal('total_berat_kg', 10, 2)->default(0.00);
            $table->dateTime('tanggal_catat')->useCurrent();
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
        Schema::dropIfExists('produksi_pupuk_kandang');
    }
};
