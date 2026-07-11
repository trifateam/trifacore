<?php

use App\Models\AkunKas;
use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Penjualan;
use App\Models\Piutang;
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

    $this->pelanggan = Pelanggan::create([
        'id_pengguna' => $this->owner->id_pengguna,
        'nama_lengkap' => 'Pelanggan Ngutang',
        'kategori' => 'Retail',
        'kontak' => '08123',
        'alamat' => 'Alamat A',
    ]);

    $this->akun = AkunKas::create([
        'nama_akun' => 'Kas Toko',
        'kategori_akun' => 'Tunai',
        'saldo' => 500000,
    ]);

    $this->penjualan = Penjualan::create([
        'no_faktur_jual' => 'PJ-100',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_penjualan' => Carbon::now(),
        'metode_pembayaran' => 'PIUTANG',
        'total_harga' => 200000,
        'kategori_penjualan' => 'telur',
        'status_order' => 'Selesai',
    ]);

    $this->piutang = Piutang::create([
        'id_penjualan' => $this->penjualan->id_penjualan,
        'jumlah_piutang' => 200000,
        'sisa_piutang' => 200000,
        'status_piutang' => 'Belum Lunas',
        'tanggal_jatuh_tempo' => Carbon::now()->addDays(7),
    ]);
});

test('owner can view buku piutang index', function () {
    $response = $this->actingAs($this->owner)->get('/keuangan/buku-piutang');

    $response->assertStatus(200);
    $response->assertSee('PJ-100');
    $response->assertSee('Pelanggan Ngutang');
});

test('owner can process partial piutang payment', function () {
    $response = $this->actingAs($this->owner)->post("/keuangan/buku-piutang/lunasi/{$this->piutang->id_piutang}", [
        'nominal' => 100000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertRedirect('/keuangan/buku-piutang');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('piutang', [
        'id_piutang' => $this->piutang->id_piutang,
        'sisa_piutang' => 100000,
        'status_piutang' => 'Lunas Sebagian',
    ]);

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akun->id_akun,
        'saldo' => 600000, // 500000 + 100000
    ]);

    $this->assertDatabaseHas('buku_kas', [
        'id_akun' => $this->akun->id_akun,
        'jenis' => 'Masuk',
        'nominal' => 100000,
    ]);
});

test('owner can process full piutang payment', function () {
    $response = $this->actingAs($this->owner)->post("/keuangan/buku-piutang/lunasi/{$this->piutang->id_piutang}", [
        'nominal' => 200000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertRedirect('/keuangan/buku-piutang');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('piutang', [
        'id_piutang' => $this->piutang->id_piutang,
        'sisa_piutang' => 0,
        'status_piutang' => 'Lunas',
    ]);

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akun->id_akun,
        'saldo' => 700000, // 500000 + 200000
    ]);
});

test('owner cannot pay piutang more than sisa piutang', function () {
    $response = $this->actingAs($this->owner)->post("/keuangan/buku-piutang/lunasi/{$this->piutang->id_piutang}", [
        'nominal' => 300000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseHas('piutang', [
        'id_piutang' => $this->piutang->id_piutang,
        'sisa_piutang' => 200000, // Unchanged
    ]);
});
