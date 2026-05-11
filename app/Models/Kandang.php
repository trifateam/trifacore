<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    protected $fillable = ['kode_kandang', 'nama_kandang', 'kapasitas', 'status'];
}
