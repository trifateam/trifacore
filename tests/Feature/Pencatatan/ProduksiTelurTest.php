<?php

use App\Models\Barang;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin',
        'username' => 'admin',
        'password' => bcrypt('password'),
        'role' => 'Admin',
    ]);

    foreach (['Telur RB', 'Telur MB', 'Telur MK', 'Telur Pecah'] as $namaBarang) {
        Barang::create([
            'nama_barang' => $namaBarang,
            'kategori_barang' => 'Telur',
            'satuan' => 'Butir',
            'stok_barang' => 0,
            'id_pengguna' => $this->admin->id_pengguna,
        ]);
    }

    $this->kandang = Kandang::create([
        'nama_kandang' => 'Kandang A',
        'tahun_masuk' => 2023,
        'id_pengguna' => $this->admin->id_pengguna,
    ]);

    $this->batch = Batch::create([
        'kode_batch' => 'BCH-01',
        'id_kandang' => $this->kandang->id_kandang,
        'kode_batch' => 'B-' . rand(100, 999),
        'nama_batch' => 'BCH-01/Kandang A',
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => now()->subDays(5)->toDateString(),
        'status_batch' => 'Aktif',
    ]);
});

test('pencatatan produksi telur otomatis melakukan backfilling pada hari yang terlewat', function () {
    // Hari ini
    $hariIni = Carbon::now()->toDateString();

    // Asumsikan batch di-assign 3 hari lalu.
    $this->batch->timestamps = false;
    $this->batch->tgl_masuk = Carbon::now()->subDays(3)->toDateString();
    $this->batch->updated_at = Carbon::now()->subDays(3)->startOfDay();
    $this->batch->save();

    $response = $this->actingAs($this->admin)->post("/pencatatan/produksi-telur/{$this->batch->id_batch}", [
        'jml_telur_rb' => 10,
        'jml_telur_mb' => 0,
        'jml_telur_mk' => 0,
        'jml_telur_pecah' => 0,
        'total_berat_kg' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/pencatatan/produksi-telur');

    // Karena 10 butir dibagi 4 hari (2.5). 10 / 4 = 2 sisa 2.
    // 2 hari terlama mendapat +1 (jadi 3).
    // Jadi distribusi: H-3(3), H-2(3), H-1(2), H-0(2)

    $this->assertDatabaseCount('produksi_telur', 4);

    $this->assertDatabaseHas('produksi_telur', [
        'tanggal_produksi' => Carbon::now()->subDays(3)->toDateString().' 00:00:00',
        'jml_telur_rb' => 3,
    ]);

    $this->assertDatabaseHas('produksi_telur', [
        'tanggal_produksi' => Carbon::now()->toDateString().' 00:00:00',
        'jml_telur_rb' => 2,
    ]);
});
