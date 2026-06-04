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
        Schema::create('produksi_telur', function (Blueprint $table) {
            $table->increments('id_produksi');
            $table->string('kode_produksi', 30)->unique();
            $table->unsignedInteger('id_batch');
            $table->unsignedInteger('id_pengguna');
            $table->date('tanggal_produksi');
            $table->integer('jml_telur_rb')->default(0);
            $table->integer('jml_telur_mk')->default(0);
            $table->integer('jml_telur_mb')->default(0);
            $table->integer('jml_telur_pecah')->default(0);
            $table->decimal('total_berat_kg', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_batch')->references('id_batch')->on('batch')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');

            // Maks 1 pencatatan per hari per batch
            $table->unique(['id_batch', 'tanggal_produksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_telur');
    }
};
