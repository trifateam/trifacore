<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'no_faktur_jual',
        'id_pelanggan',
        'id_pengguna',
        'tanggal_penjualan',
        'metode_pembayaran',
        'total_harga',
        'kategori_penjualan',
        'id_kandang',
        'catatan',
        'status_order',
        'id_pengguna_gudang',
        'tanggal_proses',
        'alamat_pengiriman',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function penggunaGudang()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_gudang', 'id_pengguna');
    }

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'id_kandang', 'id_kandang');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function piutang()
    {
        return $this->hasOne(Piutang::class, 'id_penjualan', 'id_penjualan');
    }
}
