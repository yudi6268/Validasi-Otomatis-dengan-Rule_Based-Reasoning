<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom ke tabel laporans untuk menyimpan realisasi per triwulan
        Schema::table('laporans', function (Blueprint $table) {
            if (!Schema::hasColumn('laporans', 'triwulan_aktif')) {
                $table->integer('triwulan_aktif')->default(1)->after('tahun');
            }
            
            if (!Schema::hasColumn('laporans', 'realisasi_tb1')) {
                $table->text('realisasi_tb1')->nullable()->after('triwulan_aktif');
            }
            if (!Schema::hasColumn('laporans', 'realisasi_tb2')) {
                $table->text('realisasi_tb2')->nullable()->after('realisasi_tb1');
            }
            if (!Schema::hasColumn('laporans', 'realisasi_tb3')) {
                $table->text('realisasi_tb3')->nullable()->after('realisasi_tb2');
            }
            if (!Schema::hasColumn('laporans', 'realisasi_tb4')) {
                $table->text('realisasi_tb4')->nullable()->after('realisasi_tb3');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn([
                'triwulan_aktif',
                'realisasi_tb1',
                'realisasi_tb2',
                'realisasi_tb3',
                'realisasi_tb4'
            ]);
        });
    }
};
