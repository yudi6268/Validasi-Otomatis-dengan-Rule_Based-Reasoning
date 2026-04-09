<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default values untuk tahun perjanjian
        DB::table('settings')->insert([
            [
                'key' => 'tahun_perjanjian_1',
                'value' => '2025',
                'description' => 'Tahun Perjanjian Pertama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'tahun_perjanjian_2',
                'value' => '2026',
                'description' => 'Tahun Perjanjian Kedua',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
