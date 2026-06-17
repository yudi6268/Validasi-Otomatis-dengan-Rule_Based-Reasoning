<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$laporan = App\Models\Laporan::find(5);
$perjanjian = $laporan->perjanjian;
$user = $perjanjian->user;
echo "--- LAPORAN ---\n";
var_export(["bab_pelaksanaan" => $laporan->bab_pelaksanaan, "triwulan_aktif" => $laporan->triwulan_aktif, "tanggapan_pimpinan" => $laporan->tanggapan_pimpinan]);
echo "\n--- PERJANJIAN ---\n";
var_export(["fungsi_pelaksana" => $perjanjian->fungsi_pelaksana, "tugas_pelaksana" => $perjanjian->tugas_pelaksana, "jabatan_pelaksana" => $perjanjian->jabatan_pelaksana]);
echo "\n--- USER ---\n";
var_export(["jabatan" => $user->jabatan, "fungsi" => $user->fungsi, "tugas" => $user->tugas, "membawahi" => $user->membawahi]);
echo "\n--- JABATAN ---\n";
$jabatan = App\Models\Jabatan::where('nama_jabatan', $perjanjian->jabatan_pelaksana)->orWhere('nama_jabatan', $user->jabatan)->first();
var_export($jabatan ? $jabatan->toArray() : null);
echo "\n";
