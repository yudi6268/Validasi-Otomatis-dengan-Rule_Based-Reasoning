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
            "penyusunan program pelayanan medis, keperawatan, dan penunjang;",
            "perumusan kebijakan di bidang pelayanan medis, keperawatan, dan penunjang;",
            "pengoordinasian, pemantauan, pengendalian dan evaluasi pelaksanaan pelayanan medis, keperawatan, dan penunjang;",
            "pemberian arahan pelaksanaan dan pengembangan pelayanan medis, keperawatan dan penunjang;",
            "pelaksanaan kendali mutu, kendali biaya, dan keselamatan pasien di bidang pelayanan medis, keperawatan, penunjang medis, dan penunjang nonmedis;",
            "penyusunan laporan pertanggungjawaban atas pelaksanaan tugas dan fungsinya; dan",
            "pelaksanaan fungsi lain yang diberikan oleh Direktur."
        ];

        DB::table('perjanjians')->where('id', 21)->update([
            'pihak1_pangkat' => 'Pembina',
            'tugas_pelaksana' => 'melaksanakan pengelolaan pelayanan medis, pelayanan keperawatan, pelayanan penunjang medis dan nonmedis serta kendali mutu, kendali biaya dan keselamatan pasien.',
            'fungsi_pelaksana' => json_encode($fungsiArray),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('perjanjians')->where('id', 21)->update([
            'pihak1_pangkat' => null,
            'tugas_pelaksana' => null,
            'fungsi_pelaksana' => null,
        ]);
    }
};
