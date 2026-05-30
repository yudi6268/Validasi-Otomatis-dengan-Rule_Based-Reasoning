<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\SupabaseService;

class ProfileController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
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
        $isJson = $request->header('Accept') === 'application/json' || $request->isJson();
        
        // Log request untuk debugging
        \Log::info('Profile update attempt', [
            'user_id' => $user->id,
            'has_photo' => $request->filled('croppedPhotoData'),
            'has_signature' => $request->filled('signature_data'),
            'isJson' => $isJson
        ]);

        $request->validate([
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
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
            $updateData = $request->except([
                $isJson ? '_skip_email_exception' : 'email',
                'croppedPhotoData',
                'signature_data'
            ]);
            $user->update($updateData);
            
            \Log::info('Profile text data updated', ['user_id' => $user->id]);

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

                $fileName = $user->id . '_' . uniqid() . '.png';
                $result = $this->supabase->uploadBase64Image(
                    $request->croppedPhotoData,
                    $fileName,
                    'foto_profil'
                );

                if ($result['success']) {
                    $user->foto_profil = $result['url'];
                    \Log::info('Foto profil uploaded successfully', [
                        'user_id' => $user->id,
                        'url' => $result['url']
                    ]);
                } else {
                    \Log::error('Failed to upload foto profil', [
                        'user_id' => $user->id,
                        'error' => $result['error']
                    ]);
                }
            }

            /* ======================================================
             * 3) UPLOAD TANDA TANGAN KE SUPABASE
             ====================================================== */
            if ($request->filled('signature_data')) {

                $fileName = $user->id . '_' . uniqid() . '.png';
                $result = $this->supabase->uploadBase64Image(
                    $request->signature_data,
                    $fileName,
                    'tanda_tangan'
                );

                if ($result['success']) {
                    $user->tanda_tangan = $result['url'];
                    \Log::info('Tanda tangan uploaded successfully', [
                        'user_id' => $user->id,
                        'url' => $result['url']
                    ]);
                } else {
                    \Log::error('Failed to upload tanda tangan', [
                        'user_id' => $user->id,
                        'error' => $result['error']
                    ]);
                }
            }

            $user->save();

            // Refresh user data dari database untuk memastikan data terbaru
            $user->refresh();
            
            \Log::info('Profile update successful', [
                'user_id' => $user->id,
                'foto_profil' => $user->foto_profil,
                'tanda_tangan' => $user->tanda_tangan
            ]);

            if ($isJson) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui',
                    'data' => [
                        'nama' => $user->nama,
                        'email' => $user->email,
                        'nip' => $user->nip,
                        'pangkat' => $user->pangkat,
                        'id_pegawai' => $user->id_pegawai,
                        'foto_profil' => $user->foto_profil,
                        'tanda_tangan' => $user->tanda_tangan,
                    ]
                ]);
            }

            return back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {

            \Log::error('Profile update failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($isJson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan profil.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan profil.'
            ]);
        }
    }
    /**
     * Upload foto profil dari form file
     */
    public function uploadFoto(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $file = $request->file('foto_profil');
            $fileName = $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $result = $this->supabase->uploadFile(
                $file->getRealPath(),
                $fileName,
                'foto_profil'
            );

            if ($result['success']) {
                $user->foto_profil = $result['url'];
                $user->save();
                
                \Log::info('Foto profil uploaded successfully', [
                    'user_id' => $user->id,
                    'foto_url' => $user->foto_profil
                ]);
                
                return back()->with('success', 'Foto profil berhasil diupload!');
            } else {
                \Log::error('Failed to upload foto profil', [
                    'user_id' => $user->id,
                    'error' => $result['error']
                ]);
                return back()->withErrors(['error' => 'Gagal upload foto profil: ' . $result['error']]);
            }
        } catch (\Exception $e) {
            \Log::error('Exception while uploading foto profil: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat upload foto profil.']);
        }
    }
    /**
     * Upload tanda tangan dari form file
     */
    public function uploadTTD(Request $request)
    {
        $user = Auth::user();
        
        // Validasi: terima file upload ATAU signature canvas data
        $request->validate([
            'tanda_tangan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_data' => 'nullable|string',
        ]);

        // Cek apakah ada data yang dikirim
        if (!$request->hasFile('tanda_tangan') && !$request->filled('signature_data')) {
            return back()->withErrors(['error' => 'Silakan upload file atau gambar tanda tangan terlebih dahulu.']);
        }

        try {
            $result = null;
            
            // Case 1: Upload dari file
            if ($request->hasFile('tanda_tangan')) {
                $file = $request->file('tanda_tangan');
                $fileName = $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $result = $this->supabase->uploadFile(
                    $file->getRealPath(),
                    $fileName,
                    'tanda_tangan'
                );
            } 
            // Case 2: Upload dari canvas (base64)
            else if ($request->filled('signature_data')) {
                $fileName = $user->id . '_' . uniqid() . '.png';
                
                $result = $this->supabase->uploadBase64Image(
                    $request->signature_data,
                    $fileName,
                    'tanda_tangan'
                );
            }

            if ($result && $result['success']) {
                $user->tanda_tangan = $result['url'];
                $user->save();
                
                \Log::info('Tanda tangan uploaded successfully', [
                    'user_id' => $user->id,
                    'ttd_url' => $user->tanda_tangan
                ]);
                
                return back()->with('success', 'Tanda tangan berhasil diupload!');
            } else {
                \Log::error('Failed to upload tanda tangan', [
                    'user_id' => $user->id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
                return back()->withErrors(['error' => 'Gagal upload tanda tangan: ' . ($result['error'] ?? 'Unknown error')]);
            }
        } catch (\Exception $e) {
            \Log::error('Exception while uploading tanda tangan: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat upload tanda tangan.']);
        }
    }
}