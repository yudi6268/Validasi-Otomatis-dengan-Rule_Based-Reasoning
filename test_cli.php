<?php
$bootStart = microtime(true);
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$app->instance('request', $request);

$kernel->bootstrap();
echo "Bootstrap Time: " . (microtime(true) - $bootStart) . " seconds\n";

$user = App\Models\User::where('email', '!=', 'admin@example.com')->first();
Auth::guard('web')->setRequest($request);
Auth::login($user);

$start = microtime(true);
$response = app('App\Http\Controllers\DashboardController')->wadir();
echo "Wadir Time: " . (microtime(true) - $start) . " seconds\n";
echo "Total Time: " . (microtime(true) - $bootStart) . " seconds\n";
