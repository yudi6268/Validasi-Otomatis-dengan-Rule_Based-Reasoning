<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$start_total = microtime(true);
$user = App\Models\User::where('email', '!=', 'admin@example.com')->first();
echo "User fetch: " . (microtime(true) - $start_total) . "s\n";

$dashboard = app('App\Http\Controllers\DashboardController');

$start = microtime(true);
$jabatanLower = strtolower((string) ($user->jabatan ?? ''));
$isPihakKeduaMode = str_contains($jabatanLower, 'direktur') || str_contains($jabatanLower, 'wadir') || str_contains($jabatanLower, 'wakil direktur');

if ($isPihakKeduaMode) {
    $wadirPerjanjianItems = \App\Models\Perjanjian::with('user')
        ->where('pihak2_name', $user->name)
        ->orWhere('pihak2_name', 'LIKE', '%' . $user->name . '%')
        ->orWhere('pihak2_jabatan', 'LIKE', '%' . $user->jabatan . '%')
        ->get();
} else {
    $wadirPerjanjianItems = \App\Models\Perjanjian::with('user')->where('user_id', $user->id)->get();
}
echo "Perjanjian fetch: " . (microtime(true) - $start) . "s (Count: " . $wadirPerjanjianItems->count() . ")\n";

$start = microtime(true);
$perjanjianIds = $wadirPerjanjianItems->pluck('id')->toArray();
$laporansForWaiting = \App\Models\Laporan::whereIn('perjanjian_id', $perjanjianIds)->get();
echo "Laporan fetch: " . (microtime(true) - $start) . "s (Count: " . $laporansForWaiting->count() . ")\n";

$start = microtime(true);
$allLaporans = $laporansForWaiting;

$chartData = [];
if ($isPihakKeduaMode) {
    $wadirPerjanjians = $wadirPerjanjianItems->filter(function ($p) use ($user) {
        return $p->user_id !== $user->id;
    });

    $chartData = []; // Wait, it calls buildWadirChartData inside the real loop!
    // Let's call buildAggregatedWadirChartData
    $reflectionMethod = new ReflectionMethod(get_class($dashboard), 'buildAggregatedWadirChartData');
    $reflectionMethod->setAccessible(true);
    $chartData = $reflectionMethod->invokeArgs($dashboard, [$wadirPerjanjians, $allLaporans]);
} else {
    $reflectionMethod = new ReflectionMethod(get_class($dashboard), 'buildAggregatedWadirChartData');
    $reflectionMethod->setAccessible(true);
    $chartData = $reflectionMethod->invokeArgs($dashboard, [$wadirPerjanjianItems, $allLaporans]);
}
echo "Chart Data Build: " . (microtime(true) - $start) . "s\n";
echo "Total execution: " . (microtime(true) - $start_total) . "s\n";

