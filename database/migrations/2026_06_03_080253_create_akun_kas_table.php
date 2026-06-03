<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_kas', function (Blueprint $table) {
            $table->increments('id_akun');
            $table->string('nama_akun', 50);
            $table->string('kategori_akun', 50)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('nama_pemilik', 100)->nullable();
            $table->decimal('saldo', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_kas');
    }
};
