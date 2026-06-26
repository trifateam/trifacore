<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Illuminate\Http\Request::create('/laporan/laba-rugi/generate', 'GET', ['bulan' => 6, 'tahun' => 2026]);
try {
    $controller = app()->make(\App\Http\Controllers\Laporan\LabaRugiController::class);
    $response = $controller->generate($request);
    file_put_contents(__DIR__.'/test_output_laba.json', $response->getContent());
} catch (\Exception $e) {
    file_put_contents(__DIR__.'/test_output_laba.json', $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
}
