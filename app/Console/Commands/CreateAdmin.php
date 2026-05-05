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
        $name = $this->ask('Nama Lengkap');
        $nik = $this->ask('NIK (16 digit)');
        $no_wa = $this->ask('Nomor WhatsApp');
        $email = $this->ask('Alamat Email');
        $password = $this->secret('Password');

        if (!$name || !$nik || !$no_wa || !$email || !$password) {
            $this->error('Semua field wajib diisi!');
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

            $this->info("Admin '{$user->name}' berhasil dibuat!");
        } catch (\Exception $e) {
            $this->error('Gagal membuat admin: ' . $e->getMessage());
        }
    }
}
