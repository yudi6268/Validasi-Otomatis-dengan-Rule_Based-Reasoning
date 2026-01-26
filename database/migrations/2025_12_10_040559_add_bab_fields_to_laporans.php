<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            // Bab II B: Capaian Kinerja
            $table->text('capaian_kinerja')->nullable()->after('tabelC');
            
            // Bab II C: Evaluasi
            $table->decimal('persentase_kinerja', 5, 2)->nullable()->after('capaian_kinerja');
            $table->decimal('total_realisasi_anggaran', 15, 2)->nullable()->after('persentase_kinerja');
            $table->decimal('persentase_anggaran', 5, 2)->nullable()->after('total_realisasi_anggaran');
            
            // Bab II D: Rencana Tindak Lanjut
            $table->text('rencana_tindak_lanjut')->nullable()->after('persentase_anggaran');
            
            // Bab II E: Tanggapan
            $table->boolean('tanggapan_pihak2')->default(false)->after('rencana_tindak_lanjut');
            $table->text('catatan_pihak2')->nullable()->after('tanggapan_pihak2');
            
            // Status persetujuan
            $table->boolean('rejected')->default(false)->after('catatan_pihak2');
            $table->text('rejection_reason')->nullable()->after('rejected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn([
                'capaian_kinerja', 'persentase_kinerja', 'total_realisasi_anggaran', 
                'persentase_anggaran', 'rencana_tindak_lanjut', 'tanggapan_pihak2', 
                'catatan_pihak2', 'rejected', 'rejection_reason'
            ]);
        });
    }
};
