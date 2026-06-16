<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p  = \App\Models\Perjanjian::find(29);
$tB = is_array($p->tabelB) ? $p->tabelB : json_decode((string)($p->tabelB ?? '[]'), true);

echo 'tabelB keys: ' . implode(', ', array_keys($tB)) . PHP_EOL;
foreach ($tB as $k => $v) {
    if (!is_array($v)) echo "  $k => $v" . PHP_EOL;
    else echo "  $k => [" . count($v) . " items] first: " . json_encode($v[0] ?? '') . PHP_EOL;
}

foreach ($programs as $pi => $prog) {
    $progNoKeg = array_diff_key($prog, ['kegiatan' => 1]);
    echo "PROGRAM[$pi] keys: " . implode(', ', array_keys($progNoKeg)) . PHP_EOL;
    echo "PROGRAM[$pi] data: " . json_encode($progNoKeg) . PHP_EOL;

    foreach (($prog['kegiatan'] ?? []) as $ki => $kg) {
        $kgNoSub = array_diff_key($kg, ['subKegiatan' => 1]);
        echo "  KEGIATAN[$ki] keys: " . implode(', ', array_keys($kgNoSub)) . PHP_EOL;
        echo "  KEGIATAN[$ki] data: " . json_encode($kgNoSub) . PHP_EOL;

        foreach (($kg['subKegiatan'] ?? []) as $si => $sub) {
            echo "    SUB[$si] keys: " . implode(', ', array_keys($sub)) . PHP_EOL;
            echo "    SUB[$si] data: " . json_encode($sub) . PHP_EOL;
        }
    }
    echo PHP_EOL;
}
