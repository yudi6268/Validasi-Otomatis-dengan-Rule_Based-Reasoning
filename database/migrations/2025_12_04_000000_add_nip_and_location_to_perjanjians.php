<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('perjanjians', 'pihak1_nip')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak1_nip')->nullable()->after('pihak1_jabatan');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'pihak2_nip')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak2_nip')->nullable()->after('pihak2_jabatan');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'location')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('location')->nullable()->default('Pasuruan')->after('pihak2_nip');
            });
        }

        if (!Schema::hasColumn('perjanjians', 'agreement_date')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->date('agreement_date')->nullable()->after('location');
            });
        }
    }

    public function down()
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            if (Schema::hasColumn('perjanjians', 'pihak1_nip')) {
                $table->dropColumn('pihak1_nip');
            }
            if (Schema::hasColumn('perjanjians', 'pihak2_nip')) {
                $table->dropColumn('pihak2_nip');
            }
            if (Schema::hasColumn('perjanjians', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('perjanjians', 'agreement_date')) {
                $table->dropColumn('agreement_date');
            }
        });
    }
};
