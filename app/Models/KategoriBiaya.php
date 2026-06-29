<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBiaya extends Model
{
    use HasFactory;

    protected $table = 'kategori_biaya';

    protected $primaryKey = 'id_kategori_biaya';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];
}
