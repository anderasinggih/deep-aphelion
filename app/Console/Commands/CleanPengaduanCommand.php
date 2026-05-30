<?php

namespace App\Console\Commands;

use App\Models\Pengaduan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CleanPengaduanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengaduan:clean 
                            {--all : Bersihkan semua pengaduan beserta data terkait (histories, komentar, file bukti)} 
                            {--status= : Bersihkan pengaduan berdasarkan status tertentu (menunggu, diproses, selesai, ditolak)} 
                            {--force : Lewati konfirmasi / paksa eksekusi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan data aduan (pengaduan) beserta log aktivitas, komentar, dukungan, dan file gambar bukti fisik dari storage.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $all = $this->option('all');
        $status = $this->option('status');
        $force = $this->option('force');

        if (!$all && !$status) {
            $this->error('Anda harus menentukan opsi pencucian data. Gunakan --all untuk membersihkan semua aduan, atau --status=STATUS untuk membersihkan status tertentu.');
            $this->info('Contoh: php artisan pengaduan:clean --all');
            $this->info('Contoh: php artisan pengaduan:clean --status=ditolak');
            return Command::FAILURE;
        }

        // Tentukan query target pengaduan (termasuk yang disoft-delete agar bisa dibersihkan permanen)
        $query = Pengaduan::withTrashed();

        if ($status) {
            $validStatuses = ['menunggu', 'diproses', 'selesai', 'ditolak'];
            if (!in_array(strtolower($status), $validStatuses)) {
                $this->error("Status '{$status}' tidak valid. Pilih salah satu dari: " . implode(', ', $validStatuses));
                return Command::FAILURE;
            }
            $query->where('status', strtolower($status));
        }

        $totalCount = $query->count();

        if ($totalCount === 0) {
            $this->info('Tidak ada data aduan yang cocok dengan filter untuk dibersihkan.');
            return Command::SUCCESS;
        }

        $this->warn("Tindakan ini akan menghapus PERMANEN {$totalCount} aduan beserta data relasi (histories, komentar, dukungan) dan file fisiknya dari storage.");

        if (!$force && !$this->confirm('Apakah Anda yakin ingin melanjutkan tindakan ini?', false)) {
            $this->info('Tindakan dibatalkan.');
            return Command::SUCCESS;
        }

        $this->info('Memulai pembersihan data...');

        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();

        // Ambil data secara chunked agar memori efisien
        $query->chunk(100, function ($pengaduans) use ($bar) {
            foreach ($pengaduans as $pengaduan) {
                // 1. Hapus file foto_bukti dari storage
                if ($pengaduan->foto_bukti && is_array($pengaduan->foto_bukti)) {
                    foreach ($pengaduan->foto_bukti as $foto) {
                        if (Storage::disk('public')->exists($foto)) {
                            Storage::disk('public')->delete($foto);
                        }
                    }
                }

                // 2. Hapus file foto_penyelesaian dari storage jika ada
                if ($pengaduan->foto_penyelesaian && Storage::disk('public')->exists($pengaduan->foto_penyelesaian)) {
                    Storage::disk('public')->delete($pengaduan->foto_penyelesaian);
                }

                // 3. Putuskan hubungan rujukan (null-kan linked_id di laporan yang merujuk laporan ini)
                $pengaduan->childReports()->update(['linked_id' => null]);

                // 4. Hapus data relasi di database
                $pengaduan->histories()->forceDelete();
                $pengaduan->komentars()->forceDelete();
                $pengaduan->dukungans()->forceDelete();

                // 5. Hapus permanen pengaduan
                $pengaduan->forceDelete();

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Sukses! {$totalCount} aduan beserta relasi dan file storage berhasil dibersihkan secara permanen.");

        return Command::SUCCESS;
    }
}
