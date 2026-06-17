<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('laporans')) {
            Schema::create('laporans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('perjanjian_id')->nullable();
                $table->string('periode')->nullable();
                $table->string('periode_akhir')->nullable();
                $table->string('triwulan')->nullable();
                $table->integer('triwulan_aktif')->default(1);
                $table->string('tahun')->nullable();
                $table->text('uraian_kegiatan')->nullable();
                $table->text('indikator')->nullable();
                $table->text('tabelA')->nullable();
                $table->text('tabelB')->nullable();
                $table->text('tabelC')->nullable();
                $table->string('location')->nullable();
                $table->date('agreement_date')->nullable();
                $table->string('pihak1_name')->nullable();
                $table->string('pihak1_jabatan')->nullable();
                $table->string('pihak1_signature')->nullable();
                $table->string('pihak1_nip')->nullable();
                $table->string('pihak2_name')->nullable();
                $table->string('pihak2_jabatan')->nullable();
                $table->string('pihak2_signature')->nullable();
                $table->string('pihak2_nip')->nullable();
                $table->text('sasaran')->nullable();
                $table->decimal('bobot', 5, 2)->nullable();
                $table->string('sumber_data')->nullable();
                $table->text('bab_pelaksanaan')->nullable();
                $table->text('bab_capaian')->nullable();
                $table->text('bab_kendala')->nullable();
                $table->text('bab_rencana')->nullable();
                $table->text('rencana_tindak_lanjut')->nullable();
                $table->boolean('tanggapan_pihak2')->default(false);
                $table->text('catatan_pihak2')->nullable();
                $table->boolean('rejected')->default(false);
                $table->text('rejection_reason')->nullable();
                $table->boolean('tanggapan_laporan_kurang_baik')->default(false);
                $table->boolean('tanggapan_laporan_sudah_baik')->default(false);
                $table->boolean('tanggapan_laporan_diperbaiki')->default(false);
                $table->boolean('tanggapan_laporan_diteliti_ulang')->default(false);
                $table->boolean('tanggapan_realisasi_diteliti_ulang')->default(false);
                $table->boolean('tanggapan_capaian_diteliti_ulang')->default(false);
                $table->text('tanggapan_pimpinan')->nullable();
                $table->text('kesimpulan')->nullable();
                $table->text('realisasi_tb1')->nullable();
                $table->text('realisasi_tb2')->nullable();
                $table->text('realisasi_tb3')->nullable();
                $table->text('realisasi_tb4')->nullable();
                $table->json('validation_results')->nullable();
                $table->timestamp('validation_timestamp')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
