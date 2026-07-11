<?php

use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Penjualan;
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

    $this->orderMenunggu = Penjualan::create([
        'no_faktur_jual' => 'PJ-Menunggu',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'id_pengguna' => $this->sales->id_pengguna,
        'tanggal_penjualan' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 100000,
        'kategori_penjualan' => 'telur',
        'status_order' => 'Menunggu',
    ]);

    $this->orderDiproses = Penjualan::create([
        'no_faktur_jual' => 'PJ-Diproses',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'id_pengguna' => $this->sales->id_pengguna,
        'tanggal_penjualan' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 150000,
        'kategori_penjualan' => 'telur',
        'status_order' => 'Diproses',
    ]);
});

test('sales can view order aktif list', function () {
    $response = $this->actingAs($this->sales)->get('/transaksi/order-aktif');

    $response->assertStatus(200);
    $response->assertSee('PJ-Menunggu');
    $response->assertSee('PJ-Diproses');
});

test('sales can cancel order with Menunggu status', function () {
    $response = $this->actingAs($this->sales)->patch("/transaksi/order-aktif/{$this->orderMenunggu->id_penjualan}/batalkan");

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('penjualan', [
        'id_penjualan' => $this->orderMenunggu->id_penjualan,
        'status_order' => 'Dibatalkan',
    ]);
});

test('sales cannot cancel order with Diproses status', function () {
    $response = $this->actingAs($this->sales)->patch("/transaksi/order-aktif/{$this->orderDiproses->id_penjualan}/batalkan");

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('penjualan', [
        'id_penjualan' => $this->orderDiproses->id_penjualan,
        'status_order' => 'Diproses', // Unchanged
    ]);
});
