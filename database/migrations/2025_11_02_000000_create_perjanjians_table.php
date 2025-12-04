<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perjanjians', function (Blueprint $table) {

            // Pihak 1 (dari profil user)
            $table->string('pihak1_name')->nullable()->after('tahun');
            $table->string('pihak1_jabatan')->nullable()->after('pihak1_name');
            $table->longText('pihak1_ttd')->nullable()->after('pihak1_jabatan');

            // Pihak 2 (input user)
            $table->string('pihak2_name')->nullable()->after('pihak1_ttd');
            $table->string('pihak2_jabatan')->nullable()->after('pihak2_name');

            
            // Tabel A/B/C/D
            $table->json('tabelA')->nullable()->after('jabatan');
            $table->json('tabelB')->nullable();
            $table->json('tabelC')->nullable();
            $table->json('tabelD')->nullable();
        });
    }

    public function down()
    {
        $columns = [
            'pihak1_name',
            'pihak1_jabatan',
            'pihak1_ttd',
            'pihak2_name',
            'pihak2_jabatan',
           
            'tabelA',
            'tabelB',
            'tabelC',
            'tabelD',
        ];

        $toDrop = [];
        foreach ($columns as $col) {
            if (Schema::hasColumn('perjanjians', $col)) {
                $toDrop[] = $col;
            }
        }

        if (!empty($toDrop)) {
            Schema::table('perjanjians', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }
};