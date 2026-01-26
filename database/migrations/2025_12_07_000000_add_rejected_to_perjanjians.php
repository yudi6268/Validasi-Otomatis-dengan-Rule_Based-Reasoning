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
            $table->boolean('rejected')->default(false)->after('pihak2_signature');
            $table->text('rejection_reason')->nullable()->after('rejected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->dropColumn(['rejected', 'rejection_reason']);
        });
    }
};
