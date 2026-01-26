<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'user_id' => 'required|string',
                'password' => 'required|string'
            ], [
                'user_id.required' => 'ID Pegawai wajib diisi',
                'password.required' => 'Password wajib diisi'
            ]);


            $user = User::where('id_pegawai', $credentials['user_id'])->first();

            if (!$user) {
                Log::warning('Login attempt with non-existent ID Pegawai', [
                    'id_pegawai' => $credentials['user_id'],
                    'ip' => $request->ip()
                ]);

                throw ValidationException::withMessages([
                    'login' => ['ID Pegawai tidak ditemukan. Pastikan Anda memasukkan ID Pegawai yang benar. Jika tidak tahu, hubungi administrator.']
                ]);
            }

            // Cek status user
            if ($user->status !== 'active') {
                Log::warning('Login attempt for non-active/pending user', [
                    'id_pegawai' => $credentials['user_id'],
                    'status' => $user->status,
                    'ip' => $request->ip()
                ]);
                $msg = $user->status === 'pending' 
                    ? 'Akun Anda masih PENDING (menunggu persetujuan). Hubungi administrator untuk aktivasi akun.' 
                    : 'Akun Anda NON-AKTIF (dinonaktifkan). Hubungi administrator untuk mengaktifkan kembali akun.';
                throw ValidationException::withMessages([
                    'login' => [$msg]
                ]);
            }

            // Attempt to authenticate
            if (Auth::attempt(['id_pegawai' => $credentials['user_id'], 'password' => $credentials['password']])) {
                $request->session()->regenerate();
                
                // Log successful login
                Log::info('User logged in successfully', [
                    'id_pegawai' => $user->id_pegawai,
                    'nama' => $user->nama,
                    'jabatan' => $user->jabatan,
                    'ip' => $request->ip()
                ]);
                
                // Redirect based on role
                // Cek role terlebih dahulu untuk admin
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Selamat datang di Admin Panel, ' . $user->nama);
                }
                
                // Cek jabatan untuk Direktur
                $jabatan = $user->jabatan;
                if ($jabatan === 'Direktur') {
                    return redirect()->route('dashboard.direktur')
                        ->with('success', 'Selamat datang, ' . $user->nama);
                }
                
                // Semua user lainnya (Wadir, Kabag, Kabid, Katimker/Staf) ke home
                // Mereka akan akses dashboard dari tombol "BUKA" di home
                return redirect()->route('home')
                    ->with('success', 'Selamat datang, ' . $user->nama);
            }

            // Log failed login attempt with more details
            Log::warning('Failed login attempt - Wrong password', [
                'id_pegawai' => $credentials['user_id'],
                'nama' => $user->nama,
                'email' => $user->email,
                'jabatan' => $user->jabatan,
                'password_length' => strlen($credentials['password']),
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'login' => ['Password yang Anda masukkan SALAH. Periksa kembali password Anda. Jika lupa password, gunakan fitur "Lupa Password?"']
            ]);

        } catch (ValidationException $e) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'id_pegawai' => $request->user_id,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => 'Terjadi kesalahan saat login. Silakan coba lagi atau hubungi admin.']);
        }
    }

    public function logout(Request $request)
    {
        // Log the logout event
        $user = Auth::user();
        if ($user) {
            Log::info('User logged out', [
                'id_pegawai' => $user->id_pegawai,
                'nama' => $user->nama,
                'ip' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Support both AJAX and regular requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
        
        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem');
    }
}