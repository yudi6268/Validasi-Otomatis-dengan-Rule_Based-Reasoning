<?php

use Illuminate\Support\Facades\Route;
use App\Models\Perjanjian;

Route::get('/test-insert-perjanjian', function() {
    try {
        // Generate nomor perjanjian
        $year = date('Y');
        $month = date('m');
        $latestPerjanjian = Perjanjian::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $latestPerjanjian ? ((int) substr($latestPerjanjian->nomor_perjanjian, 0, 4)) + 1 : 1;
        $nomorPerjanjian = sprintf('%04d/PK-RSUD/%s/%s', $sequence, $month, $year);
        
        echo "Nomor Perjanjian: " . $nomorPerjanjian . "<br>";
        
        $data = [
            'nomor_perjanjian' => $nomorPerjanjian,
            'user_id' => auth()->id(),
            'pihak1_name' => auth()->user()->nama,
            'pihak1_jabatan' => auth()->user()->jabatan,
            'pihak1_nip' => auth()->user()->nip,
            'pihak2_name' => 'Test Direktur',
            'pihak2_jabatan' => 'Direktur',
            'jabatan' => 'Direktur',
            'location' => 'Pasuruan',
            'agreement_date' => now(),
        ];
        
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        
        $perjanjian = Perjanjian::create($data);
        
        echo "Berhasil insert! ID: " . $perjanjian->id;
        
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
        echo "<br><br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});
