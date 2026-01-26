<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgramController extends Controller
{
    /**
     * API endpoint for live search programs (return JSON)
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = DB::table('programs');
        
        if (!empty($search)) {
            $query->where('nama_program', 'ilike', '%' . $search . '%');
        }
        
        $programs = $query->orderBy('nama_program')->limit(30)->get();
        
        return response()->json($programs);
    }

    /**
     * Display programs with hierarchical structure
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $status = $request->get('status', 'all'); // all|active|inactive
            
            // Fetch programs from database - SORT BY KODE
            $query = DB::table('programs')
                ->select('id', 'kode_program', 'nama_program', 'is_active');
            
            if (!empty($search)) {
                $query->where('nama_program', 'ilike', '%' . $search . '%');
            }
            
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
            
            $programs = $query->orderBy('kode_program')->get()->toArray();
            
            // Convert to array and fetch nested data
            foreach ($programs as &$program) {
                $program = (array)$program;
                
                // Fetch kegiatan for this program - SORT BY KODE
                $kegiatans = DB::table('kegiatan')
                    ->select('id', 'kode_kegiatan', 'nama_kegiatan', 'program_id', 'is_active')
                    ->where('program_id', $program['id'])
                    ->orderBy('kode_kegiatan')
                    ->get()
                    ->toArray();
                
                // Convert kegiatan and fetch sub-kegiatan
                $program['kegiatan'] = array_map(function($kegiatan) {
                    $kegiatan = (array)$kegiatan;
                    
                    // Fetch sub-kegiatan - SORT BY KODE
                    $subs = DB::table('sub_kegiatan')
                        ->select('id', 'kode_sub_kegiatan', 'nama_sub_kegiatan', 'kegiatan_id', 'is_active')
                        ->where('kegiatan_id', $kegiatan['id'])
                        ->orderBy('kode_sub_kegiatan')
                        ->get()
                        ->toArray();
                    
                    $kegiatan['sub_kegiatan'] = array_map(function($sub) {
                        return (array)$sub;
                    }, $subs);
                    
                    return $kegiatan;
                }, $kegiatans);
            }

            return view('admin.program.index', compact('programs', 'search', 'status'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching programs', [
                'error' => $e->getMessage(),
            ]);
            return view('admin.program.index', [
                'programs' => [],
                'search' => $request->get('search', ''),
                'status' => $request->get('status', 'all'),
            ])->with('error', 'Terjadi kesalahan saat memuat data program. Silakan coba lagi.');
        }
    }

    /**
     * Show the form for creating a new program
     */
    public function createProgram()
    {
        return view('admin.program.create');
    }

    /**
     * Store a newly created program
     */
    public function storeProgram(Request $request)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:500',
            'kode_program' => 'required|string|max:50|unique:programs,kode_program',
        ]);

        DB::table('programs')->insert([
            'kode_program' => $validated['kode_program'],
            'nama_program' => $validated['nama_program'],
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Program berhasil ditambahkan!');
    }

    /**
     * Show the form for editing program
     */
    public function editProgram($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        if (!$program) {
            abort(404);
        }
        $program = (array)$program;
        return view('admin.program.edit', compact('program'));
    }

    /**
     * Update the specified program
     */
    public function updateProgram(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:500',
            'kode_program' => 'required|string|max:50|unique:programs,kode_program,' . $id,
        ]);

        DB::table('programs')->where('id', $id)->update([
            'kode_program' => $validated['kode_program'],
            'nama_program' => $validated['nama_program'],
            'is_active' => $request->has('is_active'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Program berhasil diupdate!');
    }

    /**
     * Remove the specified program
     */
    public function destroyProgram($id)
    {
        // Delete related kegiatan and sub_kegiatan first
        $kegiatans = DB::table('kegiatan')->where('program_id', $id)->pluck('id');
        if ($kegiatans->count() > 0) {
            DB::table('sub_kegiatan')->whereIn('kegiatan_id', $kegiatans)->delete();
            DB::table('kegiatan')->where('program_id', $id)->delete();
        }
        
        DB::table('programs')->where('id', $id)->delete();

        return redirect()->route('admin.program.index')
            ->with('success', 'Program berhasil dihapus!');
    }

    /**
     * Toggle active status for a Program
     */
    public function toggleProgramActive($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        if (!$program) {
            return redirect()->back()->with('error', 'Program tidak ditemukan.');
        }
        
        DB::table('programs')->where('id', $id)->update([
            'is_active' => !$program->is_active,
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Status program diperbarui.');
    }

    // ===== KEGIATAN METHODS =====

    /**
     * Show the form for creating a new kegiatan
     */
    public function createKegiatan($programId)
    {
        $program = DB::table('programs')->where('id', $programId)->first();
        if (!$program) {
            abort(404);
        }
        $program = (array)$program;
        return view('admin.program.create-kegiatan', compact('program'));
    }

    /**
     * Store a newly created kegiatan
     */
    public function storeKegiatan(Request $request, $programId)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:500',
            'kode_kegiatan' => 'required|string|max:50',
        ]);

        DB::table('kegiatan')->insert([
            'kode_kegiatan' => $validated['kode_kegiatan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'program_id' => $programId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing kegiatan
     */
    public function editKegiatan($id)
    {
        $kegiatan = DB::table('kegiatan')->where('id', $id)->first();
        if (!$kegiatan) {
            abort(404);
        }
        $kegiatan = (array)$kegiatan;
        
        // Get program for context
        $program = DB::table('programs')->where('id', $kegiatan['program_id'])->first();
        $kegiatan['program'] = $program ? (array)$program : null;
        
        return view('admin.program.edit-kegiatan', compact('kegiatan'));
    }

    /**
     * Update the specified kegiatan
     */
    public function updateKegiatan(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:500',
            'kode_kegiatan' => 'required|string|max:50',
        ]);

        DB::table('kegiatan')->where('id', $id)->update([
            'kode_kegiatan' => $validated['kode_kegiatan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'is_active' => $request->has('is_active'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Kegiatan berhasil diupdate!');
    }

    /**
     * Remove the specified kegiatan
     */
    public function destroyKegiatan($id)
    {
        // Delete related sub_kegiatan first
        DB::table('sub_kegiatan')->where('kegiatan_id', $id)->delete();
        DB::table('kegiatan')->where('id', $id)->delete();

        return redirect()->route('admin.program.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }

    /**
     * Toggle active status for a Kegiatan
     */
    public function toggleKegiatanActive($id)
    {
        $kegiatan = DB::table('kegiatan')->where('id', $id)->first();
        if (!$kegiatan) {
            return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
        }
        
        DB::table('kegiatan')->where('id', $id)->update([
            'is_active' => !$kegiatan->is_active,
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Status kegiatan diperbarui.');
    }

    // ===== SUB KEGIATAN METHODS =====

    /**
     * Show the form for creating a new sub kegiatan
     */
    public function createSubKegiatan($kegiatanId)
    {
        try {
            // Set short timeout for this query
            $kegiatan = DB::table('kegiatan')
                ->select('id', 'nama_kegiatan', 'kode_kegiatan', 'program_id', 'is_active')
                ->where('id', $kegiatanId)
                ->first();
            
            if (!$kegiatan) {
                abort(404, 'Kegiatan tidak ditemukan');
            }
            $kegiatan = (array)$kegiatan;
            
            // Get program for context
            $program = DB::table('programs')
                ->select('id', 'nama_program', 'kode_program')
                ->where('id', $kegiatan['program_id'])
                ->first();
            
            $kegiatan['program'] = $program ? (array)$program : null;
            
            return view('admin.program.create-sub-kegiatan', compact('kegiatan'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating sub-kegiatan form', [
                'error' => $e->getMessage(),
                'kegiatan_id' => $kegiatanId,
            ]);
            abort(500, 'Terjadi kesalahan saat membuka form. Silakan coba lagi.');
        }
    }

    /**
     * Store a newly created sub kegiatan
     */
    public function storeSubKegiatan(Request $request, $kegiatanId)
    {
        try {
            $validated = $request->validate([
                'nama_sub_kegiatan' => 'required|string|max:500',
                'kode_sub_kegiatan' => 'required|string|max:50',
            ]);

            // Verify kegiatan exists
            $kegiatan = DB::table('kegiatan')->where('id', $kegiatanId)->first();
            if (!$kegiatan) {
                return back()->withErrors(['error' => 'Kegiatan tidak ditemukan']);
            }

            // Insert sub-kegiatan
            $insertId = DB::table('sub_kegiatan')->insertGetId([
                'kode_sub_kegiatan' => trim($validated['kode_sub_kegiatan']),
                'nama_sub_kegiatan' => trim($validated['nama_sub_kegiatan']),
                'kegiatan_id' => $kegiatanId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Verify insertion succeeded
            if (!$insertId) {
                return back()->withErrors(['error' => 'Gagal menyimpan data ke database'])->withInput();
            }

            // Log success
            \Illuminate\Support\Facades\Log::info('Sub-Kegiatan created', [
                'id' => $insertId,
                'kode' => $validated['kode_sub_kegiatan'],
                'nama' => $validated['nama_sub_kegiatan'],
                'kegiatan_id' => $kegiatanId,
            ]);

            return redirect()->route('admin.program.index')
                ->with('success', 'Sub Kegiatan berhasil ditambahkan!')
                ->with('expandKegiatan', $kegiatanId);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storing sub-kegiatan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing sub kegiatan
     */
    public function editSubKegiatan($id)
    {
        try {
            $subKegiatan = DB::table('sub_kegiatan')
                ->select('id', 'kode_sub_kegiatan', 'nama_sub_kegiatan', 'kegiatan_id', 'is_active')
                ->where('id', $id)
                ->first();
            
            if (!$subKegiatan) {
                abort(404, 'Sub-kegiatan tidak ditemukan');
            }
            $subKegiatan = (array)$subKegiatan;
            
            // Get kegiatan for context
            $kegiatan = DB::table('kegiatan')
                ->select('id', 'kode_kegiatan', 'nama_kegiatan', 'program_id')
                ->where('id', $subKegiatan['kegiatan_id'])
                ->first();
            
            if ($kegiatan) {
                $kegiatan = (array)$kegiatan;
                // Get program
                $program = DB::table('programs')
                    ->select('id', 'kode_program', 'nama_program')
                    ->where('id', $kegiatan['program_id'])
                    ->first();
                
                $kegiatan['program'] = $program ? (array)$program : null;
                $subKegiatan['kegiatan'] = $kegiatan;
            }
            
            return view('admin.program.edit-sub-kegiatan', compact('subKegiatan'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error editing sub-kegiatan', [
                'error' => $e->getMessage(),
                'id' => $id,
            ]);
            abort(500, 'Terjadi kesalahan saat membuka form. Silakan coba lagi.');
        }
    }

    /**
     * Update the specified sub kegiatan
     */
    public function updateSubKegiatan(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sub_kegiatan' => 'required|string|max:500',
            'kode_sub_kegiatan' => 'required|string|max:50',
        ]);

        DB::table('sub_kegiatan')->where('id', $id)->update([
            'kode_sub_kegiatan' => $validated['kode_sub_kegiatan'],
            'nama_sub_kegiatan' => $validated['nama_sub_kegiatan'],
            'is_active' => $request->has('is_active'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Sub Kegiatan berhasil diupdate!');
    }

    /**
     * Remove the specified sub kegiatan
     */
    public function destroySubKegiatan($id)
    {
        DB::table('sub_kegiatan')->where('id', $id)->delete();

        return redirect()->route('admin.program.index')
            ->with('success', 'Sub Kegiatan berhasil dihapus!');
    }

    /**
     * Toggle active status for a Sub Kegiatan
     */
    public function toggleSubKegiatanActive($id)
    {
        $sub = DB::table('sub_kegiatan')->where('id', $id)->first();
        if (!$sub) {
            return redirect()->back()->with('error', 'Sub-kegiatan tidak ditemukan.');
        }
        
        DB::table('sub_kegiatan')->where('id', $id)->update([
            'is_active' => !$sub->is_active,
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Status sub-kegiatan diperbarui.');
    }

    /**
     * Get kegiatan by program ID (API endpoint)
     */
    public function getKegiatanByProgram($programId)
    {
        $kegiatans = DB::table('kegiatan')
            ->where('program_id', $programId)
            ->where('is_active', true)
            ->orderBy('nama_kegiatan')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kegiatans
        ]);
    }
}

