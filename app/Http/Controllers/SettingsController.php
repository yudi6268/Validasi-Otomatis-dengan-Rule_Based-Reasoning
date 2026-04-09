<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function show()
    {
        return view('settings');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama salah',
            ]);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('Password updated for user: ' . $user->email);

        // LOGOUT OTOMATIS agar user login dengan password baru
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Password berhasil diperbarui. Silakan login dengan password baru Anda.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate(
            [
                'current_email' => ['required', 'email'],
                'new_email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore(auth()->id()),
                ],
            ],
            [
                'current_email.required' => 'Email lama wajib diisi',
                'current_email.email' => 'Format email lama tidak valid',
                'new_email.required' => 'Email baru wajib diisi',
                'new_email.email' => 'Format email baru tidak valid',
                'new_email.unique' => 'Email sudah digunakan oleh akun lain',
            ]
        );

        $user = Auth::user();

        // Cek email lama
        if ($request->current_email !== $user->email) {
            return back()->withErrors([
                'current_email' => 'Email lama tidak sesuai dengan email akun Anda',
            ]);
        }

        $oldEmail = $user->email;

        // Update email
        $user->email = $request->new_email;
        $user->save();

        Log::info('Email updated from ' . $oldEmail . ' to ' . $user->email);

        // Refresh user session dengan data terbaru
        auth()->setUser($user->fresh());

        return back()->with('success', 'Email berhasil diperbarui. Gunakan email baru untuk login dan reset password.');
    }
}