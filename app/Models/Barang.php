<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    
    protected $fillable = [
        'id_pengguna',
        'nama_barang',
        'kategori_barang',
        'sku',
        'satuan',
        'stok_barang',
        'stok_minimum',
        'harga',
        'dapat_dijual',
        'dapat_dibeli',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
