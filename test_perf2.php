<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$user = App\Models\User::first();
Auth::login($user);

$controller = app('App\Http\Controllers\DashboardController');

$reflection = new \ReflectionMethod(get_class($controller), 'wadir');
echo "Testing DashboardController->wadir() ...\n";

$start = microtime(true);
$controller->wadir();
echo "Total Time: " . (microtime(true) - $start) . " seconds\n";
