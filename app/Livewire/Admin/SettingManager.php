<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;

class SettingManager extends Component
{
    use WithFileUploads;

    public $sop_waktu_pemrosesan;
    public $sop_jam_operasional;
    public $sop_dasar_hukum;
    public $sop_tindak_lanjut;
    
    // Pengumuman (Papan Informasi)
    public $pengumuman_aktif;
    public $pengumuman_isi;
    public $pengumuman_tipe;

    // Anti-Spam
    public $anti_spam_aktif;
    public $anti_spam_limit;

    // Cleanup Media
    public $media_cleanup_aktif;
    public $media_cleanup_bulan;

    // Tanda Tangan
    public $ttd_jabatan;
    public $ttd_nama;
    public $ttd_file;
    public $existing_ttd_file;

    // Aset Visual (Logo & Banner)
    public $app_logo;
    public $app_logo_sekunder;
    public $app_banner;
    public $existing_app_logo;
    public $existing_app_logo_sekunder;
    public $existing_app_banner;

    // Content Settings
    public $instansi_nama;
    public $instansi_alamat;
    public $instansi_telepon;
    public $instansi_email;
    public $instansi_jam_senkam;
    public $instansi_jam_jumat;
    public $instansi_jam_sabtu;
    
    // Mail Settings
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    
    // UI State
    public $activeTab = 'umum';
    public $unlock_email = false;

    public function mount()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        $this->sop_waktu_pemrosesan = $settings['sop_waktu_pemrosesan'] ?? '';
        $this->sop_jam_operasional = $settings['sop_jam_operasional'] ?? '';
        $this->sop_dasar_hukum = $settings['sop_dasar_hukum'] ?? '';
        $this->sop_tindak_lanjut = $settings['sop_tindak_lanjut'] ?? '';
        
        $this->pengumuman_aktif = (bool) ($settings['pengumuman_aktif'] ?? false);
        $this->pengumuman_isi = $settings['pengumuman_isi'] ?? '';
        $this->pengumuman_tipe = $settings['pengumuman_tipe'] ?? 'info';

        $this->anti_spam_aktif = (bool) ($settings['anti_spam_aktif'] ?? true);
        $this->anti_spam_limit = $settings['anti_spam_limit'] ?? 3;

        $this->media_cleanup_aktif = (bool) ($settings['media_cleanup_aktif'] ?? false);
        $this->media_cleanup_bulan = $settings['media_cleanup_bulan'] ?? 24;

        $this->ttd_jabatan = $settings['ttd_jabatan'] ?? 'Camat Kembaran';
        $this->ttd_nama = $settings['ttd_nama'] ?? '';
        $this->existing_ttd_file = $settings['ttd_file'] ?? null;

        $this->instansi_nama = $settings['instansi_nama'] ?? 'Kantor Kecamatan Kembaran';
        $this->instansi_alamat = $settings['instansi_alamat'] ?? 'Jl. Kyai Kembar No. 17, Kembaran, Kabupaten Banyumas, Jawa Tengah 53182';
        $this->instansi_telepon = $settings['instansi_telepon'] ?? '(0281) 6840XXX';
        $this->instansi_email = $settings['instansi_email'] ?? 'kecamatan.kembaran@banyumaskab.go.id';
        $this->instansi_jam_senkam = $settings['instansi_jam_senkam'] ?? '07.30 – 16.00 WIB';
        $this->instansi_jam_jumat = $settings['instansi_jam_jumat'] ?? '07.30 – 11.00 WIB';
        $this->instansi_jam_sabtu = $settings['instansi_jam_sabtu'] ?? 'Libur';

        $this->existing_app_logo = $settings['app_logo'] ?? null;
        $this->existing_app_logo_sekunder = $settings['app_logo_sekunder'] ?? null;
        $this->existing_app_banner = $settings['app_banner'] ?? null;

