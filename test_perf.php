<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$user = App\Models\User::first(); 
Auth::login($user); 
$start = microtime(true); 
$res = app('App\Http\Controllers\DashboardController')->wadir(); 
echo 'Time: ' . (microtime(true) - $start) . " seconds\n";
