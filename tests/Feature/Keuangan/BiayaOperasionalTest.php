<?php

use App\Models\AkunKas;
use App\Models\KategoriBiaya;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Pengguna::create([
        'nama_lengkap' => 'Owner Test',
        'username' => 'owner_test',
        'password' => bcrypt('password123'),
        'role' => 'Owner',
    ]);

    $this->kategori = KategoriBiaya::create([
        'nama_kategori' => 'Listrik',
        'deskripsi' => 'Biaya listrik',
    ]);

    $this->akun = AkunKas::create([
        'nama_akun' => 'Kas Operasional',
        'kategori_akun' => 'Bank',
        'saldo' => 1000000,
    ]);
});

test('owner can view biaya operasional index', function () {
    $response = $this->actingAs($this->owner)->get('/keuangan/biaya-operasional');

    $response->assertStatus(200);
    $response->assertSee('Listrik');
    $response->assertSee('Kas Operasional');
});

test('owner can store biaya operasional', function () {
    $response = $this->actingAs($this->owner)->post('/keuangan/biaya-operasional', [
        'tanggal_operasional' => Carbon::now()->toDateString(),
        'id_kategori_biaya' => $this->kategori->id_kategori_biaya,
        'nama_pengeluaran' => 'Bayar Listrik Bulan Ini',
        'biaya_operasional' => 200000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertRedirect('/keuangan/biaya-operasional');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('operasional', [
        'id_kategori_biaya' => $this->kategori->id_kategori_biaya,
        'nama_pengeluaran' => 'Bayar Listrik Bulan Ini',
        'biaya_operasional' => 200000,
    ]);

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akun->id_akun,
        'saldo' => 800000, // 1000000 - 200000
    ]);

    $this->assertDatabaseHas('buku_kas', [
        'id_akun' => $this->akun->id_akun,
        'jenis' => 'Keluar',
        'nominal' => 200000,
    ]);
});

test('owner cannot store biaya operasional if required fields missing', function () {
    $response = $this->actingAs($this->owner)->post('/keuangan/biaya-operasional', [
        'tanggal_operasional' => Carbon::now()->toDateString(),
        // missing fields
    ]);

    $response->assertSessionHasErrors(['id_kategori_biaya', 'nama_pengeluaran', 'biaya_operasional', 'id_akun']);
});
