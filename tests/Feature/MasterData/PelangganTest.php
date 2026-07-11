<?php

use App\Models\Pelanggan;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin Test',
        'username' => 'admin_test',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);
});

test('admin can view pelanggan list', function () {
    Pelanggan::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_lengkap' => 'John Doe',
        'kategori' => 'Personal',
        'kontak' => '08123456789',
        'alamat' => 'Jl. Test No. 1',
    ]);

    $response = $this->actingAs($this->admin)->get('/master-data/pelanggan');

    $response->assertStatus(200);
    $response->assertSee('John Doe');
});

test('admin can create new pelanggan', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/pelanggan', [
        'nama_lengkap' => 'Jane Doe',
        'kategori' => 'Retail',
        'kontak' => '08987654321',
        'alamat' => 'Jl. Baru No. 2',
        'latitude' => '-6.200000',
        'longitude' => '106.816666',
    ]);

    $response->assertRedirect('/master-data/pelanggan');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pelanggan', [
        'nama_lengkap' => 'Jane Doe',
        'kategori' => 'Retail',
        'kontak' => '08987654321',
        'alamat' => 'Jl. Baru No. 2',
        'latitude' => '-6.200000',
        'longitude' => '106.816666',
    ]);
});

test('admin can update existing pelanggan', function () {
    $pelanggan = Pelanggan::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_lengkap' => 'Old Name',
        'kategori' => 'Personal',
        'kontak' => '0811111111',
        'alamat' => 'Jl. Lama',
    ]);

    $response = $this->actingAs($this->admin)->put("/master-data/pelanggan/{$pelanggan->id_pelanggan}", [
        'nama_lengkap' => 'New Name',
        'kategori' => 'Distributor',
        'kontak' => '0822222222',
        'alamat' => 'Jl. Update',
    ]);

    $response->assertRedirect('/master-data/pelanggan');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pelanggan', [
        'id_pelanggan' => $pelanggan->id_pelanggan,
        'nama_lengkap' => 'New Name',
        'kategori' => 'Distributor',
        'kontak' => '0822222222',
    ]);
});

test('admin can delete pelanggan if no transactions exist', function () {
    $pelanggan = Pelanggan::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_lengkap' => 'To Be Deleted',
        'kategori' => 'Personal',
        'kontak' => '0000000',
        'alamat' => 'Unknown',
    ]);

    $response = $this->actingAs($this->admin)->delete("/master-data/pelanggan/{$pelanggan->id_pelanggan}");

    $response->assertRedirect('/master-data/pelanggan');
    $response->assertSessionHas('success');

    $this->assertSoftDeleted('pelanggan', [
        'id_pelanggan' => $pelanggan->id_pelanggan,
    ]);
});

test('pelanggan validation requires necessary fields', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/pelanggan', []);

    $response->assertSessionHasErrors([
        'nama_lengkap',
        'alamat',
        'kontak',
        'kategori',
    ]);
});
