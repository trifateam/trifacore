<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batch';
    protected $primaryKey = 'id_batch';
    protected $guarded = [];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'id_kandang');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function produksiTelur()
    {
        return $this->hasMany(ProduksiTelur::class, 'id_batch');
    }

    public function konsumsiPakan()
    {
        return $this->hasMany(KonsumsiPakan::class, 'id_batch');
    }

    public function konsumsiVitamin()
    {
        return $this->hasMany(KonsumsiVitamin::class, 'id_batch');
    }

    public function deplesi()
    {
        return $this->hasMany(Deplesi::class, 'id_batch');
    }
}
