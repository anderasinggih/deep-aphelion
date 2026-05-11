<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class TentangKami extends Component
{
    public $stats = [];
    public $instansi_nama, $instansi_alamat, $instansi_telepon, $instansi_email, $jam_senin_kamis, $jam_jumat, $jam_sabtu_minggu;

    public function mount()
    {
        $settings = Setting::whereIn('key', [
            'instansi_nama', 'instansi_alamat', 'instansi_telepon', 'instansi_email',
            'instansi_jam_senkam', 'instansi_jam_jumat', 'instansi_jam_sabtu', 'app_logo', 'app_logo_sekunder'
        ])->pluck('value', 'key');

        $this->instansi_nama = $settings['instansi_nama'] ?? 'Kantor Kecamatan Kembaran';
        $this->instansi_alamat = $settings['instansi_alamat'] ?? 'Jl. Kyai Kembar No. 17, Kembaran';
        $this->instansi_telepon = $settings['instansi_telepon'] ?? '(0281) 6840XXX';
        $this->instansi_email = $settings['instansi_email'] ?? 'kecamatan.kembaran@banyumaskab.go.id';
        
        $this->jam_senin_kamis = $settings['instansi_jam_senkam'] ?? '07.30 – 16.00 WIB';
        $this->jam_jumat = $settings['instansi_jam_jumat'] ?? '07.30 – 11.00 WIB';
        $this->jam_sabtu_minggu = $settings['instansi_jam_sabtu'] ?? 'LIBUR';
        $this->app_logo = $settings['app_logo'] ?? null;
        $this->app_logo_sekunder = $settings['app_logo_sekunder'] ?? null;

        // Fetch Public Stats
        $this->stats = [
            'total' => \App\Models\Pengaduan::count(),
            'selesai' => \App\Models\Pengaduan::where('status', 'selesai')->count(),
            'rating' => round(\App\Models\Pengaduan::whereNotNull('rating')->avg('rating') ?? 0, 1)
        ];
    }

    public function render()
    {
        return view('livewire.tentang-kami', [
            'instansi_nama' => $this->instansi_nama,
            'instansi_alamat' => $this->instansi_alamat,
            'instansi_telepon' => $this->instansi_telepon,
            'instansi_email' => $this->instansi_email,
            'instansi_jam_senkam' => $this->jam_senin_kamis,
            'instansi_jam_jumat' => $this->jam_jumat,
            'instansi_jam_sabtu' => $this->jam_sabtu_minggu,
            'stats' => $this->stats,
            'app_logo' => $this->app_logo,
            'app_logo_sekunder' => $this->app_logo_sekunder
        ])->layout('layouts.app');
    }

    public function formatNumber($val)
    {
        if (!is_numeric($val)) return $val;
        if ($val >= 1000) {
            $formatted = number_format($val / 1000, 1, ',', '.');
            return str_replace(',0', '', $formatted) . 'rb';
        }
        return number_format($val, 0, ',', '.');
    }
}
