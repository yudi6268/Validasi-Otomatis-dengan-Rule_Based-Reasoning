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
        $fungsiArray = [
            "a. penyusunan program pelayanan medis, keperawatan, dan penunjang;",
            "b. perumusan kebijakan di bidang pelayanan medis, keperawatan, dan penunjang;",
            "c. pengoordinasian, pemantauan, pengendalian dan evaluasi pelaksanaan pelayanan medis, keperawatan, dan penunjang;",
            "d. pemberian arahan pelaksanaan dan pengembangan pelayanan medis, keperawatan dan penunjang;",
            "e. pelaksanaan kendali mutu, kendali biaya, dan keselamatan pasien di bidang pelayanan medis, keperawatan, penunjang medis, dan penunjang nonmedis;",
            "f. penyusunan laporan pertanggungjawaban atas pelaksanaan tugas dan fungsinya; dan",
            "g. pelaksanaan fungsi lain yang diberikan oleh Direktur."
        ];

        DB::table('perjanjians')->where('id', 21)->update([
            'fungsi_pelaksana' => json_encode($fungsiArray),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous format without numbering if needed
    }
};
