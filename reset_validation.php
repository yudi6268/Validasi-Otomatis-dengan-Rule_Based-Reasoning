<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Setup database
$app->make('db');

// Reset validation for laporan ID 5
$laporan = \App\Models\Laporan::find(5);
if ($laporan) {
    echo "=== Resetting Validation for Laporan ID 5 ===\n";
    echo "Before - Validation Results: " . json_encode($laporan->validation_results, JSON_PRETTY_PRINT) . "\n\n";
    
    $laporan->update([
        'validation_results' => []
    ]);
    
    $laporan->refresh();
    echo "After - Validation Results: " . json_encode($laporan->validation_results, JSON_PRETTY_PRINT) . "\n\n";
    echo "✅ Validation Reset Complete\n";
} else {
    echo "Laporan not found\n";
}
?>
