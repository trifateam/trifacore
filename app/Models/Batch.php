<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batch';

    protected $primaryKey = 'id_batch';

    protected $guarded = [];

    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_afkir' => 'date',
    ];

    public function getSisaHariAfkirAttribute()
    {
        if (! $this->tgl_afkir) {
            return null;
        }

        return max(0, (int) Carbon::today()->diffInDays($this->tgl_afkir, false));
    }

    public function getUmurSaatIniMingguAttribute()
    {
        $hariSejakMasuk = Carbon::parse($this->tgl_masuk)->diffInDays(Carbon::today());

        return $this->umur_awal_minggu + floor($hariSejakMasuk / 7);
    }

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
