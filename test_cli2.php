<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
$result = \Illuminate\Support\Facades\DB::select('SELECT 1');
echo "DB Query Time: " . (microtime(true) - $start) . " seconds\n";
var_dump($result);
