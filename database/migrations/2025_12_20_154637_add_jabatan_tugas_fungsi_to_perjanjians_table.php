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
        Schema::table('perjanjians', function (Blueprint $table) {
            if (!Schema::hasColumn('perjanjians', 'jabatan_pelaksana')) {
                $table->string('jabatan_pelaksana')->nullable()->after('pihak2_nip');
            }
            if (!Schema::hasColumn('perjanjians', 'tugas_pelaksana')) {
                $table->text('tugas_pelaksana')->nullable()->after('jabatan_pelaksana');
            }
            if (!Schema::hasColumn('perjanjians', 'fungsi_pelaksana')) {
                $table->text('fungsi_pelaksana')->nullable()->after('tugas_pelaksana');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            if (Schema::hasColumn('perjanjians', 'jabatan_pelaksana')) {
                $table->dropColumn('jabatan_pelaksana');
            }
            if (Schema::hasColumn('perjanjians', 'tugas_pelaksana')) {
                $table->dropColumn('tugas_pelaksana');
            }
            if (Schema::hasColumn('perjanjians', 'fungsi_pelaksana')) {
                $table->dropColumn('fungsi_pelaksana');
            }
        });
    }
};
