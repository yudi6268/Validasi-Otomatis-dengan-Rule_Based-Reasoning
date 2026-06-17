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

        $query = Jabatan::query()->orderBy('nama_jabatan');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_jabatan', 'ilike', "%{$search}%")
                    ->orWhereRaw("tugas::text ILIKE ?", ["%{$search}%"])
                    ->orWhereRaw("fungsi::text ILIKE ?", ["%{$search}%"])
                    ->orWhereRaw("membawahi::text ILIKE ?", ["%{$search}%"]);
            });
        }

        $jabatan = $query->get();

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

            if (!$this->isSameDatabase()) {
                Log::info('Attempting Supabase insert with data:', $supabaseData);
                $supabaseResult = $this->supabase->insert('jabatan', [$supabaseData]);
                Log::info('Supabase insert result:', $supabaseResult);

                if (!$supabaseResult['success']) {
                    Log::error('Supabase insert failed for jabatan ' . $jabatan->id . ': ' . ($supabaseResult['error'] ?? 'Unknown error'));
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal sinkronisasi Supabase: ' . ($supabaseResult['error'] ?? 'Unknown error'));
                }
            } else {
                Log::info('Skipping Supabase API insert because database connection is directly pointing to Supabase');
            }

            DB::commit();
            Log::info('Jabatan ' . $jabatan->id . ' successfully saved and synced to Supabase');
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

            $supabaseData = [
                'nama_jabatan' => $jabatan->nama_jabatan,
                'tugas' => $jabatan->tugas,
                'fungsi' => $jabatan->fungsi,
                'membawahi' => $jabatan->membawahi,
                'is_active' => $jabatan->is_active,
                'updated_at' => $jabatan->updated_at?->toDateTimeString(),
            ];

            if (!$this->isSameDatabase()) {
                $supabaseResult = $this->supabase->update('jabatan', ['id' => 'eq.' . $id], $supabaseData);
                if (!$supabaseResult['success']) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal sinkronisasi Supabase: ' . ($supabaseResult['error'] ?? 'Unknown error'));
                }
            } else {
                Log::info('Skipping Supabase API update because database connection is directly pointing to Supabase');
            }

            DB::commit();
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

            if (!$this->isSameDatabase()) {
                Log::info('Attempting Supabase delete for jabatan ' . $id);
                $supabaseResult = $this->supabase->delete('jabatan', ['id' => 'eq.' . $id]);
                Log::info('Supabase delete result:', $supabaseResult);

                if (!$supabaseResult['success']) {
                    Log::error('Supabase delete failed for jabatan ' . $id . ': ' . ($supabaseResult['error'] ?? 'Unknown error'));
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Gagal sinkronisasi Supabase saat menghapus: ' . ($supabaseResult['error'] ?? 'Unknown error'));
                }
            } else {
                Log::info('Skipping Supabase API delete because database connection is directly pointing to Supabase');
            }

            DB::commit();
            Log::info('Jabatan ' . $id . ' successfully deleted from both local DB and Supabase');
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
