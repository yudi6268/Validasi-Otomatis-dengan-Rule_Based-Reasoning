<?php

namespace App\Console\Commands;

use App\Models\Perjanjian;
use Illuminate\Console\Command;

class DebugTabel extends Command
{
    protected $signature = 'debug:tabel';

    protected $description = 'Debug tabel structures from latest perjanjian';

    public function handle()
    {
        $perjanjian = Perjanjian::find(24);

        if (!$perjanjian) {
            $this->error('No perjanjian found');
            return;
        }

        $this->line("=== Perjanjian ID: {$perjanjian->id} ===");

        // Simulate what edit.blade.php does
        $tabelA = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
        $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);

        $this->line("\n=== TabelB After Decode ===");
        $this->line("Type: " . gettype($tabelB));
        $this->line("Empty? " . (empty($tabelB) ? 'YES' : 'NO'));
        if (!empty($tabelB)) {
            $this->line("Keys: " . implode(',', array_keys($tabelB)));
            $this->line("has sasaran? " . (isset($tabelB['sasaran']) ? 'YES' : 'NO'));
            if (isset($tabelB['sasaran'])) {
                $this->line("sasaran count: " . count($tabelB['sasaran']));
            }
        }

        $this->line("\n=== Condition Check ===");
        $condition = !empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0;
        $this->line("Condition result: " . ($condition ? 'TRUE' : 'FALSE'));

        if ($condition) {
            $this->line("\n=== Rows Data (should display) ===");
            foreach ($tabelB['sasaran'] as $index => $sasaran) {
                $this->line("Row {$index}:");
                $this->line("  Sasaran: " . substr($sasaran, 0, 50));
                $this->line("  Indikator: " . substr($tabelB['indikator'][$index] ?? '', 0, 50));
                $this->line("  Target: " . ($tabelB['target'][$index] ?? ''));
            }
        } else {
            $this->line("No rows will be displayed due to condition failure");
        }
    }
}
