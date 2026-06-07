<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBiaya;
use App\Models\AkunKas;
use App\Models\Operasional;
use App\Models\BukuKas;
use App\Helpers\CodeGenerator;
use Carbon\Carbon;

class OperasionalSeeder extends Seeder
{
    public function run(): void
    {
        Operasional::query()->delete();
        $gaji = KategoriBiaya::where('nama_kategori', 'Gaji Pegawai')->first();
        $listrik = KategoriBiaya::where('nama_kategori', 'Listrik & Air')->first();
        $bensin = KategoriBiaya::where('nama_kategori', 'Transportasi & BBM')->first();
        $akun = AkunKas::where('kategori_akun', 'Bank')->first();
        $admin = \App\Models\Pengguna::where('role', 'Admin')->first();
        $id_pengguna = $admin ? $admin->id_pengguna : 1;

        // Biaya Gaji (Akhir Bulan)
        $dateGaji = Carbon::now()->subDays(5); // Anggap akhir bulan kemaren
        $kodeGaji = CodeGenerator::generate('OP', 'operasional', 'kode_operasional');
        $nominalGaji = 15000000;
        
        $opGaji = Operasional::create([
            'kode_operasional' => $kodeGaji,
            'id_kategori_biaya' => $gaji->id_kategori_biaya,
            'id_akun' => $akun->id_akun,
            'id_pengguna' => $id_pengguna,
            'tanggal_operasional' => $dateGaji->toDateString(),
            'biaya_operasional' => $nominalGaji,
            'nama_pengeluaran' => 'Gaji 5 Pegawai Bulan Lalu',
            'created_at' => $dateGaji,
            'updated_at' => $dateGaji,
        ]);

        $akun->saldo -= $nominalGaji;
        $akun->save();
        
        BukuKas::create([
            'kode_jurnal' => CodeGenerator::generate('BK', 'buku_kas', 'kode_jurnal', 4),
            'id_akun' => $akun->id_akun,
            'id_pengguna' => $id_pengguna,
            'tanggal_transaksi' => $dateGaji,
            'jenis' => 'Keluar',
            'tipe_referensi' => 'operasional',
            'id_referensi' => $opGaji->id_operasional,
            'nominal' => $nominalGaji,
            'keterangan' => "Biaya Operasional - " . $gaji->nama_kategori,
            'created_at' => $dateGaji,
            'updated_at' => $dateGaji,
        ]);

        // Biaya Listrik (Tengah Bulan)
        $dateListrik = Carbon::now()->subDays(15);
        $kodeListrik = CodeGenerator::generate('OP', 'operasional', 'kode_operasional');
        $nominalListrik = 2500000;

        $opListrik = Operasional::create([
            'kode_operasional' => $kodeListrik,
            'id_kategori_biaya' => $listrik->id_kategori_biaya,
            'id_akun' => $akun->id_akun,
            'id_pengguna' => $id_pengguna,
            'tanggal_operasional' => $dateListrik->toDateString(),
            'biaya_operasional' => $nominalListrik,
            'nama_pengeluaran' => 'Tagihan Listrik Kandang',
            'created_at' => $dateListrik,
            'updated_at' => $dateListrik,
        ]);

        $akun->saldo -= $nominalListrik;
        $akun->save();

        BukuKas::create([
            'kode_jurnal' => CodeGenerator::generate('BK', 'buku_kas', 'kode_jurnal', 4),
            'id_akun' => $akun->id_akun,
            'id_pengguna' => $id_pengguna,
            'tanggal_transaksi' => $dateListrik,
            'jenis' => 'Keluar',
            'tipe_referensi' => 'operasional',
            'id_referensi' => $opListrik->id_operasional,
            'nominal' => $nominalListrik,
            'keterangan' => "Biaya Operasional - " . $listrik->nama_kategori,
            'created_at' => $dateListrik,
            'updated_at' => $dateListrik,
        ]);

        // Biaya Bensin (Hari ini)
        $dateBensin = Carbon::now();
        $kodeBensin = CodeGenerator::generate('OP', 'operasional', 'kode_operasional');
        $nominalBensin = 350000;

        $opBensin = Operasional::create([
            'kode_operasional' => $kodeBensin,
            'id_kategori_biaya' => $bensin->id_kategori_biaya,
            'id_akun' => $akun->id_akun,
            'id_pengguna' => $id_pengguna,
            'tanggal_operasional' => $dateBensin->toDateString(),
            'biaya_operasional' => $nominalBensin,
            'nama_pengeluaran' => 'BBM Mobil Pickup Pengiriman Telur',
        ]);

        $akun->saldo -= $nominalBensin;
        $akun->save();

        BukuKas::create([
            'kode_jurnal' => CodeGenerator::generate('BK', 'buku_kas', 'kode_jurnal', 4),
            'id_akun' => $akun->id_akun,
            'id_pengguna' => $id_pengguna,
            'tanggal_transaksi' => $dateBensin,
            'jenis' => 'Keluar',
            'tipe_referensi' => 'operasional',
            'id_referensi' => $opBensin->id_operasional,
            'nominal' => $nominalBensin,
            'keterangan' => "Biaya Operasional - " . $bensin->nama_kategori,
        ]);
    }
}
