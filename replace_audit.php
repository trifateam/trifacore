<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/app/Http/Controllers');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // We want to replace:
    // RiwayatAktivitas::create([
    //     'id_pengguna' => Auth::id(),
    //     'aktivitas' => "Mencatat biaya operasional '{$request->nama_pengeluaran}' sebesar Rp" . number_format($request->biaya_operasional, 0, ',', '.')
    // ]);
    // with:
    // \App\Services\AuditService::log("Mencatat biaya operasional '{$request->nama_pengeluaran}' sebesar Rp" . number_format($request->biaya_operasional, 0, ',', '.'));
    
    // Pattern:
    // RiwayatAktivitas::create\s*\(\s*\[\s*(?:'id_pengguna'\s*=>\s*[^,]+,\s*)?'aktivitas'\s*=>\s*(.+?)(?:,\s*)?\]\s*\);
    
    $pattern = '/(?:\\\\?App\\\\Models\\\\)?RiwayatAktivitas::create\s*\(\s*\[\s*\'id_pengguna\'\s*=>\s*[^,]+,\s*\'aktivitas\'\s*=>\s*(.+?)(?:,\s*)?\]\s*\);/s';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $activity = trim($matches[1]);
        return '\App\Services\AuditService::log(' . $activity . ');';
    }, $content);
    
    // Sometimes 'aktivitas' is first
    $pattern2 = '/(?:\\\\?App\\\\Models\\\\)?RiwayatAktivitas::create\s*\(\s*\[\s*\'aktivitas\'\s*=>\s*(.+?),\s*\'id_pengguna\'\s*=>\s*[^,]+(?:,\s*)?\]\s*\);/s';
    $content = preg_replace_callback($pattern2, function($matches) {
        $activity = trim($matches[1]);
        return '\App\Services\AuditService::log(' . $activity . ');';
    }, $content);

    // Some might omit id_pengguna, or have it inside different formatting.
    // Let's do a simple one: if it has RiwayatAktivitas::create
    if ($content !== $original) {
        // Also ensure use App\Models\RiwayatAktivitas; is removed since we don't use it anymore, though it's harmless
        $content = preg_replace('/use App\\\\Models\\\\RiwayatAktivitas;\r?\n/', '', $content);
        file_put_contents($path, $content);
        echo "Updated: $path\n";
        $count++;
    }
}

// Don't forget app/Services where Transaksi services are.
$dir = new RecursiveDirectoryIterator(__DIR__ . '/app/Services');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    if (strpos($path, 'AuditService.php') !== false) continue;

    $content = file_get_contents($path);
    $original = $content;

    $pattern = '/(?:\\\\?App\\\\Models\\\\)?RiwayatAktivitas::create\s*\(\s*\[\s*\'id_pengguna\'\s*=>\s*[^,]+,\s*\'aktivitas\'\s*=>\s*(.+?)(?:,\s*)?\]\s*\);/s';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $activity = trim($matches[1]);
        return '\App\Services\AuditService::log(' . $activity . ');';
    }, $content);

    if ($content !== $original) {
        $content = preg_replace('/use App\\\\Models\\\\RiwayatAktivitas;\r?\n/', '', $content);
        file_put_contents($path, $content);
        echo "Updated: $path\n";
        $count++;
    }
}

echo "Total files updated: $count\n";
