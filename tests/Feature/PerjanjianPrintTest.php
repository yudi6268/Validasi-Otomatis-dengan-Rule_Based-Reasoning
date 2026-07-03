<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Perjanjian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerjanjianPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_print_perjanjian_with_array_tugas_pelaksana(): void
    {
        // 1. Create a user
        $user = User::create([
            'id_pegawai' => 'PEG123',
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'nip' => '1234567890',
            'jabatan' => 'Wakil Direktur Pelayanan',
            'pangkat' => 'Pembina / IVa',
            'divisi' => 'Pelayanan',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // 2. Create a Perjanjian record with array tugas_pelaksana
        $perjanjian = Perjanjian::create([
            'user_id' => $user->id,
            'tahun' => '2025',
            'pihak1_name' => 'Test User',
            'pihak1_jabatan' => 'Wakil Direktur Pelayanan',
            'pihak1_nip' => '1234567890',
            'pihak2_name' => 'Direktur Nama',
            'pihak2_jabatan' => 'Direktur',
            'pihak2_nip' => '0987654321',
            'tugas_pelaksana' => ['Melaksanakan tugas A', 'Melaksanakan tugas B'],
            'fungsi_pelaksana' => ['Fungsi A', 'Fungsi B'],
            'tabelA' => [
                'sasaran' => ['Sasaran 1'],
                'indikator' => ['Indikator 1'],
                'satuan' => ['%'],
                'target' => ['100'],
            ],
            'tabelC' => [
                'programs' => [
                    [
                        'name' => 'Program 1',
                        'amount' => '100000000',
                        'tw1' => '25000000',
                        'tw2' => '25000000',
                        'tw3' => '25000000',
                        'tw4' => '25000000',
                        'kegiatan' => [],
                    ]
                ]
            ],
        ]);

        // 3. Act as the user and make print page request
        $response = $this->actingAs($user)->get(route('perjanjian.print', ['id' => $perjanjian->id]));

        // 4. Assert response is successful and contains the tugas texts
        $response->assertStatus(200);
        $response->assertSee('Melaksanakan tugas A');
        $response->assertSee('Melaksanakan tugas B');
    }

    public function test_print_perjanjian_with_json_string_tugas_pelaksana(): void
    {
        // 1. Create a user
        $user = User::create([
            'id_pegawai' => 'PEG124',
            'nama' => 'Test User',
            'email' => 'test2@example.com',
            'nip' => '1234567891',
            'jabatan' => 'Wakil Direktur Pelayanan',
            'pangkat' => 'Pembina / IVa',
            'divisi' => 'Pelayanan',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // 2. Create a Perjanjian record with string/JSON tugas_pelaksana
        $perjanjian = Perjanjian::create([
            'user_id' => $user->id,
            'tahun' => '2025',
            'pihak1_name' => 'Test User',
            'pihak1_jabatan' => 'Wakil Direktur Pelayanan',
            'pihak1_nip' => '1234567890',
            'pihak2_name' => 'Direktur Nama',
            'pihak2_jabatan' => 'Direktur',
            'pihak2_nip' => '0987654321',
            'tugas_pelaksana' => 'Melaksanakan tugas tunggal',
            'fungsi_pelaksana' => 'Fungsi tunggal',
            'tabelA' => [
                'sasaran' => ['Sasaran 1'],
                'indikator' => ['Indikator 1'],
                'satuan' => ['%'],
                'target' => ['100'],
            ],
            'tabelC' => [
                'programs' => []
            ],
        ]);

        // 3. Act as the user and make print page request
        $response = $this->actingAs($user)->get(route('perjanjian.print', ['id' => $perjanjian->id]));

        // 4. Assert response is successful and contains the tugas texts
        $response->assertStatus(200);
        $response->assertSee('Melaksanakan tugas tunggal');
    }

    public function test_render_print_view(): void
    {
        $perjanjian = $this->createMockPerjanjian();
        
        $view = $this->view('perjanjian.print', [
            'perjanjian' => $perjanjian,
            'logoSrc' => '',
            'logoPemda' => '',
            'logoRsud' => '',
            'tanggal' => '18 Juni 2026',
            'tahun' => '2025',
            'tugas_fungsi' => 'Tugas A\nFungsi B',
            'tabelA' => $perjanjian->tabelA,
            'tabelB' => $perjanjian->tabelB,
            'tabelC' => $perjanjian->tabelC,
            'user' => $perjanjian->user,
            'isDirektur' => false,
            'status' => 'menunggu',
            'for_pdf' => false,
        ]);

        $view->assertSee('Melaksanakan tugas A');
    }

    public function test_render_pdf_snappy_view(): void
    {
        $perjanjian = $this->createMockPerjanjian();
        
        $view = $this->view('perjanjian.pdf-snappy', [
            'perjanjian' => $perjanjian,
            'data' => $perjanjian,
            'logoSrc' => '',
            'logoPemda' => '',
            'logoRsud' => '',
            'tanggal' => '18 Juni 2026',
            'tahun' => '2025',
            'tugas_fungsi' => 'Tugas A\nFungsi B',
            'tabelA' => $perjanjian->tabelA,
            'tabelB' => $perjanjian->tabelB,
            'tabelC' => $perjanjian->tabelC,
            'user' => $perjanjian->user,
            'isDirektur' => false,
            'status' => 'menunggu',
            'for_pdf' => true,
        ]);

        $view->assertSee('Melaksanakan tugas A');
    }

    public function test_render_pdf_legacy_view(): void
    {
        $perjanjian = $this->createMockPerjanjian();
        
        $view = $this->view('perjanjian.pdf', [
            'perjanjian' => $perjanjian,
            'data' => $perjanjian,
            'logoSrc' => '',
            'logoPemda' => '',
            'logoRsud' => '',
            'tanggal' => '18 Juni 2026',
            'tahun' => '2025',
            'tugas_fungsi' => 'Tugas A\nFungsi B',
            'tabelA' => $perjanjian->tabelA,
            'tabelB' => $perjanjian->tabelB,
            'tabelC' => $perjanjian->tabelC,
            'user' => $perjanjian->user,
            'isDirektur' => false,
            'status' => 'menunggu',
            'for_pdf' => true,
        ]);

        $view->assertSee('Melaksanakan tugas A');
    }

    private function createMockPerjanjian(): Perjanjian
    {
        $user = User::create([
            'id_pegawai' => 'PEG999',
            'nama' => 'Test User',
            'email' => 'test_mock@example.com',
            'nip' => '1234567899',
            'jabatan' => 'Wakil Direktur Pelayanan',
            'pangkat' => 'Pembina / IVa',
            'divisi' => 'Pelayanan',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        return Perjanjian::create([
            'user_id' => $user->id,
            'tahun' => '2025',
            'pihak1_name' => 'Test User',
            'pihak1_jabatan' => 'Wakil Direktur Pelayanan',
            'pihak1_nip' => '1234567890',
            'pihak2_name' => 'Direktur Nama',
            'pihak2_jabatan' => 'Direktur',
            'pihak2_nip' => '0987654321',
            'tugas_pelaksana' => ['Melaksanakan tugas A', 'Melaksanakan tugas B'],
            'fungsi_pelaksana' => ['Fungsi A', 'Fungsi B'],
            'tabelA' => [
                'sasaran' => ['Sasaran 1'],
                'indikator' => ['Indikator 1'],
                'satuan' => ['%'],
                'target' => ['100'],
            ],
            'tabelB' => [
                'sasaran' => ['Sasaran 1'],
                'indikator' => ['Indikator 1'],
                'tw1' => ['100'],
                'tw2' => ['100'],
                'tw3' => ['100'],
                'tw4' => ['100'],
            ],
            'tabelC' => [
                'programs' => [
                    [
                        'name' => 'Program 1',
                        'amount' => '100000000',
                        'tw1' => '25000000',
                        'tw2' => '25000000',
                        'tw3' => '25000000',
                        'tw4' => '25000000',
                        'kegiatan' => [],
                    ]
                ]
            ],
        ]);
    }
}
