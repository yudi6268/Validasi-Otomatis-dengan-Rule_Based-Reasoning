<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('perjanjians', 'pihak2_ttd_path')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->string('pihak2_ttd_path')->nullable()->after('pihak2_signature');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('perjanjians', 'pihak2_ttd_path')) {
            Schema::table('perjanjians', function (Blueprint $table) {
                $table->dropColumn('pihak2_ttd_path');
            });
        }
    }
};
