<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Menampilkan form lupa password
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    // 2. Mengirim kode verifikasi ke email
    public function sendCode(Request $request)
    {
        try {
            $request->validate([
                'email_or_id' => 'required',
            ]);

            // Cek user berdasarkan email
            $user = User::where('email', $request->email_or_id)
                        ->orWhere('id_pegawai', $request->email_or_id)
                        ->first();

            if (!$user) {
                return back()->with('error', 'Email atau ID tidak ditemukan!');
            }

            if (empty($user->email)) {
                return back()->with('error', 'User tidak memiliki email terdaftar!');
            }

            // Generate kode 6 digit
            $code = rand(100000, 999999);

            // Simpan ke tabel verifikasi
            DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email],
                ['token' => $code, 'created_at' => now()]
            );

            // Kirim email menggunakan template
            try {
                Mail::send('emails.forgot-password', ['code' => $code], function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Kode Verifikasi Reset Password - RSUD Bangil');
                });

                // Simpan email ke session untuk digunakan di step verifikasi
                session(['email' => $user->email]);

                return redirect()->route('verify.form')->with(
                    'success', 'Kode verifikasi telah dikirim ke email Anda! Kode berlaku 15 menit.'
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            \Log::error('Error in sendCode: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // 3. Tampilkan form verifikasi kode
    public function showVerifyForm()
    {
        return view('auth.verify-code');
    }

    // 4. Verifikasi kode
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|array']);

        // Gabungkan kode dari input array
        $code = implode('', $request->code);
        $code = trim($code); // Hapus whitespace
        
        // Ambil email dari session
        $email = session('email');

        if (!$email) {
            return back()->with('error', 'Session berakhir. Silakan minta kode lagi!');
        }

        // Cek kode di database
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $code)
            ->first();

        if (!$record) {
            return back()->with('error', 'Kode verifikasi salah atau tidak valid!');
        }

        // Cek kode tidak kadaluarsa (valid 15 menit)
        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_resets')->where('email', $email)->delete();
            return back()->with('error', 'Kode verifikasi sudah kadaluarsa. Minta kode baru!');
        }

        // Kode benar, simpan email ke session untuk langkah berikutnya
        session(['email' => $email, 'verified' => true]);
        
        return redirect()->route('reset.form')->with('success', 'Kode verifikasi benar!');
    }

    // 5. Form reset password
    public function showResetForm()
    {
        // Cek apakah user sudah verify kode
        if (!session('verified')) {
            return redirect()->route('verify.form')->with('error', 'Silakan verifikasi kode terlebih dahulu!');
        }
        return view('auth.reset-password');
    }

    // 6. Simpan password baru
    public function resetPassword(Request $request)
    {
        // Validasi session
        if (!session('verified') || !session('email')) {
            session()->forget(['email', 'verified']);
            return redirect()->route('forgot.form')->with('error', 'Session berakhir. Coba lagi dari awal!');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $email = session('email');

        // Cek user exist
        $user = User::where('email', $email)->first();
        if (!$user) {
            session()->forget(['email', 'verified']);
            return redirect()->route('forgot.form')->with('error', 'User tidak ditemukan!');
        }

        // Update password
        // CATATAN: User model punya 'password' => 'hashed' cast
        // jadi Laravel akan otomatis Hash::make() saat di-set
        try {
            // Trim password to remove any whitespace
            $newPassword = trim($request->password);
            
            $user->password = $newPassword; // Langsung assign, bukan Hash::make()
            $user->save();

            // Verify the password was saved correctly by testing it
            if (!\Hash::check($newPassword, $user->fresh()->password)) {
                \Log::error('Password verification failed after save', [
                    'email' => $email,
                    'id_pegawai' => $user->id_pegawai
                ]);
                return back()->with('error', 'Terjadi kesalahan saat menyimpan password. Silakan coba lagi!');
            }

            \Log::info('Password berhasil direset dan diverifikasi untuk user: ' . $email, [
                'email' => $email,
                'id_pegawai' => $user->id_pegawai,
                'nama' => $user->nama,
                'password_length' => strlen($newPassword)
            ]);

            // Hapus record password_resets
            DB::table('password_resets')->where('email', $email)->delete();

            // Hapus session yang sudah tidak perlu
            session()->forget(['email', 'verified']);

            return redirect('/login')->with('success', 'Password berhasil direset! Silakan login dengan ID Pegawai: ' . $user->id_pegawai . ' dan password baru Anda.');
        } catch (\Exception $e) {
            \Log::error('Error reset password: ' . $e->getMessage(), [
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal menyimpan password baru. Coba lagi!');
        }
    }
}