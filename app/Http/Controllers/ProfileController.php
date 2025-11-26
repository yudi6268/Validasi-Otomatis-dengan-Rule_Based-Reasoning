<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return view('profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'nullable|string|max:255',
            'id_pegawai' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'pangkat' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'croppedPhotoData' => 'nullable|string',
            'signature_data' => 'nullable|string',
        ]);

        try {

            /* ===========================
             * SIMPAN DATA TEXT BIASA
             =========================== */
            $user->update($request->except([
                'croppedPhotoData',
                'signature_data'
            ]));

            /* ===========================
             * SETTING SUPABASE
             =========================== */
            $supabaseUrl = env('SUPABASE_URL');
            $serviceKey  = env('SUPABASE_SERVICE_ROLE_KEY');
            $anonKey     = env('SUPABASE_ANON_KEY');
            $bucket      = env('SUPABASE_STORAGE_BUCKET', 'public');

            /* ======================================================
             * 1) HAPUS FOTO PROFIL
             ====================================================== */
            if ($request->croppedPhotoData === "__DELETE_PHOTO__") {
                $user->foto_profil = null;
            }

            /* ======================================================
             * 2) UPLOAD FOTO PROFIL KE SUPABASE
             ====================================================== */
            if ($request->filled('croppedPhotoData') && 
                $request->croppedPhotoData !== "__DELETE_PHOTO__") {

                $raw = preg_replace('/^data:image\/\w+;base64,/', '', $request->croppedPhotoData);
                $binary = base64_decode($raw);

                $remotePath = 'foto_profil/' . $user->id . '_' . uniqid() . '.png';

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $serviceKey,
                    'apikey' => $anonKey,
                    'Content-Type' => 'image/png',
                ])->withBody($binary, 'image/png')->put(
                    rtrim($supabaseUrl, '/') . "/storage/v1/object/$bucket/$remotePath"
                );

                if ($response->successful()) {
                    $user->foto_profil = 
                        rtrim($supabaseUrl, '/') . "/storage/v1/object/public/$bucket/$remotePath";
                }
            }

            /* ======================================================
             * 3) UPLOAD TANDA TANGAN KE SUPABASE
             ====================================================== */
            if ($request->filled('signature_data')) {

                $raw = preg_replace('/^data:image\/\w+;base64,/', '', $request->signature_data);
                $binary = base64_decode($raw);

                $remotePath = 'tanda_tangan/' . $user->id . '_' . uniqid() . '.png';

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $serviceKey,
                    'apikey' => $anonKey,
                    'Content-Type' => 'image/png',
                ])->withBody($binary, 'image/png')->put(
                    rtrim($supabaseUrl, '/') . "/storage/v1/object/$bucket/$remotePath"
                );

                if ($response->successful()) {
                    $user->tanda_tangan = 
                        rtrim($supabaseUrl, '/') . "/storage/v1/object/public/$bucket/$remotePath";
                }
            }

            $user->save();

            return back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {

            \Log::error('Profile update failed: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan profil.'
            ]);
        }
    }
}