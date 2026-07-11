<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();



$start = microtime(true);
$perjs = App\Models\Perjanjian::with('user')->get();
echo "All Perjanjian fetch: " . (microtime(true) - $start) . "s (" . $perjs->count() . " rows)\n";

$start = microtime(true);
$laps = App\Models\Laporan::get();
echo "All Laporan fetch: " . (microtime(true) - $start) . "s (" . $laps->count() . " rows)\n";
