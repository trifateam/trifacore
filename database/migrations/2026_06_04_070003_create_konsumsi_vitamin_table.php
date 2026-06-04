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
        Schema::create('konsumsi_vitamin', function (Blueprint $table) {
            $table->increments('id_konsumsi_vitamin');
            $table->string('kode_vitamin', 30)->unique();
            $table->unsignedInteger('id_batch');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_pengguna');
            $table->date('tanggal_konsumsi');
            $table->time('waktu_pemberian')->nullable();
            $table->decimal('dosis', 10, 2)->default(0.00);
            $table->decimal('total_penggunaan', 10, 2)->default(0.00);
            $table->string('metode_pemberian', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_batch')->references('id_batch')->on('batch')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsumsi_vitamin');
    }
};
