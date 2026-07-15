<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JabatanController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    public function diagnostics()
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        $success = true;
        $count = 0;
        $error = null;

        try {
            $count = Jabatan::count();
        } catch (\Throwable $throwable) {
            $success = false;
            $error = $throwable->getMessage();
        }

        return response()->json([
            'database_connection' => config('database.default'),
            'jabatan_table_accessible' => $success,
            'jabatan_count' => $count,
            'error' => $error,
        ]);
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $search = request('search');

        $allJabatan = \Illuminate\Support\Facades\Cache::remember('jabatan_all', 3600, function() {
            return Jabatan::orderBy('nama_jabatan')->get();
        });

        if ($search) {
            $searchLower = strtolower($search);
            $jabatan = $allJabatan->filter(function($j) use ($searchLower) {
                $fungsiStr = is_array($j->fungsi) ? implode(' ', $j->fungsi) : ($j->fungsi ?? '');
                $membStr = is_array($j->membawahi) ? implode(' ', $j->membawahi) : ($j->membawahi ?? '');
                return str_contains(strtolower($j->nama_jabatan ?? ''), $searchLower)
                    || str_contains(strtolower($j->tugas ?? ''), $searchLower)
                    || str_contains(strtolower($fungsiStr), $searchLower)
                    || str_contains(strtolower($membStr), $searchLower);
            });
        } else {
            $jabatan = $allJabatan;
        }

        if (request()->ajax() || request()->get('ajax') == 1) {
            return view('admin.jabatan.partials.table', compact('jabatan'))->render();
        }

        return view('admin.jabatan.index', compact('jabatan'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        return view('admin.jabatan.create');
    }

    /* ================= DEBUG SUPABASE INSERT ================= */
    public function debugSupabaseInsert()
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        $testData = [
            [
                'id' => 999,
                'nama_jabatan' => 'TEST_JABATAN_' . time(),
                'tugas' => 'Test insert dari localhost',
                'fungsi' => ['fungsi1', 'fungsi2'],
                'membawahi' => ['unit1'],
                'is_active' => true,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ]
        ];

        Log::info('Testing Supabase insert with data:', $testData);
        $result = $this->supabase->insert('jabatan', $testData);
        Log::info('Supabase insert result:', $result);

        return response()->json([
            'test_data' => $testData,
            'supabase_result' => $result,
            'config' => [
                'supabase_url' => config('services.supabase.url'),
                'has_anon_key' => !empty(config('services.supabase.anon_key')),
                'has_service_key' => !empty(config('services.supabase.service_role_key')),
            ]
        ]);
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string',
            'tugas' => 'nullable|string',
            'fungsi' => 'nullable|array',
            'fungsi.*' => 'nullable|string',
            'membawahi' => 'nullable|array',
            'membawahi.*' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['fungsi'] = isset($data['fungsi'])
            ? array_values(array_filter($data['fungsi'], fn ($item) => $item !== null && trim($item) !== ''))
            : [];
        $data['membawahi'] = isset($data['membawahi'])
            ? array_values(array_filter($data['membawahi'], fn ($item) => $item !== null && trim($item) !== ''))
            : [];
        $data['is_active'] = $request->has('is_active');

        DB::beginTransaction();
        try {
            $jabatan = Jabatan::create($data);
            Log::info('Jabatan created locally:', $jabatan->toArray());

            DB::commit();
            \Illuminate\Support\Facades\Cache::forget('jabatan_all');
            
            if (!$this->isSameDatabase()) {
                $supabaseData = [
                    'id' => $jabatan->id,
                    'nama_jabatan' => $jabatan->nama_jabatan,
                    'tugas' => $jabatan->tugas,
                    'fungsi' => $jabatan->fungsi,
                    'membawahi' => $jabatan->membawahi,
                    'is_active' => $jabatan->is_active,
                    'created_at' => $jabatan->created_at?->toDateTimeString(),
                    'updated_at' => $jabatan->updated_at?->toDateTimeString(),
                ];

                app()->terminating(function() use ($supabaseData, $jabatan) {
                    Log::info('Attempting deferred Supabase insert with data:', $supabaseData);
                    $supabaseResult = $this->supabase->insert('jabatan', [$supabaseData]);
                    if (!$supabaseResult['success']) {
                        Log::error('Deferred Supabase insert failed for jabatan ' . $jabatan->id . ': ' . ($supabaseResult['error'] ?? 'Unknown error'));
                    } else {
                        Log::info('Deferred Supabase insert successful for jabatan ' . $jabatan->id);
                    }
                });
            }

        } catch (\Throwable $throwable) {
            Log::error('Jabatan store error: ' . $throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan jabatan: ' . $throwable->getMessage());
        }

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan dan disinkronisasi ke Supabase');
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        return view('admin.jabatan.edit', compact('jabatan'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $data = $request->validate([
            'nama_jabatan' => 'required|string',
            'tugas' => 'nullable|string',
            'fungsi' => 'nullable|array',
            'fungsi.*' => 'nullable|string',
            'membawahi' => 'nullable|array',
            'membawahi.*' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['fungsi'] = isset($data['fungsi'])
            ? array_values(array_filter($data['fungsi'], fn ($item) => $item !== null && trim($item) !== ''))
            : [];
        $data['membawahi'] = isset($data['membawahi'])
            ? array_values(array_filter($data['membawahi'], fn ($item) => $item !== null && trim($item) !== ''))
            : [];
        $data['is_active'] = $request->has('is_active');

        DB::beginTransaction();
        try {
            $jabatan->update($data);

            DB::commit();
            \Illuminate\Support\Facades\Cache::forget('jabatan_all');

            if (!$this->isSameDatabase()) {
                $supabaseData = [
                    'nama_jabatan' => $jabatan->nama_jabatan,
                    'tugas' => $jabatan->tugas,
                    'fungsi' => $jabatan->fungsi,
                    'membawahi' => $jabatan->membawahi,
                    'is_active' => $jabatan->is_active,
                    'updated_at' => $jabatan->updated_at?->toDateTimeString(),
                ];

                app()->terminating(function() use ($id, $supabaseData) {
                    $supabaseResult = $this->supabase->update('jabatan', ['id' => 'eq.' . $id], $supabaseData);
                    if (!$supabaseResult['success']) {
                        Log::error('Deferred Supabase update failed for jabatan ' . $id . ': ' . ($supabaseResult['error'] ?? 'Unknown error'));
                    }
                });
            }

        } catch (\Throwable $throwable) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jabatan: ' . $throwable->getMessage());
        }

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui');
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        DB::beginTransaction();
        try {
            Log::info('Deleting jabatan ' . $id . ' from local database');
            $jabatan->delete();

            DB::commit();
            \Illuminate\Support\Facades\Cache::forget('jabatan_all');

            if (!$this->isSameDatabase()) {
                app()->terminating(function() use ($id) {
                    Log::info('Attempting deferred Supabase delete for jabatan ' . $id);
                    $supabaseResult = $this->supabase->delete('jabatan', ['id' => 'eq.' . $id]);
                    if (!$supabaseResult['success']) {
                        Log::error('Deferred Supabase delete failed for jabatan ' . $id . ': ' . ($supabaseResult['error'] ?? 'Unknown error'));
                    }
                });
            }
        } catch (\Throwable $throwable) {
            Log::error('Jabatan delete error: ' . $throwable->getMessage(), ['id' => $id]);
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus jabatan: ' . $throwable->getMessage());
        }

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus dari kedua sistem');
    }

    /**
     * Check if the default database is the same Supabase database.
     */
    private function isSameDatabase(): bool
    {
        $default = config('database.default');
        if ($default !== 'pgsql') {
            return false;
        }
        $host = config('database.connections.pgsql.host');
        $username = config('database.connections.pgsql.username');
        return (strpos($host, 'supabase') !== false || strpos($username, 'supabase') !== false);
    }
}
