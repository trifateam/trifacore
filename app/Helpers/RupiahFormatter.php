<?php

namespace App\Helpers;

class RupiahFormatter
{
    /**
     * Format angka ke Rupiah
     * @param mixed $number
     * @return string
     */
    public static function format($number)
    {
        if (!is_numeric($number)) {
            $number = 0;
        }
        
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    /**
     * Parse Rupiah string ke angka
     * @param string $string
     * @return float
     */
    public static function parse($string)
    {
        // Menghapus "Rp ", titik, dan spasi
        $string = str_replace(['Rp', '.', ' '], '', $string);
        // Mengganti koma menjadi titik (jika ada desimal)
        $string = str_replace(',', '.', $string);
        
        return (float) $string;
    }
}
