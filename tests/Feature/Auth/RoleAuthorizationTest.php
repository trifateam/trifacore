<?php

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pengguna tanpa hak akses role yang tepat akan ditolak', function () {
    // Pegawai Kandang seharusnya tidak bisa akses Master Data Kategori Biaya
    $pegawaiKandang = Pengguna::create([
        'nama_lengkap' => 'Pegawai Kandang',
        'username' => 'pegawaikandang',
        'password' => bcrypt('password123'),
        'role' => 'Pegawai Kandang',
    ]);

    $response = $this->actingAs($pegawaiKandang)->get('/master-data/kategori-biaya');

    // Middleware role melempar redirect ke dashboard dengan error
    $response->assertRedirect('/dashboard');
    $response->assertSessionHas('error', 'Anda tidak memiliki akses ke halaman ini');
});

test('pengguna dengan hak akses role yang tepat diizinkan', function () {
    // Admin seharusnya bisa akses Master Data Kategori Biaya
    $admin = Pengguna::create([
        'nama_lengkap' => 'Admin',
        'username' => 'admin',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->get('/master-data/kategori-biaya');

    $response->assertStatus(200);
});
