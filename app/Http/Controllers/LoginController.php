<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $start = microtime(true);
        \Log::info("LOGIN PROFILING: Start");

        try {
            $credentials = $request->validate([
                'user_id' => 'required|string',
                'password' => 'required|string'
            ], [
                'user_id.required' => 'ID Pegawai wajib diisi',
                'password.required' => 'Password wajib diisi'
            ]);
            
            \Log::info("LOGIN PROFILING: Validation done. Time: " . (microtime(true) - $start));

            $user = \Illuminate\Support\Facades\Cache::remember('user_login_' . $credentials['user_id'], 30, function() use ($credentials) {
                return User::where('id_pegawai', $credentials['user_id'])->first();
            });
            
            \Log::info("LOGIN PROFILING: Fetch user done. Time: " . (microtime(true) - $start));

            if (!$user) {
                throw ValidationException::withMessages([
                    'login' => ['ID Pegawai tidak ditemukan.']
                ]);
            }

            if ($user->status !== 'active') {
                throw ValidationException::withMessages([
                    'login' => ['Akun Anda NON-AKTIF.']
                ]);
            }

            \Log::info("LOGIN PROFILING: Status check done. Time: " . (microtime(true) - $start));

            if (\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                \Log::info("LOGIN PROFILING: Hash::check done. Time: " . (microtime(true) - $start));
                
                Auth::login($user);
                \Log::info("LOGIN PROFILING: Auth::login done. Time: " . (microtime(true) - $start));
                
                $request->session()->regenerate();
                \Log::info("LOGIN PROFILING: session()->regenerate done. Time: " . (microtime(true) - $start));
                
                $hasUnreadNotifications = \Illuminate\Support\Facades\Cache::remember('unread_notifs_' . $user->id, 60, function() use ($user) {
                    return Notification::unread()->forUser($user->id)->exists();
                });
                \Log::info("LOGIN PROFILING: Notification fetch done. Time: " . (microtime(true) - $start));
                
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
                        ->with('success', 'Selamat datang di Admin Panel, ' . $user->nama)
                        ->with('show_notification_modal', $hasUnreadNotifications);
                }
                
                // Cek jabatan untuk redirect yang sesuai
                $jabatan = $user->jabatan;

                // Direktur ke dashboard direktur
                if ($jabatan === 'Direktur') {
                    return redirect()->route('dashboard.direktur')
                        ->with('success', 'Selamat datang, ' . $user->nama)
                        ->with('show_notification_modal', $hasUnreadNotifications);
                }

                // Semua akun non-admin memakai dashboard wadir yang sama
                return redirect()->route('dashboard.wadir')
                    ->with('success', 'Selamat datang, ' . $user->nama)
                    ->with('show_notification_modal', $hasUnreadNotifications);
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