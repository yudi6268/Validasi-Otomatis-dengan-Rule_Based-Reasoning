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
            if (!Schema::hasColumn('laporans', 'triwulan')) {
                $table->string('triwulan')->nullable()->after('perjanjian_id');
            }
            if (!Schema::hasColumn('laporans', 'tahun')) {
                $table->string('tahun')->nullable()->after('triwulan');
            }
            if (!Schema::hasColumn('laporans', 'tabelA')) {
                $table->text('tabelA')->nullable()->after('indikator');
            }
            if (!Schema::hasColumn('laporans', 'tabelB')) {
                $table->text('tabelB')->nullable()->after('tabelA');
            }
            if (!Schema::hasColumn('laporans', 'tabelC')) {
                $table->text('tabelC')->nullable()->after('tabelB');
            }
            if (!Schema::hasColumn('laporans', 'location')) {
                $table->string('location')->nullable()->after('periode_akhir');
            }
            if (!Schema::hasColumn('laporans', 'agreement_date')) {
                $table->date('agreement_date')->nullable()->after('location');
            }
            if (!Schema::hasColumn('laporans', 'pihak1_nip')) {
                $table->string('pihak1_nip')->nullable()->after('pihak1_jabatan');
            }
            if (!Schema::hasColumn('laporans', 'pihak2_nip')) {
                $table->string('pihak2_nip')->nullable()->after('pihak2_jabatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropColumns = [];
        foreach (['triwulan', 'tahun', 'tabelA', 'tabelB', 'tabelC', 'location', 'agreement_date', 'pihak1_nip', 'pihak2_nip'] as $column) {
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
