<?php

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin',
        'username' => 'admin',
        'password' => bcrypt('password'),
        'role' => 'Admin',
    ]);

    $this->supplier = Supplier::create([
        'nama_supplier' => 'PT Pakan Subur',
        'no_hp' => '08123456789',
        'alamat' => 'Jl. Subur',
        'id_pengguna' => $this->admin->id_pengguna,
    ]);

    $this->akunKas = AkunKas::create([
        'nama_akun' => 'Kas Besar',
        'nomor_rekening' => '12345',
        'saldo' => 1000000,
        'kategori_kas' => 'Kas',
    ]);

    $this->barang = Barang::create([
        'nama_barang' => 'Pakan Starter',
        'kategori_barang' => 'Pakan',
        'satuan' => 'Kg',
        'stok_barang' => 0,
        'dapat_dibeli' => 1,
        'dapat_dijual' => 0,
        'id_pengguna' => $this->admin->id_pengguna,
    ]);
});

test('admin bisa melakukan transaksi pembelian material secara lunas', function () {
    $response = $this->actingAs($this->admin)->post('/transaksi/pembelian', [
        'jenis' => 'material',
        'id_supplier' => $this->supplier->id_supplier,
        'metode_pembayaran' => 'LUNAS',
        'id_akun_kas' => $this->akunKas->id_akun,
        'items' => [
            [
                'id_barang' => $this->barang->id_barang,
                'kuantitas' => 10,
                'harga_satuan' => 5000,
            ],
        ],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/transaksi/pembelian');

    // Cek stok barang bertambah
    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->barang->id_barang,
        'stok_barang' => 10,
    ]);

    // Cek saldo kas berkurang
    $this->assertDatabaseHas('akun_kas', [
        'id_akun' => $this->akunKas->id_akun,
        'saldo' => 950000, // 1,000,000 - (10 * 5000)
    ]);
});
