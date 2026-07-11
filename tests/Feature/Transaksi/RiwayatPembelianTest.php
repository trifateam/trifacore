<?php

use App\Models\Pembelian;
use App\Models\Pengguna;
use App\Models\Supplier;
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

    $this->supplier = Supplier::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_supplier' => 'PT Suplai Pakan',
        'alamat_supplier' => 'Alamat',
        'kontak_supplier' => '08123',
    ]);

    $this->pembelian = Pembelian::create([
        'no_faktur_beli' => 'FB-12345',
        'id_supplier' => $this->supplier->id_supplier,
        'id_pengguna' => $this->admin->id_pengguna,
        'tanggal_pembelian' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 500000,
    ]);
});

test('admin can view riwayat pembelian index', function () {
    $response = $this->actingAs($this->admin)->get('/transaksi/riwayat-pembelian');

    $response->assertStatus(200);
    $response->assertSee('FB-12345');
    $response->assertSee('PT Suplai Pakan');
});

test('admin can view riwayat pembelian detail', function () {
    $response = $this->actingAs($this->admin)->get("/transaksi/riwayat-pembelian/{$this->pembelian->id_pembelian}");

    $response->assertStatus(200);
    $response->assertSee('FB-12345');
});
