<?php

use App\Models\Kandang;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin',
        'username' => 'admin',
        'password' => bcrypt('password'),
        'role' => 'Admin',
    ]);
});

test('admin bisa melihat daftar kandang', function () {
    Kandang::create(['nama_kandang' => 'Kandang A', 'tahun_masuk' => 2023, 'id_pengguna' => $this->admin->id_pengguna]);

    $response = $this->actingAs($this->admin)->get('/master-data/kandang');

    $response->assertStatus(200);
    $response->assertSee('Kandang A');
});

test('admin bisa membuat kandang baru', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/kandang', [
        'nama_kandang' => 'Kandang B',
        'tahun_masuk' => 2024,
    ]);

    $response->assertRedirect('/master-data/kandang');
    $this->assertDatabaseHas('kandang', [
        'nama_kandang' => 'Kandang B',
        'tahun_masuk' => 2024,
    ]);
});

test('validasi pembuatan kandang bekerja', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/kandang', [
        'nama_kandang' => '',
        'tahun_masuk' => 'abc',
    ]);

    $response->assertSessionHasErrors(['nama_kandang', 'tahun_masuk']);
});
