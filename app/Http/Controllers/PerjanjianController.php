<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjanjian;

class PerjanjianController extends Controller
{
    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $data = Perjanjian::orderBy('id', 'desc')->get();
        return view('perjanjian.index', compact('data'));
    }


    // ==============================
    // CREATE
    // ==============================
    public function create()
    {
        return view('perjanjian.create');
    }


    // ==============================
    // STORE
    // ==============================
    public function store(Request $request)
    {
        // ===== VALIDASI =====
        $request->validate([
            'pihak2_name'   => 'required|string',
            'pihak2_jabatan' => 'required|string',
            'jabatan'       => 'required|string',
            'tugas'         => 'required|string',
            'fungsi'        => 'required|string',
        ]);

        // ===== CEK TANDA TANGAN PIHAK PERTAMA =====
        if (!auth()->user()->tanda_tangan) {
            return back()->with('error', 'Gagal menyimpan! TTD anda kosong.');
        }

        // ==========================
        // SIMPAN DATA PERJANJIAN
        // ==========================
        $save = Perjanjian::create([
            // PIHAK PERTAMA
            'pihak1_name'       => auth()->user()->nama,
            'pihak1_jabatan'    => auth()->user()->jabatan,
            'pihak1_ttd'        => auth()->user()->tanda_tangan,

            // PIHAK KEDUA
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,

            // LAINNYA
            'jabatan'           => $request->jabatan,
            'tugas'             => $request->tugas,
            'fungsi'            => $request->fungsi,

            // TABEL A
            'tabelA' => json_encode([
                'sasaran'  => $request->a_sasaran,
                'indikator'=> $request->a_indikator,
                'target'   => $request->a_target,
                'formula'  => $request->a_formula,
                'sumber'   => $request->a_sumber,
            ]),

            // TABEL B
            'tabelB' => json_encode([
                'sasaran'  => $request->b_sasaran,
                'indikator'=> $request->b_indikator,
                'satuan'   => $request->b_satuan,
                'target'   => $request->b_target,
            ]),

            // TABEL C
            'tabelC' => json_encode([
                'sasaran'  => $request->c_sasaran,
                'indikator'=> $request->c_indikator,
                'target'   => $request->c_target,
                'tw1'      => $request->c_tw1,
                'tw2'      => $request->c_tw2,
                'tw3'      => $request->c_tw3,
                'tw4'      => $request->c_tw4,
            ]),

            // TABEL D
            'tabelD' => json_encode([
                'program'  => $request->d_program,
                'anggaran' => $request->d_anggaran,
                'keterangan'=> $request->d_keterangan,
            ]),
        ]);

        return redirect()->route('perjanjian.index')->with('success', 'Data perjanjian berhasil disimpan!');
    }


    // ==============================
    // PRINT
    // ==============================
    public function print($id)
    {
        $data = Perjanjian::findOrFail($id);

        return view('perjanjian.print', compact('data'));
    }
}