<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perjanjian;
use Illuminate\Http\Request;

class PerjanjianController extends Controller
{
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
            ]);
            return back()->with('success', 'Status perjanjian berhasil direset ke "Menunggu".');
        }

        if ($request->action === 'approve') {
            $perjanjian->update([
                'rejected'         => false,
                'pihak2_signature' => 'admin_approved',
            ]);
            return back()->with('success', 'Perjanjian berhasil disetujui oleh admin.');
        }

        if ($request->action === 'reject') {
            $perjanjian->update([
                'rejected'          => true,
                'pihak2_signature'  => null,
                'catatan_penolakan' => $request->catatan,
                'rejection_reason'  => $request->catatan,
            ]);
            return back()->with('success', 'Perjanjian berhasil ditolak oleh admin.');
        }
    }

    public function destroy($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $perjanjian->delete();
        return back()->with('success', 'Perjanjian berhasil dihapus.');
    }
}
