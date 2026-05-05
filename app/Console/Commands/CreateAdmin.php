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
        $this->info('--- Membuat Akun Administrator ---');
        
        $name = $this->ask('Nama Lengkap');
        $nik = $this->ask('NIK (16 digit)');
        $no_wa = $this->ask('Nomor WhatsApp');
        $email = $this->ask('Alamat Email');
        $password = $this->secret('Password');

        // Validasi Sederhana
        if (!$name || !$nik || !$no_wa || !$email || !$password) {
            $this->error('Gagal: Semua field wajib diisi!');
            return;
        }

        if (strlen($nik) !== 16) {
            $this->error('Gagal: NIK harus tepat 16 digit angka!');
            return;
        }

        if (\App\Models\User::where('email', $email)->exists()) {
            $this->error('Gagal: Email ini sudah terdaftar!');
            return;
        }

        if (\App\Models\User::where('nik', $nik)->exists()) {
            $this->error('Gagal: NIK ini sudah terdaftar!');
            return;
        }

        try {
            $user = \App\Models\User::create([
                'name' => strtoupper($name),
                'nik' => $nik,
                'no_wa' => $no_wa,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->info("Sukses: Admin '{$user->name}' berhasil dibuat dan otomatis terverifikasi!");
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
