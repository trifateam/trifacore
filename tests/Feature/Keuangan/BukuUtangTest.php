<?php

use App\Models\AkunKas;
use App\Models\Hutang;
use App\Models\Pembelian;
use App\Models\Pengguna;
use App\Models\Supplier;
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

    $this->supplier = Supplier::create([
        'id_pengguna' => $this->owner->id_pengguna,
        'nama_supplier' => 'Supplier Utang',
        'alamat_supplier' => 'Alamat A',
        'kontak_supplier' => '08123',
    ]);

    $this->akun = AkunKas::create([
        'nama_akun' => 'Kas Toko',
        'kategori_akun' => 'Tunai',
        'saldo' => 500000,
    ]);

    $this->pembelian = Pembelian::create([
        'no_faktur_beli' => 'FB-100',
        'id_supplier' => $this->supplier->id_supplier,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_pembelian' => Carbon::now(),
        'metode_pembayaran' => 'PIUTANG',
        'total_harga' => 200000,
    ]);

    $this->hutang = Hutang::create([
        'id_pembelian' => $this->pembelian->id_pembelian,
        'jumlah_hutang' => 200000,
        'sisa_hutang' => 200000,
        'status_hutang' => 'Belum Lunas',
        'tanggal_jatuh_tempo' => Carbon::now()->addDays(7),
    ]);
});

test('owner can view buku utang index', function () {
    $response = $this->actingAs($this->owner)->get('/keuangan/buku-utang');

    $response->assertStatus(200);
    $response->assertSee('FB-100');
    $response->assertSee('Supplier Utang');
});

test('owner can process partial hutang payment', function () {
    $response = $this->actingAs($this->owner)->post("/keuangan/buku-utang/lunasi/{$this->hutang->id_hutang}", [
        'nominal' => 100000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertRedirect('/keuangan/buku-utang');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('hutang', [
        'id_hutang' => $this->hutang->id_hutang,
        'sisa_hutang' => 100000,
        'status_hutang' => 'Lunas Sebagian',
    ]);

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akun->id_akun,
        'saldo' => 400000, // 500000 - 100000
    ]);

    $this->assertDatabaseHas('buku_kas', [
        'id_akun' => $this->akun->id_akun,
        'jenis' => 'Keluar',
        'nominal' => 100000,
    ]);
});

test('owner can process full hutang payment', function () {
    $response = $this->actingAs($this->owner)->post("/keuangan/buku-utang/lunasi/{$this->hutang->id_hutang}", [
        'nominal' => 200000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertRedirect('/keuangan/buku-utang');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('hutang', [
        'id_hutang' => $this->hutang->id_hutang,
        'sisa_hutang' => 0,
        'status_hutang' => 'Lunas',
    ]);

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akun->id_akun,
        'saldo' => 300000, // 500000 - 200000
    ]);
});

test('owner cannot pay hutang more than sisa hutang', function () {
    $response = $this->actingAs($this->owner)->post("/keuangan/buku-utang/lunasi/{$this->hutang->id_hutang}", [
        'nominal' => 300000,
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseHas('hutang', [
        'id_hutang' => $this->hutang->id_hutang,
        'sisa_hutang' => 200000, // Unchanged
    ]);
});

test('owner cannot pay hutang if saldo akun kas insufficient', function () {
    // Kurangi saldo akun
    $this->akun->update(['saldo' => 50000]);

    $response = $this->actingAs($this->owner)->post("/keuangan/buku-utang/lunasi/{$this->hutang->id_hutang}", [
        'nominal' => 100000, // Butuh 100k, saldo 50k
        'id_akun' => $this->akun->id_akun,
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseHas('hutang', [
        'id_hutang' => $this->hutang->id_hutang,
        'sisa_hutang' => 200000, // Unchanged
    ]);
});
