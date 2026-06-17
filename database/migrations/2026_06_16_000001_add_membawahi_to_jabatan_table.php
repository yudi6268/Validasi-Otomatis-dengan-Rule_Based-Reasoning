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
        if (env('DB_CONNECTION') === 'pgsql') {
            // PostgreSQL: add JSON column if not exists
            \DB::statement("ALTER TABLE jabatan ADD COLUMN IF NOT EXISTS membawahi json;");
        } else {
            Schema::table('jabatan', function (Blueprint $table) {
                if (!Schema::hasColumn('jabatan', 'membawahi')) {
                    $table->json('membawahi')->nullable()->after('fungsi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (env('DB_CONNECTION') === 'pgsql') {
            \DB::statement('ALTER TABLE jabatan DROP COLUMN IF EXISTS membawahi;');
        } else {
            Schema::table('jabatan', function (Blueprint $table) {
                if (Schema::hasColumn('jabatan', 'membawahi')) {
                    $table->dropColumn('membawahi');
                }
            });
        }
    }
};
