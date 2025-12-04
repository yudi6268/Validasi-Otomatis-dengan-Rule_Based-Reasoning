<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add columns to `perjanjians` only if they don't already exist
        if (!Schema::hasColumn('perjanjians', 'sasaran')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->text('sasaran')->nullable()->after('deskripsi');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'bobot')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->decimal('bobot', 5, 2)->nullable()->after('sasaran');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'sumber_data')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('sumber_data')->nullable()->after('bobot');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'pihak1_name')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak1_name')->nullable()->after('sumber_data');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'pihak1_signature')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak1_signature')->nullable()->after('pihak1_name');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'pihak2_name')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak2_name')->nullable()->after('pihak1_signature');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'pihak2_signature')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak2_signature')->nullable()->after('pihak2_name');
            });
        }

        // Add columns to `laporans` only if they don't already exist
        if (!Schema::hasColumn('laporans', 'sasaran')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->text('sasaran')->nullable()->after('uraian_kegiatan');
            });
        }

        if (!Schema::hasColumn('laporans', 'bobot')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->decimal('bobot', 5, 2)->nullable()->after('sasaran');
            });
        }

        if (!Schema::hasColumn('laporans', 'sumber_data')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->string('sumber_data')->nullable()->after('bobot');
            });
        }

        if (!Schema::hasColumn('laporans', 'pihak1_name')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->string('pihak1_name')->nullable()->after('sumber_data');
            });
        }

        if (!Schema::hasColumn('laporans', 'pihak1_signature')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->string('pihak1_signature')->nullable()->after('pihak1_name');
            });
        }

        if (!Schema::hasColumn('laporans', 'pihak2_name')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->string('pihak2_name')->nullable()->after('pihak1_signature');
            });
        }

        if (!Schema::hasColumn('laporans', 'pihak2_signature')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->string('pihak2_signature')->nullable()->after('pihak2_name');
            });
        }
    }

    public function down()
    {
        $perColumns = ['sasaran','bobot','sumber_data','pihak1_name','pihak1_signature','pihak2_name','pihak2_signature'];
        $toDropPer = [];
        foreach ($perColumns as $col) {
            if (Schema::hasColumn('perjanjians', $col)) {
                $toDropPer[] = $col;
            }
        }
        if (!empty($toDropPer)) {
            Schema::table('perjanjians', function (Blueprint $table) use ($toDropPer) {
                $table->dropColumn($toDropPer);
            });
        }

        $lapColumns = ['sasaran','bobot','sumber_data','pihak1_name','pihak1_signature','pihak2_name','pihak2_signature'];
        $toDropLap = [];
        foreach ($lapColumns as $col) {
            if (Schema::hasColumn('laporans', $col)) {
                $toDropLap[] = $col;
            }
        }
        if (!empty($toDropLap)) {
            Schema::table('laporans', function (Blueprint $table) use ($toDropLap) {
                $table->dropColumn($toDropLap);
            });
        }
    }
};