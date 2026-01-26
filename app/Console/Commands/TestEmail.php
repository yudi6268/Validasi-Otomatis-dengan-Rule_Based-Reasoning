<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test konfigurasi email dengan mengirim test email';

    public function handle()
    {
        $email = $this->argument('email');
        $code = rand(100000, 999999);

        $this->info('Mengirim test email ke: ' . $email);
        $this->info('Kode verifikasi: ' . $code);

        try {
            Mail::send('emails.forgot-password', ['code' => $code], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Kode Verifikasi Reset Password');
            });

            if (Mail::failures()) {
                $this->error('❌ Email gagal terkirim!');
                $this->error('Failures: ' . json_encode(Mail::failures()));
                return 1;
            }

            $this->info('');
            $this->info('✅ Email berhasil terkirim!');
            $this->info('Silakan cek inbox di: ' . $email);
            $this->info('Jangan lupa cek folder SPAM jika tidak ada di inbox');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('');
            $this->error('Troubleshooting:');
            $this->error('1. Pastikan MAIL_* di .env sudah benar');
            $this->error('2. Jalankan: php artisan config:clear');
            $this->error('3. Cek file SETUP_EMAIL.md untuk panduan lengkap');
            
            return 1;
        }
    }
}
