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
        Schema::create('batch', function (Blueprint $table) {
            $table->increments('id_batch');
            $table->string('kode_batch', 30)->unique();
            $table->unsignedInteger('id_kandang')->nullable();
            $table->string('nama_batch', 100)->nullable();
            $table->string('jenis_ayam', 50)->nullable();
            $table->date('tgl_masuk');
            $table->integer('umur_awal_minggu')->default(0);
            $table->date('tgl_afkir')->nullable();
            $table->integer('populasi_awal')->default(0);
            $table->string('status_batch', 20)->default('Aktif');
            $table->unsignedInteger('id_supplier')->nullable();
            $table->decimal('harga_per_ekor', 15, 2)->default(0.00);
            $table->integer('populasi_saat_ini')->default(0);
            $table->timestamps();

            $table->foreign('id_kandang')->references('id_kandang')->on('kandang')->nullOnDelete();
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch');
    }
};
