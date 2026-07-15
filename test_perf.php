<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
App\Models\User::first();
echo 'Q1: ' . (microtime(true) - $start) . PHP_EOL;

$start = microtime(true);
App\Models\User::first();
echo 'Q2: ' . (microtime(true) - $start) . PHP_EOL;
