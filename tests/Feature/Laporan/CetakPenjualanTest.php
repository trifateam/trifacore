<?php

use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Penjualan;
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
        'nama_lengkap' => 'Pelanggan Setia',
        'kategori' => 'Retail',
        'kontak' => '081',
        'alamat' => 'Alamat A',
    ]);

    $this->penjualan = Penjualan::create([
        'no_faktur_jual' => 'PJ-123',
        'id_pelanggan' => $this->pelanggan->id_pelanggan,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_penjualan' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 200000,
        'kategori_penjualan' => 'telur',
        'status_order' => 'Selesai',
    ]);
});

test('owner can view cetak penjualan index', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/cetak/penjualan-telur');
    
    $response->assertStatus(200);
});

test('owner can generate cetak penjualan pdf', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/cetak/penjualan-telur/pdf?pelanggan_id=all&bulan='.date('m').'&tahun='.date('Y'));
    
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
