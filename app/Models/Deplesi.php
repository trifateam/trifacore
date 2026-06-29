<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deplesi extends Model
{
    protected $table = 'deplesi';

    protected $primaryKey = 'id_deplesi';

    protected $guarded = [];

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
