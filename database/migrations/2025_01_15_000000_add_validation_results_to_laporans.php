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
            if (!Schema::hasColumn('laporans', 'validation_results')) {
                $table->json('validation_results')->nullable()->after('kesimpulan');
            }
            if (!Schema::hasColumn('laporans', 'validation_timestamp')) {
                $table->timestamp('validation_timestamp')->nullable()->after('validation_results');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (Schema::hasColumn('laporans', 'validation_timestamp')) {
                $table->dropColumn('validation_timestamp');
            }
            if (Schema::hasColumn('laporans', 'validation_results')) {
                $table->dropColumn('validation_results');
            }
        });
    }
};
