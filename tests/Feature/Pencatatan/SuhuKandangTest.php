<?php

use App\Models\Kandang;
use App\Models\Pengguna;
use App\Models\SuhuKandang;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->pegawai = Pengguna::create([
        'nama_lengkap' => 'Pegawai Test',
        'username' => 'pegawai_test',
        'password' => bcrypt('password123'),
        'role' => 'Pegawai Kandang',
    ]);

    $this->kandang = Kandang::create([
        'id_pengguna' => $this->pegawai->id_pengguna, 
        'nama_kandang' => 'Kandang Suhu',
        'kapasitas' => 1000,
        'status_kandang' => 'Aktif',
    ]);
});

test('pegawai can view suhu kandang index', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/suhu');

    $response->assertStatus(200);
    $response->assertSee('Kandang Suhu');
});

test('pegawai can store suhu kandang', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/suhu/{$this->kandang->id_kandang}", [
        'tanggal_waktu' => Carbon::now()->format('Y-m-d H:i:s'),
        'suhu' => 28.5,
        'suhu_min' => 26.0,
        'suhu_max' => 31.0,
        'kelembaban' => 60,
    ]);

    $response->assertRedirect('/pencatatan/suhu');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('suhu_kandang', [
        'id_kandang' => $this->kandang->id_kandang,
        'suhu' => 28.5,
        'kelembaban' => 60,
    ]);
});

test('pegawai cannot store suhu_min >= suhu_max', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/suhu/{$this->kandang->id_kandang}", [
        'tanggal_waktu' => Carbon::now()->format('Y-m-d H:i:s'),
        'suhu' => 28.5,
        'suhu_min' => 31.0,
        'suhu_max' => 26.0,
        'kelembaban' => 60,
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('suhu_kandang', [
        'id_kandang' => $this->kandang->id_kandang,
    ]);
});

test('pegawai cannot store suhu kandang more than 1 time per day per kandang', function () {
    SuhuKandang::create([
        'kode_suhu' => 'SK-1',
        'id_kandang' => $this->kandang->id_kandang,
        'id_pengguna' => $this->pegawai->id_pengguna,
        'tanggal_waktu' => Carbon::now()->format('Y-m-d H:i:s'),
        'suhu' => 29.0,
    ]);

    $response = $this->actingAs($this->pegawai)->post("/pencatatan/suhu/{$this->kandang->id_kandang}", [
        'tanggal_waktu' => Carbon::now()->format('Y-m-d H:i:s'),
        'suhu' => 30.0,
    ]);

    $response->assertRedirect('/pencatatan/suhu');
    $response->assertSessionHas('error');

    $this->assertEquals(1, SuhuKandang::count());
});
