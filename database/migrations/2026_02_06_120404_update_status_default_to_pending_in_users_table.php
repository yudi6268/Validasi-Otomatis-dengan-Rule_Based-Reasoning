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
        // Migration ini sudah dijalankan dan mengubah default value
        // CATATAN: Default 'pending' hanya berlaku untuk user BARU yang mendaftar
        // Existing users tetap memiliki status mereka (active/non-active)
        
        // Update migration sebelumnya sudah mengubah struktur
        // Tidak perlu action tambahan di sini karena sudah dihandle manual
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tidak diperlukan karena perubahan sudah permanen
    }
};
