<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonsumsiPakan extends Model
{
    protected $table = 'konsumsi_pakan';
    protected $primaryKey = 'id_konsumsi';
    protected $guarded = [];

    protected $casts = [
        'tanggal_konsumsi' => 'date',
        'jumlah_pakan_kg' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'id_batch', 'id_batch');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
