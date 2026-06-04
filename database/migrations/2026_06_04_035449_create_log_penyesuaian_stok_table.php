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
        Schema::create('log_penyesuaian_stok', function (Blueprint $table) {
            $table->increments('id_log');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_pengguna');
            $table->decimal('stok_lama', 10, 2)->default(0.00);
            $table->decimal('stok_baru', 10, 2)->default(0.00);
            $table->string('alasan', 255);
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang')->cascadeOnDelete();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_penyesuaian_stok');
    }
};
