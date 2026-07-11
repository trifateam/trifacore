<?php

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
        'nama_supplier' => 'PT Suplai',
        'alamat_supplier' => 'Alamat',
        'kontak_supplier' => '081',
    ]);

    $this->pembelian = Pembelian::create([
        'no_faktur_beli' => 'FB-123',
        'id_supplier' => $this->supplier->id_supplier,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_pembelian' => Carbon::now(),
        'metode_pembayaran' => 'LUNAS',
        'total_harga' => 100000,
    ]);
});

test('owner can view cetak pembelian index', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/cetak/pembelian-pakan');

    $response->assertStatus(200);
});

test('owner can generate cetak pembelian pdf', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/cetak/pembelian-pakan/pdf?supplier_id=all&bulan='.date('m').'&tahun='.date('Y'));

    $response->assertStatus(200);
    // Usually PDF returns 200 with application/pdf header
    $response->assertHeader('content-type', 'application/pdf');
});
