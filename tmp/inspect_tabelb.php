<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Perjanjian::find(29);

function dumpField($name, $value) {
    echo "=== $name ===\n";
    echo 'type=' . gettype($value) . PHP_EOL;
    if (is_string($value) && json_decode($value, true) !== null) {
        echo json_encode(json_decode($value, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    } else {
        var_export($value);
        echo PHP_EOL;
    }
}

dumpField('tabelA', $p->tabelA);
dumpField('tabelB', $p->tabelB);
dumpField('tabelC', $p->tabelC);
