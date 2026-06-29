<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduksiTelur extends Model
{
    protected $table = 'produksi_telur';

    protected $primaryKey = 'id_produksi';

    protected $guarded = [];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'total_berat_kg' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'id_batch', 'id_batch');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
