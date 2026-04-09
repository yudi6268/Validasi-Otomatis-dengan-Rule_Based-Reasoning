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
            if (!Schema::hasColumn('perjanjians', 'pdf_url')) {
                $table->string('pdf_url')->nullable();
            }
            if (!Schema::hasColumn('perjanjians', 'pdf_path')) {
                $table->string('pdf_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->dropColumn(['pdf_url', 'pdf_path']);
        });
    }
};
