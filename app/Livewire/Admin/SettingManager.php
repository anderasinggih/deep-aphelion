<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;

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
    public $app_banner_1;
    public $app_banner_2;
    public $app_banner_3;
    public $existing_app_logo;
    public $existing_app_logo_sekunder;
    public $existing_app_banner_1;
    public $existing_app_banner_2;
    public $existing_app_banner_3;

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
    public $mail_from_name;
    
    // Notification Settings
    public $notif_email_penerima;
    public $maintenance_mode = false;
    
    // UI State
    #[Url]
    public $activeTab = 'umum';
    public $unlock_email = false;
    public $showUnlockModal = false;
    public $confirmText = '';
    public $showSaveEmailModal = false;
    public $saveConfirmText = '';

    // System Health Data
    public $systemInfo = [];
    public $latestLogs = '';

    public function mount()
    {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'admin']), 403);

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
        $this->existing_app_banner_1 = $settings['app_banner_1'] ?? null;
        $this->existing_app_banner_2 = $settings['app_banner_2'] ?? null;
        $this->existing_app_banner_3 = $settings['app_banner_3'] ?? null;

        $this->mail_host = $settings['mail_host'] ?? config('mail.mailers.smtp.host');
        $this->mail_port = $settings['mail_port'] ?? config('mail.mailers.smtp.port');
        $this->mail_username = $settings['mail_username'] ?? config('mail.mailers.smtp.username');
        $this->mail_password = $settings['mail_password'] ?? config('mail.mailers.smtp.password');
        $this->mail_encryption = $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption');
        $this->mail_from_name = $settings['mail_from_name'] ?? config('mail.from.name');

        $this->notif_email_penerima = $settings['notif_email_penerima'] ?? '';
        $this->maintenance_mode = (bool)($settings['maintenance_mode'] ?? false);

        if (auth()->user()->role === 'superadmin') {
            $this->loadSystemInfo();
        }
    }
    public function updatedMaintenanceMode($value)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        
        \App\Models\Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => $value ? '1' : '0']
        );

        $status = $value ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Mode Perbaikan berhasil {$status}.");
    }

    public function loadSystemInfo()
    {
        // Disk Usage
        try {
            $total = disk_total_space(base_path());
            $free = disk_free_space(base_path());
            $used = $total - $free;
            $percent = ($total > 0) ? ($used / $total) * 100 : 0;

            $this->systemInfo = [
                'disk_total' => $this->formatBytes($total),
                'disk_free' => $this->formatBytes($free),
                'disk_used' => $this->formatBytes($used),
                'disk_percent' => round($percent, 1),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'os' => PHP_OS,
            ];
        } catch (\Exception $e) {
            $this->systemInfo = [
                'disk_total' => 'N/A',
                'disk_free' => 'N/A',
                'disk_used' => 'N/A',
                'disk_percent' => 0,
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'os' => PHP_OS,
            ];
        }

        // Load Logs (Last 50 lines)
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            // Using file_get_contents and array_slice as a safer alternative to shell_exec for logs
            $logLines = file($logPath);
            $lastLines = array_slice($logLines, -50);
            $this->latestLogs = implode("", $lastLines) ?: 'Log kosong atau tidak terbaca.';
        } else {
            $this->latestLogs = 'File log tidak ditemukan.';
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function deleteBanner($index)
    {
        $key = 'app_banner_' . $index;
        $this->updateSetting($key, null);
        
        $prop = 'existing_app_banner_' . $index;
        $this->$prop = null;
        
        $inputProp = 'app_banner_' . $index;
        $this->$inputProp = null;
        
        session()->flash('success', 'Banner ' . $index . ' berhasil dihapus.');
    }

    public function deleteSignature()
    {
        $this->updateSetting('ttd_file', null);
        $this->existing_ttd_file = null;
        $this->ttd_file = null;
        session()->flash('success', 'Tanda tangan berhasil dihapus.');
    }

    public function clearCache()
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            
            session()->flash('success', 'Seluruh cache sistem (Config, Cache, Route, View) berhasil dibersihkan.');
        } catch (\Exception $e) {
            \Log::error('Gagal membersihkan cache: ' . $e->getMessage());
            session()->flash('error', 'Gagal membersihkan cache sistem.');
        }
    }

    public function saveNotifSettings()
    {
        $this->validate([
            'notif_email_penerima' => 'nullable|string|max:1000',
        ]);

        $this->updateSetting('notif_email_penerima', $this->notif_email_penerima);
        session()->flash('success', 'Daftar email notifikasi berhasil diperbarui.');
    }

    public function saveSettings()
    {
        // Restricted for superadmin only
        $restrictedTabs = ['email', 'sistem'];
        if (in_array($this->activeTab, $restrictedTabs) && auth()->user()->role !== 'superadmin') {
            session()->flash('error', 'Hanya Superadmin yang dapat mengubah pengaturan ini.');
            return;
        }

        // If email is unlocked, we need extra confirmation to save
        if ($this->unlock_email && strtoupper($this->saveConfirmText) !== 'SIMPAN PERUBAHAN') {
            $this->saveConfirmText = '';
            $this->showSaveEmailModal = true;
            return;
        }

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
            'app_banner_1' => 'nullable|image|max:5120',
            'app_banner_2' => 'nullable|image|max:5120',
            'app_banner_3' => 'nullable|image|max:5120',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:10',
            'mail_from_name' => 'nullable|string|max:255',
            'notif_email_penerima' => 'nullable|string|max:1000',
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
                'MAIL_FROM_ADDRESS' => $this->mail_username, 
                'MAIL_FROM_NAME' => $this->mail_from_name,
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

        if ($this->app_banner_1) {
            $path = $this->app_banner_1->store('assets', 'public');
            $this->updateSetting('app_banner_1', $path);
            $this->existing_app_banner_1 = $path;
            $this->app_banner_1 = null;
        }

        if ($this->app_banner_2) {
            $path = $this->app_banner_2->store('assets', 'public');
            $this->updateSetting('app_banner_2', $path);
            $this->existing_app_banner_2 = $path;
            $this->app_banner_2 = null;
        }

        if ($this->app_banner_3) {
            $path = $this->app_banner_3->store('assets', 'public');
            $this->updateSetting('app_banner_3', $path);
            $this->existing_app_banner_3 = $path;
            $this->app_banner_3 = null;
        }

        session()->flash('success', 'Pengaturan berhasil disimpan.');
        
        // Reset save confirmation and lock again for safety
        $this->saveConfirmText = '';
        $this->showSaveEmailModal = false;
        $this->unlock_email = false;
    }

    public function openUnlockModal()
    {
        if ($this->unlock_email) {
            $this->unlock_email = false;
        } else {
            $this->confirmText = '';
            $this->showUnlockModal = true;
        }
    }

    public function confirmUnlock()
    {
        if (strtoupper($this->confirmText) === 'SAYA MENGERTI') {
            $this->unlock_email = true;
            $this->showUnlockModal = false;
        } else {
            $this->addError('confirmText', 'Teks konfirmasi tidak sesuai.');
        }
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
