<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perjanjian;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerjanjianController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index(Request $request)
    {
        $query = Perjanjian::with('user')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pihak1_name', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'approved') {
                $query->whereNotNull('pihak2_signature')
                      ->where(fn($q) => $q->whereNull('rejected')->orWhere('rejected', false));
            } elseif ($status === 'rejected') {
                $query->where('rejected', true);
            } elseif ($status === 'waiting') {
                $query->whereNull('pihak2_signature')
                      ->where(fn($q) => $q->whereNull('rejected')->orWhere('rejected', false));
            }
        }

        if ($tahun = $request->get('tahun')) {
            $query->where('tahun', $tahun);
        }

        $perjanjians = $query->paginate(20)->withQueryString();
        $tahunList = Perjanjian::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('admin.perjanjian.index', compact('perjanjians', 'tahunList'));
    }

    public function revisiStatus(Request $request, $id)
    {
        $request->validate([
            'action'  => 'required|in:reset,approve,reject',
            'catatan' => 'required_if:action,reject|nullable|string|max:500',
        ]);

        $perjanjian = Perjanjian::findOrFail($id);

        if ($request->action === 'reset') {
            $perjanjian->update([
                'rejected'          => false,
                'pihak2_signature'  => null,
                'catatan_penolakan' => null,
                'rejection_reason'  => null,
                'status'            => 'menunggu',
            ]);
            $this->syncPerjanjianToSupabase($perjanjian);
            return back()->with('success', 'Status perjanjian berhasil direset ke "Menunggu".');
        }

        if ($request->action === 'approve') {
            $perjanjian->update([
                'rejected'         => false,
                'pihak2_signature' => 'admin_approved',
                'status'           => 'disetujui',
            ]);
            $this->syncPerjanjianToSupabase($perjanjian);
            return back()->with('success', 'Perjanjian berhasil disetujui oleh admin.');
        }

        if ($request->action === 'reject') {
            $perjanjian->update([
                'rejected'          => true,
                'pihak2_signature'  => null,
                'catatan_penolakan' => $request->catatan,
                'rejection_reason'  => $request->catatan,
                'status'            => 'ditolak',
            ]);
            $this->syncPerjanjianToSupabase($perjanjian);
            return back()->with('success', 'Perjanjian berhasil ditolak oleh admin.');
        }
    }

    public function destroy($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $perjanjian->delete();
        return back()->with('success', 'Perjanjian berhasil dihapus.');
    }

    private function syncPerjanjianToSupabase(Perjanjian $perjanjian)
    {
        if (!(bool) config('services.supabase.sync_enabled', true)) {
            return;
        }

        $perjanjianId = $perjanjian->id;

        app()->terminating(function () use ($perjanjianId) {
            try {
                $perjanjian = Perjanjian::find($perjanjianId);
                if (!$perjanjian) return;

                $payload = [
                    'pihak2_name' => $perjanjian->pihak2_name,
                    'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
                    'pihak2_nip' => $perjanjian->pihak2_nip,
                    'location' => $perjanjian->location,
                    'agreement_date' => $perjanjian->agreement_date,
                    'jabatan' => $perjanjian->jabatan,
                    'jabatan_pelaksana' => $perjanjian->jabatan_pelaksana,
                    'tugas_pelaksana' => $perjanjian->tugas_pelaksana,
                    'fungsi_pelaksana' => $perjanjian->fungsi_pelaksana,
                    'pihak1_ttd' => $perjanjian->pihak1_ttd,
                    'status' => $perjanjian->status,
                    'catatan_penolakan' => $perjanjian->catatan_penolakan,
                    'rejected' => $perjanjian->rejected,
                    'rejection_reason' => $perjanjian->rejection_reason,
                    'pihak2_signature' => $perjanjian->pihak2_signature,
                    'pihak2_ttd_path' => $perjanjian->pihak2_ttd_path,
                    'tabelA' => $perjanjian->tabelA,
                    'tabelB' => $perjanjian->tabelB,
                    'tabelC' => $perjanjian->tabelC,
                ];

                $filters = ['local_id' => 'eq.' . $perjanjian->id];
                $res = $this->supabase->update('perjanjians', $filters, $payload);
                if (empty($res['success']) && !empty($perjanjian->nomor_perjanjian)) {
                    $filters = ['nomor_perjanjian' => 'eq.' . $perjanjian->nomor_perjanjian];
                    $res = $this->supabase->update('perjanjians', $filters, $payload);
                }
                if (empty($res['success'])) {
                    Log::warning('Deferred Supabase update failed for perjanjian #' . $perjanjian->id . ' in Admin: ' . ($res['error'] ?? 'unknown'));
                } else {
                    Log::info('Deferred Supabase update succeeded for perjanjian #' . $perjanjian->id . ' in Admin');
                }
            } catch (\Exception $e) {
                Log::warning('Deferred Supabase update exception for perjanjian #' . $perjanjianId . ' in Admin: ' . $e->getMessage());
            }
        });
    }
}

