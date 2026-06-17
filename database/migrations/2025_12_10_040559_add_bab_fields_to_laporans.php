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
            if (!Schema::hasColumn('laporans', 'capaian_kinerja')) {
                $table->text('capaian_kinerja')->nullable()->after('tabelC');
            }
            if (!Schema::hasColumn('laporans', 'persentase_kinerja')) {
                $table->decimal('persentase_kinerja', 5, 2)->nullable()->after('capaian_kinerja');
            }
            if (!Schema::hasColumn('laporans', 'total_realisasi_anggaran')) {
                $table->decimal('total_realisasi_anggaran', 15, 2)->nullable()->after('persentase_kinerja');
            }
            if (!Schema::hasColumn('laporans', 'persentase_anggaran')) {
                $table->decimal('persentase_anggaran', 5, 2)->nullable()->after('total_realisasi_anggaran');
            }
            if (!Schema::hasColumn('laporans', 'rencana_tindak_lanjut')) {
                $table->text('rencana_tindak_lanjut')->nullable()->after('persentase_anggaran');
            }
            if (!Schema::hasColumn('laporans', 'tanggapan_pihak2')) {
                $table->boolean('tanggapan_pihak2')->default(false)->after('rencana_tindak_lanjut');
            }
            if (!Schema::hasColumn('laporans', 'catatan_pihak2')) {
                $table->text('catatan_pihak2')->nullable()->after('tanggapan_pihak2');
            }
            if (!Schema::hasColumn('laporans', 'rejected')) {
                $table->boolean('rejected')->default(false)->after('catatan_pihak2');
            }
            if (!Schema::hasColumn('laporans', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropColumns = [];
        foreach (['capaian_kinerja', 'persentase_kinerja', 'total_realisasi_anggaran', 'persentase_anggaran', 'rencana_tindak_lanjut', 'tanggapan_pihak2', 'catatan_pihak2', 'rejected', 'rejection_reason'] as $column) {
            if (Schema::hasColumn('laporans', $column)) {
                $dropColumns[] = $column;
            }
        }

        if (!empty($dropColumns)) {
            Schema::table('laporans', function (Blueprint $table) use ($dropColumns) {
                $table->dropColumn($dropColumns);
            });
        }
    }
};
