<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TriwulanSettingController extends Controller
{
    /**
     * Tampilkan form untuk mengatur triwulan aktif
     */
    public function show()
    {
        // Verifikasi user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        $triwulanAktif = Setting::where('key', 'triwulan_aktif')->first();
        $triwulan = $triwulanAktif ? (int)$triwulanAktif->value : 1;

        return view('admin.triwulan-setting', compact('triwulan'));
    }

    /**
     * Update triwulan aktif
     */
    public function update(Request $request)
    {
        // Verifikasi user adalah admin
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses'
            ], 403);
        }

        $validated = $request->validate([
            'triwulan' => 'required|integer|min:1|max:4'
        ]);

        $setting = Setting::where('key', 'triwulan_aktif')->first();

        if ($setting) {
            $setting->update(['value' => (string)$validated['triwulan']]);
        } else {
            Setting::create([
                'key' => 'triwulan_aktif',
                'value' => (string)$validated['triwulan']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Triwulan aktif berhasil diubah menjadi Triwulan ' . $validated['triwulan'],
            'triwulan' => $validated['triwulan']
        ]);
    }
}
