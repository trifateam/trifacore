<?php

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->sales = Pengguna::create([
        'nama_lengkap' => 'Sales Test',
        'username' => 'sales_test',
        'password' => bcrypt('password123'),
        'role' => 'Sales',
    ]);

    $this->pelanggan = Pelanggan::create([
        'id_pengguna' => $this->sales->id_pengguna,
        'nama_lengkap' => 'Pelanggan Tetap',
        'kategori' => 'Retail',
        'kontak' => '08123',
        'alamat' => 'Alamat A',
    ]);

    $this->telur = Barang::create([
        'id_pengguna' => $this->sales->id_pengguna,
        'nama_barang' => 'Telur Grade A',
        'kategori_barang' => 'Telur',
        'satuan' => 'kg',
        'stok_barang' => 100,
        'dapat_dijual' => 1,
        'dapat_dibeli' => 0,
        'harga' => 25000,
    ]);

    $this->akunKas = AkunKas::create([
        'nama_akun' => 'Kas Toko',
        'kategori_akun' => 'Tunai',
        'saldo' => 0,
    ]);
});

test('sales can view form penjualan telur', function () {
    $response = $this->actingAs($this->sales)->get('/transaksi/penjualan/create?jenis=telur');

    $response->assertStatus(200);
    $response->assertSee('Telur Grade A');
    $response->assertSee('Pelanggan Tetap');
});

test('sales can store penjualan LUNAS', function () {
    $response = $this->actingAs($this->sales)->post('/transaksi/penjualan', [
        'jenis' => 'telur',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'metode_pembayaran' => 'LUNAS',
        'id_akun_kas' => $this->akunKas->id_akun,
        'items' => [
            [
                'id_barang' => $this->telur->id_barang,
                'kuantitas' => 10,
                'harga_satuan' => 25000,
            ],
        ],
    ]);

    $response->assertRedirect('/transaksi/penjualan');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('penjualan', [
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 250000, // 10 * 25000
        'status_order' => 'Menunggu',
    ]);

    $this->assertDatabaseHas('buku_kas', [
        'id_akun' => $this->akunKas->id_akun,
        'jenis' => 'Masuk',
        'nominal' => 250000,
    ]);

    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akunKas->id_akun,
        'saldo' => 250000, // saldo bertambah
    ]);
});

test('sales can store penjualan PIUTANG', function () {
    $response = $this->actingAs($this->sales)->post('/transaksi/penjualan', [
        'jenis' => 'telur',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'metode_pembayaran' => 'PIUTANG',
        'tanggal_jatuh_tempo' => Carbon::tomorrow()->toDateString(),
        'items' => [
            [
                'id_barang' => $this->telur->id_barang,
                'kuantitas' => 10,
                'harga_satuan' => 25000,
            ],
        ],
    ]);

    $response->assertRedirect('/transaksi/penjualan');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('penjualan', [
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'metode_pembayaran' => 'PIUTANG',
        'total_harga' => 250000, // 10 * 25000
        'status_order' => 'Menunggu',
    ]);

    $this->assertDatabaseHas('piutang', [
        'jumlah_piutang' => 250000,
        'sisa_piutang' => 250000,
        'status_piutang' => 'Belum Lunas',
    ]);
});

test('penjualan validation requires items', function () {
    $response = $this->actingAs($this->sales)->post('/transaksi/penjualan', [
        'jenis' => 'telur',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'metode_pembayaran' => 'LUNAS',
        'id_akun_kas' => $this->akunKas->id_akun,
        // no items
    ]);

    $response->assertSessionHasErrors(['items']);
});
