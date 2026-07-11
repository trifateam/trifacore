<?php

use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin Test',
        'username' => 'admin_test',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);

    $this->pelanggan = Pelanggan::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_lengkap' => 'Pelanggan Setia',
        'kategori' => 'Retail',
        'kontak' => '08123',
        'alamat' => 'Alamat A',
    ]);

    $this->penjualan = Penjualan::create([
        'no_faktur_jual' => 'PJ-12345',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'id_pengguna' => $this->admin->id_pengguna,
        'tanggal_penjualan' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 500000,
        'kategori_penjualan' => 'telur',
        'status_order' => 'Selesai',
    ]);
});

test('admin can view riwayat penjualan index', function () {
    $response = $this->actingAs($this->admin)->get('/transaksi/riwayat-penjualan');

    $response->assertStatus(200);
    $response->assertSee('PJ-12345');
    $response->assertSee('Pelanggan Setia');
});

test('admin can view riwayat penjualan detail', function () {
    $response = $this->actingAs($this->admin)->get("/transaksi/riwayat-penjualan/{$this->penjualan->id_penjualan}");

    $response->assertStatus(200);
    $response->assertSee('PJ-12345');
});
