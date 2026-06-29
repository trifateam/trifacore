<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPenyesuaianStok extends Model
{
    protected $table = 'log_penyesuaian_stok';
    protected $primaryKey = 'id_log';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
