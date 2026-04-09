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
            // Tambah kolom status (menggantikan rejected boolean)
            // Nilai: 'draft', 'menunggu', 'disetujui', 'ditolak'
            if (!Schema::hasColumn('perjanjians', 'status')) {
                $table->string('status')->default('menunggu')->after('pihak2_signature');
            }
            
            // Tambah kolom catatan_penolakan (menggantikan rejection_reason)
            if (!Schema::hasColumn('perjanjians', 'catatan_penolakan')) {
                $table->text('catatan_penolakan')->nullable()->after('status');
            }
        });
        
        // Migrasi data lama ke format baru
        DB::table('perjanjians')->where('rejected', true)->update(['status' => 'ditolak']);
        DB::table('perjanjians')->where('rejected', false)->update(['status' => 'menunggu']);
        
        // Copy rejection_reason ke catatan_penolakan
        DB::statement("UPDATE perjanjians SET catatan_penolakan = rejection_reason WHERE rejection_reason IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjanjians', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_penolakan']);
        });
    }
};
