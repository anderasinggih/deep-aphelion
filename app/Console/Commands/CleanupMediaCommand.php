<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-media';
    protected $description = 'Hapus foto laporan yang sudah melewati batas waktu penyimpanan';

    public function handle()
    {
        $aktif = \App\Models\Setting::where('key', 'media_cleanup_aktif')->first()?->value ?? false;
        
        if (!$aktif) {
            $this->info('Cleanup media dinonaktifkan di pengaturan.');
            return;
        }

        $bulan = \App\Models\Setting::where('key', 'media_cleanup_bulan')->first()?->value ?? 24;
        $thresholdDate = now()->subMonths($bulan);

        $this->info("Memulai pembersihan media untuk laporan sebelum: " . $thresholdDate->format('Y-m-d'));

        $pengaduans = \App\Models\Pengaduan::where('created_at', '<', $thresholdDate)
            ->whereNotNull('foto_bukti')
            ->get();

        $count = 0;
        foreach ($pengaduans as $p) {
            $fotos = is_array($p->foto_bukti) ? $p->foto_bukti : json_decode($p->foto_bukti, true);
            
            if ($fotos) {
                foreach ($fotos as $foto) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($foto)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($foto);
                    }
                }
                
                $p->update(['foto_bukti' => []]);
                $count++;
            }
        }

        $this->info("Selesai! Berhasil membersihkan media dari $count laporan.");
    }
}
