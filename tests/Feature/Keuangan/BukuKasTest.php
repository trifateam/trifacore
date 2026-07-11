<?php

use App\Models\AkunKas;
use App\Models\BukuKas;
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

    $this->akun = AkunKas::create([
        'nama_akun' => 'Kas Toko',
        'kategori_akun' => 'Tunai',
        'saldo' => 500000,
    ]);
});

test('owner can view buku kas index', function () {
    BukuKas::create([
        'kode_jurnal' => 'BK-0001',
        'id_akun' => $this->akun->id_akun,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_transaksi' => Carbon::now(),
        'jenis' => 'Masuk',
        'tipe_referensi' => 'penjualan',
        'nominal' => 500000,
        'keterangan' => 'Test Masuk',
    ]);

    $response = $this->actingAs($this->owner)->get('/keuangan/buku-kas');

    $response->assertStatus(200);
    $response->assertSee('BK-0001');
    $response->assertSee('Test Masuk');
});

test('owner can filter buku kas by jenis', function () {
    BukuKas::create([
        'kode_jurnal' => 'BK-0001',
        'id_akun' => $this->akun->id_akun,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_transaksi' => Carbon::now(),
        'jenis' => 'Masuk',
        'tipe_referensi' => 'penjualan',
        'nominal' => 500000,
        'keterangan' => 'Test Masuk',
    ]);

    BukuKas::create([
        'kode_jurnal' => 'BK-0002',
        'id_akun' => $this->akun->id_akun,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_transaksi' => Carbon::now(),
        'jenis' => 'Keluar',
        'tipe_referensi' => 'pembelian',
        'nominal' => 200000,
        'keterangan' => 'Test Keluar',
    ]);

    $response = $this->actingAs($this->owner)->get('/keuangan/buku-kas?jenis=Keluar');

    $response->assertStatus(200);
    $response->assertSee('BK-0002');
    $response->assertDontSee('BK-0001'); // Masuk should be filtered out
});
