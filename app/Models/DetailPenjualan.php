<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail_jual';

    protected $fillable = [
        'id_penjualan',
        'id_barang',
        'kuantitas',
        'harga_satuan',
        'sub_total',
    ];

    protected $casts = [
        'kuantitas' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'sub_total' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
