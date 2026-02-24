<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'nama' => 'Infrastruktur & Jalan',
            'deskripsi' => 'Pengaduan terkait jalan berlubang, jembatan rusak, atau fasilitas umum fisik lainnya.',
            'sla_hari' => 5,
        ]);

        Kategori::create([
            'nama' => 'Penerangan Jalan Umum (PJU)',
            'deskripsi' => 'Pengaduan lampu jalan mati atau tiang listrik bermasalah.',
            'sla_hari' => 2,
        ]);

        Kategori::create([
            'nama' => 'Kebersihan & Lingkungan',
            'deskripsi' => 'Pengaduan tumpukan sampah, saluran air mampet, atau pencemaran.',
            'sla_hari' => 3,
        ]);

        Kategori::create([
            'nama' => 'Ketertiban Umum (Trantib)',
            'deskripsi' => 'Pengaduan gangguan keamanan, kebisingan, atau pelanggaran perdes.',
            'sla_hari' => 1,
        ]);

        Kategori::create([
            'nama' => 'Pelayanan Publik Desa/Kecamatan',
            'deskripsi' => 'Pengaduan terkait kelambatan administrasi atau pungli.',
            'sla_hari' => 3,
        ]);
    }
}