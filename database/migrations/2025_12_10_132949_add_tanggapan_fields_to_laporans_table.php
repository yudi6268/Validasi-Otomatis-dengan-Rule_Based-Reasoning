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
            $table->boolean('tanggapan_laporan_kurang_baik')->default(false)->after('catatan_pihak2');
            $table->boolean('tanggapan_laporan_sudah_baik')->default(false)->after('tanggapan_laporan_kurang_baik');
            $table->boolean('tanggapan_laporan_diperbaiki')->default(false)->after('tanggapan_laporan_sudah_baik');
            $table->boolean('tanggapan_laporan_diteliti_ulang')->default(false)->after('tanggapan_laporan_diperbaiki');
            $table->boolean('tanggapan_realisasi_diteliti_ulang')->default(false)->after('tanggapan_laporan_diteliti_ulang');
            $table->boolean('tanggapan_capaian_diteliti_ulang')->default(false)->after('tanggapan_realisasi_diteliti_ulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn([
                'tanggapan_laporan_kurang_baik',
                'tanggapan_laporan_sudah_baik',
                'tanggapan_laporan_diperbaiki',
                'tanggapan_laporan_diteliti_ulang',
                'tanggapan_realisasi_diteliti_ulang',
                'tanggapan_capaian_diteliti_ulang'
            ]);
        });
    }
};
