<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('perjanjians')) {
            Schema::create('perjanjians', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('jabatan')->nullable();
                $table->text('deskripsi')->nullable();
                $table->text('indikator')->nullable();
                $table->string('tahun')->nullable();

                // Pihak 1 (dari profil user)
                $table->string('pihak1_name')->nullable();
                $table->string('pihak1_jabatan')->nullable();
                $table->longText('pihak1_ttd')->nullable();

                // Pihak 2 (input user)
                $table->string('pihak2_name')->nullable();
                $table->string('pihak2_jabatan')->nullable();

                // Jabatan, Fungsi, Tugas
                $table->string('jabatan_pelaksana')->nullable();
                $table->text('tugas_pelaksana')->nullable();
                $table->text('fungsi_pelaksana')->nullable();

                // Tabel A/B/C/D
                $table->json('tabelA')->nullable();
                $table->json('tabelB')->nullable();
                $table->json('tabelC')->nullable();
                $table->json('tabelD')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('perjanjians');
    }
};