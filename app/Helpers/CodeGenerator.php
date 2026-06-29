<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    /**
     * Generate kode unik berformat: PREFIX-YYYYMMDD-XX
     *
     * @param  string  $prefix  (PT, KP, KV, DP, SK, PP, OP, PB, PJ, BK, BHU, BPI, BTC, JRN)
     * @param  string  $table  Nama tabel
     * @param  string  $column  Nama kolom kode
     * @param  int  $digits  Jumlah digit nomor urut di akhir (default 2)
     * @return string Kode unik
     */
    public static function generate($prefix, $table, $column, $digits = 2)
    {
        $datePrefix = date('Ymd');
        $fullPrefix = $prefix.'-'.$datePrefix;

        // Cari record terakhir di tabel dengan kode yang dimulai PREFIX-YYYYMMDD
        $lastRecord = DB::table($table)
            ->where($column, 'like', $fullPrefix.'-%')
            ->orderBy($column, 'desc')
            ->first();

        if (! $lastRecord) {
            // Jika belum ada: PREFIX-YYYYMMDD-01
            return $fullPrefix.'-'.str_pad(1, $digits, '0', STR_PAD_LEFT);
        }

        // Ambil digit terakhir sesuai $digits, increment +1
        $lastCode = $lastRecord->$column;

        // Asumsi struktur kode: PREFIX-YYYYMMDD-XX
        $lastNumber = (int) substr($lastCode, -($digits));

        $nextNumber = str_pad($lastNumber + 1, $digits, '0', STR_PAD_LEFT);

        return $fullPrefix.'-'.$nextNumber;
    }
}
