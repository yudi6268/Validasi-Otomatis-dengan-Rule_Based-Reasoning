<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
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

        // LOGOUT OTOMATIS 
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Password berhasil diperbarui, silakan login ulang');
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
            'new_email.unique' => 'Email sudah digunakan',
        ]
    );

    $user = Auth::user();

    // Cek email lama
    if ($request->current_email !== $user->email) {
        return back()->withErrors([
            'current_email' => 'Email lama tidak sesuai',
        ]);
    }

    // Update email
    $user->email = $request->new_email;
    $user->save();

    // Refresh user session tanpa logout
    auth()->setUser($user);

    return back()->with('success', 'Email berhasil diperbarui');
    }
}