        $this->mail_host = $settings['mail_host'] ?? config('mail.mailers.smtp.host');
        $this->mail_port = $settings['mail_port'] ?? config('mail.mailers.smtp.port');
        $this->mail_username = $settings['mail_username'] ?? config('mail.mailers.smtp.username');
        $this->mail_password = $settings['mail_password'] ?? config('mail.mailers.smtp.password');
        $this->mail_encryption = $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption');
    }

    public function saveSettings()
    {
        $this->validate([
            'sop_waktu_pemrosesan' => 'required|string',
            'sop_jam_operasional' => 'required|string',
            'sop_dasar_hukum' => 'required|string',
            'sop_tindak_lanjut' => 'required|string',
            'pengumuman_aktif' => 'boolean',
            'pengumuman_isi' => 'nullable|string|max:1000',
            'pengumuman_tipe' => 'required|in:info,success,warning,error',
            'anti_spam_aktif' => 'boolean',
            'anti_spam_limit' => 'required|integer|min:1|max:100',
            'media_cleanup_aktif' => 'boolean',
            'media_cleanup_bulan' => 'required|integer|min:1|max:120',
            'ttd_jabatan' => 'required|string|max:100',
            'ttd_nama' => 'required|string|max:100',
            'ttd_file' => 'nullable|image|max:2048',
            'instansi_nama' => 'required|string|max:255',
            'instansi_alamat' => 'required|string|max:500',
            'instansi_telepon' => 'required|string|max:50',
            'instansi_email' => 'required|email|max:100',
            'instansi_jam_senkam' => 'required|string|max:100',
            'instansi_jam_jumat' => 'required|string|max:100',
            'instansi_jam_sabtu' => 'required|string|max:100',
            'app_logo' => 'nullable|image|max:2048',
            'app_logo_sekunder' => 'nullable|image|max:2048',
            'app_banner' => 'nullable|image|max:5120',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:10',
        ]);

        $this->updateSetting('sop_waktu_pemrosesan', $this->sop_waktu_pemrosesan);
        $this->updateSetting('sop_jam_operasional', $this->sop_jam_operasional);
        $this->updateSetting('sop_dasar_hukum', $this->sop_dasar_hukum);
        $this->updateSetting('sop_tindak_lanjut', $this->sop_tindak_lanjut);
        $this->updateSetting('pengumuman_aktif', $this->pengumuman_aktif);
        $this->updateSetting('pengumuman_isi', $this->pengumuman_isi);
        $this->updateSetting('pengumuman_tipe', $this->pengumuman_tipe);
        $this->updateSetting('anti_spam_aktif', $this->anti_spam_aktif);
        $this->updateSetting('anti_spam_limit', $this->anti_spam_limit);
        $this->updateSetting('media_cleanup_aktif', $this->media_cleanup_aktif);
        $this->updateSetting('media_cleanup_bulan', $this->media_cleanup_bulan);
        $this->updateSetting('ttd_jabatan', $this->ttd_jabatan);
        $this->updateSetting('ttd_nama', $this->ttd_nama);
        $this->updateSetting('instansi_nama', $this->instansi_nama);
        $this->updateSetting('instansi_alamat', $this->instansi_alamat);
        $this->updateSetting('instansi_telepon', $this->instansi_telepon);
        $this->updateSetting('instansi_email', $this->instansi_email);
        $this->updateSetting('instansi_jam_senkam', $this->instansi_jam_senkam);
        $this->updateSetting('instansi_jam_jumat', $this->instansi_jam_jumat);
        $this->updateSetting('instansi_jam_sabtu', $this->instansi_jam_sabtu);
        $this->updateSetting('mail_host', $this->mail_host);
        $this->updateSetting('mail_port', $this->mail_port);
        $this->updateSetting('mail_username', $this->mail_username);
        $this->updateSetting('mail_password', $this->mail_password);
        $this->updateSetting('mail_encryption', $this->mail_encryption);

        // Update .env file for mail settings ONLY if email tab was unlocked
        if ($this->unlock_email) {
            $this->updateEnv([
                'MAIL_HOST' => $this->mail_host,
                'MAIL_PORT' => $this->mail_port,
                'MAIL_USERNAME' => $this->mail_username,
                'MAIL_PASSWORD' => $this->mail_password,
                'MAIL_ENCRYPTION' => $this->mail_encryption,
                'MAIL_FROM_ADDRESS' => $this->mail_username, // Usually same as username
                'MAIL_FROM_NAME' => $this->instansi_nama,
            ]);
        }

        if ($this->ttd_file) {
            $path = $this->ttd_file->store('assets', 'public');
            $this->updateSetting('ttd_file', $path);
            $this->existing_ttd_file = $path;
            $this->ttd_file = null;
        }

        if ($this->app_logo) {
            $path = $this->app_logo->store('assets', 'public');
            $this->updateSetting('app_logo', $path);
            $this->existing_app_logo = $path;
            $this->app_logo = null;
        }

        if ($this->app_logo_sekunder) {
            $path = $this->app_logo_sekunder->store('assets', 'public');
            $this->updateSetting('app_logo_sekunder', $path);
            $this->existing_app_logo_sekunder = $path;
            $this->app_logo_sekunder = null;
        }

        if ($this->app_banner) {
            $path = $this->app_banner->store('assets', 'public');
            $this->updateSetting('app_banner', $path);
            $this->existing_app_banner = $path;
            $this->app_banner = null;
        }

        session()->flash('success', 'Pengaturan berhasil disimpan.');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function updateSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    private function updateEnv($data = [])
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $env = file_get_contents($path);

            foreach ($data as $key => $value) {
                $value = trim($value);
                // If value contains spaces, wrap it in quotes
                if (preg_match('/\s/', $value)) {
                    $value = '"' . $value . '"';
                }

                $keyPattern = "/^{$key}=.*/m";

                if (preg_match($keyPattern, $env)) {
                    $env = preg_replace($keyPattern, "{$key}={$value}", $env);
                } else {
                    $env .= "\n{$key}={$value}";
                }
            }

            file_put_contents($path, $env);
            
            // Reload the environment variables for the current request
            \Illuminate\Support\Facades\Artisan::call('config:clear');
        }
    }

    public function render()
    {
        return view('livewire.admin.setting-manager')->layout('layouts.app');
    }
}
