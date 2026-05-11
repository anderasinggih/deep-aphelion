<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'warga')->pluck('id');
        $kategoris = Kategori::pluck('id');

        if ($users->isEmpty() || $kategoris->isEmpty()) {
            $this->command->error('Pastikan sudah ada data User Warga dan Kategori sebelum menjalankan seeder ini.');
            return;
        }

        $statuses = ['menunggu', 'diproses', 'selesai'];
        $prioritas = ['rendah', 'sedang', 'tinggi'];
        
        $judulContoh = [
            'Jalan Berlubang di Depan Balai Desa',
            'Lampu Penerangan Jalan Mati',
            'Sampah Menumpuk di Pinggir Sungai',
            'Drainase Tersumbat Air Meluap',
            'Pohon Tumbang Menutup Akses',
            'Gangguan Kamtibmas Larut Malam',
            'Bantuan Sosial Tidak Tepat Sasaran',
            'Pungli di Lokasi Parkir',
            'Kualitas Air PDAM Keruh',
            'Jaringan Internet Lemah di Area Perbukitan'
        ];

        for ($i = 1; $i <= 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $createdDate = Carbon::now()->subDays(rand(1, 90));
            
            // Generate Koordinat sekitar Kembaran (-7.4245, 109.2882)
            $lat = -7.4245 + (rand(-50, 50) / 1000);
            $lng = 109.2882 + (rand(-50, 50) / 1000);

            Pengaduan::create([
                'kode_tracking' => 'KMB-' . $createdDate->format('Ym') . '-' . str_pad($i + 100, 4, '0', STR_PAD_LEFT),
                'user_id' => $users->random(),
                'kategori_id' => $kategoris->random(),
                'judul' => $judulContoh[array_rand($judulContoh)] . ' #' . $i,
                'deskripsi' => 'Ini adalah deskripsi laporan simulasi ke-' . $i . '. Laporan ini dikirimkan untuk keperluan pengujian performa dashboard dan peta sebaran.',
                'tanggal_kejadian' => $createdDate->subDays(rand(0, 2)),
                'prioritas' => $prioritas[array_rand($prioritas)],
                'lokasi_kejadian' => 'Desa ' . ['Kembaran', 'Kramat', 'Pliken', 'Bojongsari'][array_rand(['Kembaran', 'Kramat', 'Pliken', 'Bojongsari'])] . ', Kec. Kembaran',
                'latitude' => $lat,
                'longitude' => $lng,
                'is_anonymous' => (rand(1, 10) > 8),
                'is_private' => (rand(1, 10) > 9),
                'status' => $status,
                'created_at' => $createdDate,
                'updated_at' => $status === 'selesai' ? $createdDate->addHours(rand(24, 72)) : $createdDate,
                'pesan_penutup' => $status === 'selesai' ? 'Terima kasih, laporan Anda telah kami selesaikan dengan baik.' : null,
            ]);
        }

        $this->command->info('Berhasil membuat 20 data laporan dummy.');
    }
}
