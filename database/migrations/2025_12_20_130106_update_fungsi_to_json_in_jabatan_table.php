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
            // PostgreSQL: gunakan USING untuk konversi
            \DB::statement('ALTER TABLE jabatan ALTER COLUMN fungsi TYPE json USING fungsi::json, ALTER COLUMN fungsi DROP NOT NULL, ALTER COLUMN fungsi DROP DEFAULT, ALTER COLUMN fungsi DROP IDENTITY IF EXISTS');
        } else {
            Schema::table('jabatan', function (Blueprint $table) {
                $table->json('fungsi')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatan', function (Blueprint $table) {
            // Revert back to text
            $table->text('fungsi')->nullable()->change();
        });
    }
};
