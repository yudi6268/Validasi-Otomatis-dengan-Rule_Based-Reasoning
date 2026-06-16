<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with(['user', 'perjanjian'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('pihak1_name', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('periode', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'approved') {
                $query->whereNotNull('pihak2_signature');
            } elseif ($status === 'waiting') {
                $query->whereNull('pihak2_signature');
            }
        }

        if ($tahun = $request->get('tahun')) {
            $query->where('tahun', $tahun);
        }

        $laporans = $query->paginate(20)->withQueryString();
        $tahunList = Laporan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');

        return view('admin.laporan.index', compact('laporans', 'tahunList'));
    }

    public function revisiStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:reset,approve',
        ]);

        $laporan = Laporan::findOrFail($id);

        if ($request->action === 'reset') {
            $laporan->update([
                'pihak2_signature' => null,
                'pihak2_name'      => null,
                'pihak2_jabatan'   => null,
                'tanggapan_pimpinan' => null,
            ]);
            return back()->with('success', 'Status laporan berhasil direset ke "Menunggu".');
        }

        if ($request->action === 'approve') {
            $laporan->update(['pihak2_signature' => 'admin_approved']);
            return back()->with('success', 'Laporan berhasil disetujui oleh admin.');
        }
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();
        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
