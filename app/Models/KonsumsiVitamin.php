<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonsumsiVitamin extends Model
{
    protected $table = 'konsumsi_vitamin';

    protected $primaryKey = 'id_konsumsi_vitamin';

    protected $guarded = [];

    protected $casts = [
        'tanggal_konsumsi' => 'date',
        'dosis' => 'decimal:2',
        'total_penggunaan' => 'decimal:2',
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
