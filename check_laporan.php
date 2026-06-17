<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Setup database
$app->make('db');

// Check laporan
$laporan = \App\Models\Laporan::find(5);
if ($laporan) {
    echo "=== Laporan ID 5 ===\n";
    echo "Perjanjian ID: " . $laporan->perjanjian_id . "\n";
    echo "Validation Results: " . json_encode($laporan->validation_results, JSON_PRETTY_PRINT) . "\n\n";
    
    if ($laporan->realisasi_tb1) {
        $data = json_decode($laporan->realisasi_tb1, true);
        echo "=== Realisasi TB1 Data ===\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
} else {
    echo "Laporan not found\n";
}
?>
