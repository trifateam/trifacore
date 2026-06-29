<?php

use App\Http\Controllers\Laporan\LabaRugiController;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
$request = Request::create('/laporan/laba-rugi/generate', 'GET', ['bulan' => 6, 'tahun' => 2026]);
try {
    $controller = app()->make(LabaRugiController::class);
    $response = $controller->generate($request);
    file_put_contents(__DIR__.'/test_output_laba.json', $response->getContent());
} catch (Exception $e) {
    file_put_contents(__DIR__.'/test_output_laba.json', $e->getMessage().' on line '.$e->getLine().' in '.$e->getFile());
}
