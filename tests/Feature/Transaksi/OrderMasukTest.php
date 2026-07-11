<?php

use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->gudang = Pengguna::create([
        'nama_lengkap' => 'Pegawai Gudang',
        'username' => 'gudang_test',
        'password' => bcrypt('password123'),
        'role' => 'Pegawai Gudang',
    ]);

    $this->pelanggan = Pelanggan::create([
        'id_pengguna' => $this->gudang->id_pengguna,
        'nama_lengkap' => 'Pelanggan Tetap',
        'kategori' => 'Retail',
        'kontak' => '08123',
        'alamat' => 'Alamat A',
    ]);

    $this->telur = Barang::create([
        'id_pengguna' => $this->gudang->id_pengguna,
        'nama_barang' => 'Telur Grade A',
        'kategori_barang' => 'Telur',
        'satuan' => 'kg',
        'stok_barang' => 100,
        'dapat_dijual' => 1,
        'dapat_dibeli' => 0,
        'harga' => 25000,
    ]);

    $this->order = Penjualan::create([
        'no_faktur_jual' => 'PJ-123',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'id_pengguna' => $this->gudang->id_pengguna,
        'tanggal_penjualan' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 250000,
        'kategori_penjualan' => 'telur',
        'status_order' => 'Menunggu',
    ]);

    DetailPenjualan::create([
        'id_penjualan' => $this->order->id_penjualan,
        'id_barang' => $this->telur->id_barang,
        'kuantitas' => 10,
        'harga_satuan' => 25000,
        'sub_total' => 250000,
    ]);
});

test('pegawai gudang can view order masuk list', function () {
    $response = $this->actingAs($this->gudang)->get('/transaksi/order-masuk');

    $response->assertStatus(200);
    $response->assertSee('PJ-123');
});

test('pegawai gudang can process order', function () {
    $response = $this->actingAs($this->gudang)->patch("/transaksi/order-masuk/{$this->order->id_penjualan}/proses");

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('penjualan', [
        'id_penjualan' => $this->order->id_penjualan,
        'status_order' => 'Diproses',
    ]);
});

test('pegawai gudang can complete order and reduce stok', function () {
    // Ubah status ke Diproses dulu
    $this->order->update(['status_order' => 'Diproses']);

    $response = $this->actingAs($this->gudang)->patch("/transaksi/order-masuk/{$this->order->id_penjualan}/selesai");

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('penjualan', [
        'id_penjualan' => $this->order->id_penjualan,
        'status_order' => 'Selesai',
    ]);

    // Stok awal 100, dibeli 10 -> sisa 90
    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->telur->id_barang,
        'stok_barang' => 90,
    ]);
});

test('pegawai gudang cannot complete order if stok insufficient', function () {
    // Ubah stok menjadi 5
    $this->telur->update(['stok_barang' => 5]);

    $response = $this->actingAs($this->gudang)->patch("/transaksi/order-masuk/{$this->order->id_penjualan}/selesai");

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('penjualan', [
        'id_penjualan' => $this->order->id_penjualan,
        'status_order' => 'Menunggu', // unchanged
    ]);

    // Stok tetap 5
    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->telur->id_barang,
        'stok_barang' => 5,
    ]);
});

test('pegawai gudang can cancel order', function () {
    $response = $this->actingAs($this->gudang)->patch("/transaksi/order-masuk/{$this->order->id_penjualan}/batalkan");

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('penjualan', [
        'id_penjualan' => $this->order->id_penjualan,
        'status_order' => 'Dibatalkan',
    ]);
});
