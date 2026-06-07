<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // Pattern 1: 'Rp ' . number_format($var, 0, ',', '.') -> '@rupiah($var)'
    // Inside blade directive like :value="'Rp ' . number_format($totalNominal, 0, ',', '.')"
    // We want to replace "'Rp ' . number_format($x, 0, ',', '.')" with "App\Helpers\RupiahFormatter::format($x)"
    // because it's inside a blade attribute like :value="..."
    $content = preg_replace("/'Rp '\s*\.\s*number_format\(([^,]+),\s*0,\s*','\s*,\s*'\.'\)/", "\App\Helpers\RupiahFormatter::format($1)", $content);
    
    // Pattern 2: Rp {{ number_format($var, 0, ',', '.') }} -> @rupiah($var)
    $content = preg_replace("/Rp\s*\{\{\s*number_format\(([^,]+),\s*0,\s*','\s*,\s*'\.'\)\s*\}\}/", "@rupiah($1)", $content);
    
    // Pattern 3: Rp {{ number_format($var, 2, ',', '.') }} -> @rupiah($var)
    $content = preg_replace("/Rp\s*\{\{\s*number_format\(([^,]+),\s*2,\s*','\s*,\s*'\.'\)\s*\}\}/", "@rupiah($1)", $content);

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
        $count++;
    }
}

echo "Total files updated: $count\n";
