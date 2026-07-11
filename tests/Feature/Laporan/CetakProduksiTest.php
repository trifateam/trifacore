<?php

use App\Models\Batch;
use App\Models\Kandang;
use App\Models\Pengguna;
use App\Models\ProduksiTelur;
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

    $this->kandang = Kandang::create([
        'id_pengguna' => $this->owner->id_pengguna,
        'nama_kandang' => 'Kandang A',
        'kapasitas' => 1000,
        'status_kandang' => 'Aktif',
    ]);

    $this->batch = Batch::create([
        'kode_batch' => 'B-'.rand(100, 999),
        'nama_batch' => 'Batch 01',
        'id_kandang' => $this->kandang->id_kandang,
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => Carbon::today()->toDateString(),
        'umur_awal_minggu' => 1,
        'status_batch' => 'Aktif',
    ]);

    $this->produksi = ProduksiTelur::create([
        'kode_produksi' => 'PT-123',
        'id_batch' => $this->batch->id_batch,
        'id_pengguna' => $this->owner->id_pengguna,
        'tanggal_produksi' => Carbon::now(),
        'jml_telur_rb' => 100,
        'jml_telur_mb' => 20,
        'jml_telur_mk' => 10,
        'jml_telur_pecah' => 5,
        'total_berat_kg' => 10,
    ]);
});

test('owner can view cetak produksi index', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/cetak/produksi-telur');

    $response->assertStatus(200);
});

test('owner can generate cetak produksi pdf', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/cetak/produksi-telur/pdf?kandang_id='.$this->kandang->id_kandang.'&bulan='.date('m').'&tahun='.date('Y'));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
