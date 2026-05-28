<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun administrator baru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('--- Membuat Akun Staf/Administrator ---');
        
        $name = $this->ask('Nama Lengkap');
        $no_wa = $this->ask('Nomor WhatsApp');
        $email = $this->ask('Alamat Email');
        $password = $this->secret('Password');
        $role = $this->choice('Role Pengguna', ['superadmin', 'admin', 'petugas'], 1);

        // Validasi Sederhana
        if (!$name || !$no_wa || !$email || !$password) {
            $this->error('Gagal: Semua field wajib diisi!');
            return;
        }

        if (\App\Models\User::where('email', $email)->exists()) {
            $this->error('Gagal: Email ini sudah terdaftar!');
            return;
        }

        try {
            $user = \App\Models\User::create([
                'name' => strtoupper($name),
                'no_wa' => $no_wa,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => $role,
                'email_verified_at' => now(),
            ]);

            $this->info("Sukses: Pengguna '{$user->name}' dengan role '{$role}' berhasil dibuat dan otomatis terverifikasi!");
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
