<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deplesi extends Model
{
    protected $table = 'deplesi';

    protected $primaryKey = 'id_deplesi';

    protected $fillable = [
        'kode_deplesi',
        'id_batch',
        'id_pengguna',
        'tanggal_deplesi',
        'jml_mati',
        'jml_cacat',
    ];

    protected $casts = [
        'tanggal_deplesi' => 'date',
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
