<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';

    protected $fillable = [
        'no_faktur_beli',
        'id_supplier',
        'id_pengguna',
        'tanggal_pembelian',
        'metode_pembayaran',
        'total_pembelian',
        'kategori_pembelian',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'datetime',
        'total_pembelian' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function hutang()
    {
        return $this->hasOne(Hutang::class, 'id_pembelian', 'id_pembelian');
    }
}
