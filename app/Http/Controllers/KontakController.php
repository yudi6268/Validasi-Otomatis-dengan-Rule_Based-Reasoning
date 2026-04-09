<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class KontakController extends Controller
{
    public function show()
    {
        return view('kontak');
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string|max:5000',
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
                'pesan' => $request->pesan,
            ];

            // Email tujuan dari .env atau default
            $developerEmail = env('DEVELOPER_EMAIL', env('MAIL_FROM_ADDRESS', 'magangrsudbangil@gmail.com'));

            Mail::send('emails.kontak', $data, function ($message) use ($data, $developerEmail) {
                $message->to($developerEmail)
                    ->subject('Pesan Kontak dari ' . $data['nama'])
                    ->replyTo($data['email'], $data['nama']);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah terkirim. Terima kasih.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending contact email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan. Silakan coba lagi.'
            ], 500);
        }
    }
}
