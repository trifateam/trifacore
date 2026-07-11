<?php

use App\Models\AkunKas;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin Test',
        'username' => 'admin_test',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);
});

test('admin can view rekening list', function () {
    AkunKas::create([
        'nama_akun' => 'BCA Utama',
        'kategori_akun' => 'Bank',
        'no_rekening' => '1234567890',
        'nama_pemilik' => 'PT Trifacore',
        'saldo' => 1000000,
    ]);

    $response = $this->actingAs($this->admin)->get('/master-data/rekening');

    $response->assertStatus(200);
    $response->assertSee('BCA Utama');
});

test('admin can create new rekening', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/rekening', [
        'nama_akun' => 'Kas Tunai',
        'kategori_akun' => 'Tunai',
        'no_rekening' => null,
        'nama_pemilik' => 'Admin Kasir',
        'saldo' => 500000,
    ]);

    $response->assertRedirect('/master-data/rekening');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('akun_kas', [
        'nama_akun' => 'Kas Tunai',
        'kategori_akun' => 'Tunai',
        'saldo' => 500000,
    ]);
});

test('admin can update existing rekening without modifying saldo', function () {
    $rekening = AkunKas::create([
        'nama_akun' => 'BRI Lama',
        'kategori_akun' => 'Bank',
        'no_rekening' => '11111',
        'nama_pemilik' => 'Lama',
        'saldo' => 10000,
    ]);

    $response = $this->actingAs($this->admin)->put("/master-data/rekening/{$rekening->id_akun}", [
        'nama_akun' => 'BRI Baru',
        'kategori_akun' => 'Bank',
        'no_rekening' => '22222',
        'nama_pemilik' => 'Baru',
        'saldo' => 50000, // Should be ignored by controller
    ]);

    $response->assertRedirect('/master-data/rekening');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $rekening->id_akun,
        'nama_akun' => 'BRI Baru',
        'no_rekening' => '22222',
        'saldo' => 10000, // Saldo remains unchanged
    ]);
});

test('rekening validation requires necessary fields', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/rekening', []);

    $response->assertSessionHasErrors([
        'nama_akun',
        'kategori_akun',
    ]);
});
