<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user if not exists
        $admin = User::where('email', 'admin@rsudbangil.com')->orWhere('role', 'admin')->first();

        if ($admin) {
            $this->command->info('✅ Admin sudah ada: ' . $admin->email . ' (role: ' . $admin->role . ')');
            return;
        }

        User::create([
            'id_pegawai' => 'ADM001',
            'nama' => 'Administrator',
            'nip' => '199999999999999999',
            'jabatan' => 'Administrator Sistem',
            'pangkat' => 'Administrator',
            'divisi' => 'IT',
            'email' => 'admin@rsudbangil.com',
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('Admin123!'),
        ]);

        $this->command->info('✅ Admin default berhasil dibuat!');
        $this->command->info('📧 Email: admin@rsudbangil.com');
        $this->command->info('🔑 Password: Admin123!');
    }
}
