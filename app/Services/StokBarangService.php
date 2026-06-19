<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Facades\Log;

class StokBarangService
{
    /**
     * Menambahkan stok telur ke tabel barang
     *
     * @param string $namaJenis (e.g. 'Telur RB', 'Telur MB')
     * @param int $jumlah
     * @return Barang
     * @throws \Exception
     */
    public function tambahStokTelur(string $namaJenis, int $jumlah)
    {
        if ($jumlah <= 0) return null;

        $barang = Barang::where('kategori_barang', 'Telur')
            ->where('nama_barang', $namaJenis)
            ->first();

        if (!$barang) {
            throw new \Exception("Master data barang untuk jenis telur \"{$namaJenis}\" tidak ditemukan. Silakan tambahkan terlebih dahulu di menu Data Barang.");
        }

        $barang->stok_barang += $jumlah;
        $barang->save();

        return $barang;
    }

    /**
     * Mengurangi stok telur (untuk rollback atau deplesi stok)
     *
     * @param string $namaJenis
     * @param int $jumlah
     * @return Barang
     * @throws \Exception
     */
    public function kurangiStokTelur(string $namaJenis, int $jumlah)
    {
        if ($jumlah <= 0) return null;

        $barang = Barang::where('kategori_barang', 'Telur')
            ->where('nama_barang', $namaJenis)
            ->first();

        if (!$barang) {
            throw new \Exception("Master data barang untuk jenis telur \"{$namaJenis}\" tidak ditemukan.");
        }

        // Tidak apa-apa jika stok menjadi negatif, tergantung business rule
        // tapi secara realita stok barang berkurang bisa minus jika data telat input
        $barang->stok_barang -= $jumlah;
        $barang->save();

        return $barang;
    }

    /**
     * Menyesuaikan stok telur (saat edit)
     *
     * @param string $namaJenis
     * @param int $jumlahLama
     * @param int $jumlahBaru
     * @return void
     */
    public function adjustStokTelur(string $namaJenis, int $jumlahLama, int $jumlahBaru)
    {
        $selisih = $jumlahBaru - $jumlahLama;

        if ($selisih > 0) {
            $this->tambahStokTelur($namaJenis, $selisih);
        } elseif ($selisih < 0) {
            $this->kurangiStokTelur($namaJenis, abs($selisih));
        }
    }

    /**
     * Mengurangi stok pakan di tabel barang berdasarkan ID barang
     *
     * @param int $id_barang
     * @param float $jumlah_kg
     * @return Barang
     * @throws \Exception
     */
    public function kurangiStokPakan(int $id_barang, float $jumlah_kg)
    {
        if ($jumlah_kg <= 0) return null;

        $barang = Barang::find($id_barang);

        if (!$barang) {
            throw new \Exception("Master data barang pakan tidak ditemukan.");
        }

        if ($barang->stok_barang < $jumlah_kg) {
            throw new \Exception("Stok pakan tidak cukup. Tersedia: " . number_format($barang->stok_barang, 2) . " kg");
        }

        $barang->stok_barang -= $jumlah_kg;
        $barang->save();

        return $barang;
    }

    /**
     * Mengurangi stok vitamin di tabel barang berdasarkan ID barang
     *
     * @param int $id_barang
     * @param float $jumlah
     * @return Barang
     * @throws \Exception
     */
    public function kurangiStokVitamin(int $id_barang, float $jumlah)
    {
        if ($jumlah <= 0) return null;

        $barang = Barang::find($id_barang);

        if (!$barang) {
            throw new \Exception("Master data barang vitamin tidak ditemukan.");
        }

        if ($barang->stok_barang < $jumlah) {
            throw new \Exception("Stok vitamin tidak cukup. Tersedia: " . number_format($barang->stok_barang, 2) . " " . ($barang->satuan ?? 'unit'));
        }

        $barang->stok_barang -= $jumlah;
        $barang->save();

        return $barang;
    }

    /**
     * Menambahkan stok ayam afkir ke tabel barang
     *
     * @param int $jumlah
     * @return Barang
     * @throws \Exception
     */
    public function tambahStokAyamAfkir(int $jumlah)
    {
        if ($jumlah <= 0) return null;

        $barang = Barang::where('dapat_dijual', true)
                ->where(function ($query) {
                    $query->where('nama_barang', 'like', '%Afkir%')
                          ->orWhere('kategori_barang', 'Ayam');
                })
                ->first();

        if (!$barang) {
            throw new \Exception("Master data barang untuk \"Ayam Afkir\" tidak ditemukan. Silakan tambahkan terlebih dahulu di menu Data Barang.");
        }

        $barang->stok_barang += $jumlah;
        $barang->save();

        return $barang;
    }
}
