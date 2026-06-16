<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$laporan = App\Models\Laporan::find(5);
if ($laporan) {
    echo "Laporan ID 5\n";
    echo "kesimpulan length: " . strlen($laporan->kesimpulan ?? '') . "\n";
    echo "Kesimpulan preview: " . substr($laporan->kesimpulan ?? '', 0, 300) . "\n\n";

    $perjanjian = $laporan->perjanjian;
    if ($perjanjian) {
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
        $programs = $tabelC['programs'] ?? [];
        echo "Programs count: " . count($programs) . "\n";
        foreach ($programs as $p) {
            $src = $p['source'] ?? 'NULL';
            $ket = $p['ket'] ?? 'NULL';
            $ktr = $p['keterangan'] ?? 'NULL';
            echo "Program: " . ($p['no'] ?? '?') . " source=$src ket=$ket keterangan=$ktr\n";
            foreach ($p['kegiatan'] ?? [] as $kg) {
                $ks = $kg['source'] ?? 'NULL';
                echo "  Keg: " . ($kg['no'] ?? '?') . " source=$ks\n";
                foreach ($kg['subKegiatan'] ?? [] as $sub) {
                    $ss = $sub['source'] ?? 'NULL';
                    echo "    Sub: " . ($sub['no'] ?? '?') . " source=$ss\n";
                }
            }
        }
        $tw = $laporan->triwulan_aktif ?? 1;
        $relRaw = $laporan->{'realisasi_tb' . $tw};
        $relData = is_array($relRaw) ? $relRaw : json_decode($relRaw ?? 'null', true);
        echo "\nRealisasi rows sample:\n";
        foreach (($relData['rows'] ?? []) as $row) {
            echo "  row=" . ($row['row'] ?? '?') . " realisasi=" . ($row['realisasi'] ?? 'NULL') . " target=" . ($row['target'] ?? 'NULL') . "\n";
        }
    }
}
