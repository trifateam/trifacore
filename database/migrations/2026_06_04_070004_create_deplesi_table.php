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
        Schema::create('deplesi', function (Blueprint $table) {
            $table->increments('id_deplesi');
            $table->string('kode_deplesi', 30)->unique();
            $table->unsignedInteger('id_batch');
            $table->unsignedInteger('id_pengguna');
            $table->date('tanggal_deplesi');
            $table->integer('jml_mati')->default(0);
            $table->integer('jml_afkir')->default(0);
            $table->timestamps();

            $table->foreign('id_batch')->references('id_batch')->on('batch')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');

            // Maks 1 pencatatan per hari per batch
            $table->unique(['id_batch', 'tanggal_deplesi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deplesi');
    }
};
