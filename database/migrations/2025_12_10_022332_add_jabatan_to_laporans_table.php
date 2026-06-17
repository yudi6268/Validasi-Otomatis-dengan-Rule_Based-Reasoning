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
        if (!Schema::hasColumn('laporans', 'pihak1_jabatan') || !Schema::hasColumn('laporans', 'pihak2_jabatan')) {
            Schema::table('laporans', function (Blueprint $table) {
                if (!Schema::hasColumn('laporans', 'pihak1_jabatan')) {
                    $table->string('pihak1_jabatan')->nullable()->after('pihak1_name');
                }

                if (!Schema::hasColumn('laporans', 'pihak2_jabatan')) {
                    $table->string('pihak2_jabatan')->nullable()->after('pihak2_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropColumns = [];
        if (Schema::hasColumn('laporans', 'pihak1_jabatan')) {
            $dropColumns[] = 'pihak1_jabatan';
        }

        if (Schema::hasColumn('laporans', 'pihak2_jabatan')) {
            $dropColumns[] = 'pihak2_jabatan';
        }

        if (!empty($dropColumns)) {
            Schema::table('laporans', function (Blueprint $table) use ($dropColumns) {
                $table->dropColumn($dropColumns);
            });
        }
    }
};
