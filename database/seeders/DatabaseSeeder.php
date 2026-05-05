<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@kembaran.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Petugas Trantib',
            'email' => 'trantib@kembaran.go.id',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Warga Tester',
            'email' => 'warga@example.com',
            'nik' => '3302091234567890',
            'no_wa' => '081234567890',
            'password' => bcrypt('password'),
            'role' => 'warga',
            'email_verified_at' => now(),
        ]);

        $this->call([
            KategoriSeeder::class ,
        ]);
    }
}