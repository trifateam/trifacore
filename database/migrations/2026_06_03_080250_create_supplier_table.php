<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('id_supplier');
            $table->unsignedInteger('id_pengguna');
            $table->string('nama_supplier', 100);
            $table->string('kontak_supplier', 20)->nullable();
            $table->text('alamat_supplier')->nullable();
            $table->string('nama_pic', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
