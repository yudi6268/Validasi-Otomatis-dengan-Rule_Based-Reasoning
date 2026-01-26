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
            $table->string('triwulan')->nullable()->after('perjanjian_id');
            $table->string('tahun')->nullable()->after('triwulan');
            $table->text('tabelA')->nullable()->after('indikator');
            $table->text('tabelB')->nullable()->after('tabelA');
            $table->text('tabelC')->nullable()->after('tabelB');
            $table->string('location')->nullable()->after('periode_akhir');
            $table->date('agreement_date')->nullable()->after('location');
            $table->string('pihak1_nip')->nullable()->after('pihak1_jabatan');
            $table->string('pihak2_nip')->nullable()->after('pihak2_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['triwulan', 'tahun', 'tabelA', 'tabelB', 'tabelC', 'location', 'agreement_date', 'pihak1_nip', 'pihak2_nip']);
        });
    }
};
