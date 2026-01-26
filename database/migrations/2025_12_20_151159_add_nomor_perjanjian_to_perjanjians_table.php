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
            if (!Schema::hasColumn('perjanjians', 'nomor_perjanjian')) {
                $table->string('nomor_perjanjian')->nullable()->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            if (Schema::hasColumn('perjanjians', 'nomor_perjanjian')) {
                $table->dropColumn('nomor_perjanjian');
            }
        });
    }
};
