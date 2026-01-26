<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JabatanController extends Controller
{
    private function anon()
    {
        return [
            'apikey' => config('services.supabase.anon_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.anon_key'),
            'Accept' => 'application/json',
        ];
    }

    private function service()
    {
        return [
            'apikey' => config('services.supabase.service_role_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_role_key'),
            'Content-Type' => 'application/json',
        ];
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $user = auth()->user();
        $search = request('search');
        $limit = 30;
        $url = config('services.supabase.url') . "/rest/v1/jabatan?select=*&limit={$limit}";
        $headers = $user->isAdmin() ? $this->service() : $this->anon();
        $filters = [];
        if (!$user->isAdmin() && !empty($user->jabatan)) {
            $filters[] = 'nama_jabatan=eq.' . urlencode($user->jabatan);
        }
        if ($search) {
            // Supabase REST API: gunakan ilike untuk pencarian case-insensitive (hanya kolom string)
            $filters[] = 'or=(nama_jabatan.ilike.*' . urlencode($search) . '*,'
                . 'tugas.ilike.*' . urlencode($search) . '*)';
        }
        if (count($filters)) {
            $url .= '&' . implode('&', $filters);
        }
        $response = Http::withHeaders($headers)->get($url);
        $jabatan = $response->successful() ? $response->json() : [];
        // Debug jika gagal
        if (!$response->successful()) {
            $jabatan = [
                'status' => $response->status(),
                'body' => $response->body(),
            ];
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

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string',
            'tugas' => 'nullable|string',
            'fungsi' => 'nullable|array',
            'fungsi.*' => 'string',
            'is_active' => 'nullable|boolean',
        ]);

        Http::withHeaders($this->service())
            ->post(config('services.supabase.url') . '/rest/v1/jabatan', $data);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan');
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $response = Http::withHeaders($this->anon())
            ->get(config('services.supabase.url') . "/rest/v1/jabatan?id=eq.$id&select=*");

        $jabatan = $response->json()[0] ?? null;

        abort_if(!$jabatan, 404);

        return view('admin.jabatan.edit', compact('jabatan'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string',
            'tugas' => 'nullable|string',
            'fungsi' => 'nullable|array',
            'fungsi.*' => 'string',
            'is_active' => 'nullable|boolean',
        ]);

        Http::withHeaders($this->service())
            ->patch(config('services.supabase.url') . "/rest/v1/jabatan?id=eq.$id", $data);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui');
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        Http::withHeaders($this->service())
            ->delete(config('services.supabase.url') . "/rest/v1/jabatan?id=eq.$id");

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus');
    }
}
