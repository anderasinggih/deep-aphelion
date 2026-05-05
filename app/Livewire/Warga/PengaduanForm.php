<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PengaduanForm extends Component
{
    use WithFileUploads, Toast;

    public $judul;
    public $kategori_id;
    public $deskripsi;
    public $tanggal_kejadian;
    public $prioritas = 'sedang';
    public $harapan_pelapor;
    public $foto_bukti = [];
    public $lokasi_kejadian;
    public $latitude;
    public $longitude;
    public $is_anonymous = false;
    public $is_private = false;
    public $pernyataan = false;

    public $pengaduanId = null;
    public $isEdit = false;
    public $old_foto_bukti = [];

    // Success Modal Props
    public $showSuccessModal = false;
    public $lastSavedId = null;
    public $lastTrackingCode = null;

    protected $rules = [
        'judul' => 'required|string|max:100',
        'kategori_id' => 'required|exists:kategoris,id',
        'deskripsi' => 'required|string|max:2000',
        'tanggal_kejadian' => 'required|date|before_or_equal:today',
        'prioritas' => 'required|in:rendah,sedang,tinggi',
        'harapan_pelapor' => 'nullable|string|max:500',
        'foto_bukti.*' => 'image|max:10240',
        'lokasi_kejadian' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'is_anonymous' => 'boolean',
        'is_private' => 'boolean',
        'pernyataan' => 'accepted',
    ];

    public function mount($id = null, $kode_tracking = null)
    {
        // Set default tanggal hari ini jika baru
        if (!$id && !$kode_tracking) {
            $this->tanggal_kejadian = now()->format('Y-m-d');
        }

        if ($id || $kode_tracking) {
            $pengaduan = $id 
                ? Pengaduan::where('id', $id)->where('user_id', auth()->id())->firstOrFail()
                : Pengaduan::where('kode_tracking', $kode_tracking)->where('user_id', auth()->id())->firstOrFail();

            if ($pengaduan->status !== 'menunggu') {
                session()->flash('error', 'Laporan yang sudah diproses tidak dapat diubah.');
                return redirect()->route('dashboard');
            }

            $this->isEdit = true;
            $this->pengaduanId = $pengaduan->id;
            $this->judul = $pengaduan->judul;
            $this->kategori_id = $pengaduan->kategori_id;
            $this->deskripsi = $pengaduan->deskripsi;
            $this->tanggal_kejadian = $pengaduan->tanggal_kejadian ? $pengaduan->tanggal_kejadian->format('Y-m-d') : null;
            $this->prioritas = $pengaduan->prioritas ?? 'sedang';
            $this->harapan_pelapor = $pengaduan->harapan_pelapor;
            $this->lokasi_kejadian = $pengaduan->lokasi_kejadian;
            $this->latitude = $pengaduan->latitude;
            $this->longitude = $pengaduan->longitude;
            $this->is_anonymous = $pengaduan->is_anonymous;
            $this->is_private = $pengaduan->is_private;
            $this->old_foto_bukti = $pengaduan->foto_bukti ?? [];
        }
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();

        if (!$this->isEdit) {
            $settings = \App\Models\Setting::whereIn('key', ['anti_spam_aktif', 'anti_spam_limit'])->pluck('value', 'key');
            $antiSpamAktif = (bool) ($settings['anti_spam_aktif'] ?? true);
            $antiSpamLimit = (int) ($settings['anti_spam_limit'] ?? 3);

            if ($antiSpamAktif) {
                $rateLimitKey = 'pengaduan_' . $user->id;
                if (RateLimiter::tooManyAttempts($rateLimitKey, $antiSpamLimit)) {
                    $this->error("Anti-Spam Aktif: Batas maksimal {$antiSpamLimit} laporan per hari tercapai.", position: 'toast-top toast-center');
                    $this->dispatch('scroll-to-top');
                    return;
                }
                RateLimiter::hit($rateLimitKey, 86400);
            }
        }

        $paths = $this->old_foto_bukti;
        
        if (!empty($this->foto_bukti)) {
            $manager = new ImageManager(new Driver());
            foreach ($this->foto_bukti as $foto) {
                if (count($paths) >= 4) break;

                $image = $manager->read($foto->getRealPath());
                $image->scale(width: 1920);
                
                $filename = 'pengaduans/' . $foto->hashName();
                $encoded = $image->toJpeg(75);
                
                Storage::disk('public')->put($filename, (string) $encoded);
                $paths[] = $filename;
            }
        }

        $data = [
            'kategori_id' => $this->kategori_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'tanggal_kejadian' => $this->tanggal_kejadian,
            'prioritas' => $this->prioritas,
            'harapan_pelapor' => $this->harapan_pelapor,
            'foto_bukti' => $paths,
            'lokasi_kejadian' => $this->lokasi_kejadian,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_anonymous' => $this->is_anonymous,
            'is_private' => $this->is_private,
        ];

        if ($this->isEdit) {
            $pengaduan = Pengaduan::find($this->pengaduanId);
            $pengaduan->update($data);
            session()->flash('success', 'Laporan berhasil diperbarui.');
        }
        else {
            $data['user_id'] = $user->id;
            $data['status'] = 'menunggu';
            $pengaduan = Pengaduan::create($data);

            // Generate kode resmi: PKM-KBR/001/V/2025
            $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
            $bulan = $bulanRomawi[now()->month - 1];
            $tahun = now()->year;
            $nomorUrut = Pengaduan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', $tahun)
                ->count();
            $pengaduan->kode_tracking = 'PKM-KBR/' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT) . '/' . $bulan . '/' . $tahun;
            $pengaduan->save();

            PengaduanHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'user_id' => $user->id,
                'status_sebelumnya' => null,
                'status_baru' => 'menunggu',
                'keterangan_admin' => 'Laporan berhasil dibuat warga dan masuk ke antrean kecamatan.',
            ]);

            session()->flash('success', 'Laporan berhasil disubmit. Kode Tracking Anda: ' . $pengaduan->kode_tracking);
            
            $this->lastSavedId = $pengaduan->id;
            $this->lastTrackingCode = $pengaduan->kode_tracking;
            $this->showSuccessModal = true;
            return;
        }

        return redirect()->route('dashboard');
    }

    public function reverseGeocode($lat, $lng)
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'KembaranNgadu/1.0 (admin@kembaran.go.id)'
            ])->get("https://nominatim.openstreetmap.org/reverse", [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lng,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['display_name'])) {
                    $this->lokasi_kejadian = $data['display_name'];
                }
            } else {
                \Log::warning('Nominatim Geocode Failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            \Log::error('Reverse Geocode Error: ' . $e->getMessage());
        }
    }

    public function searchLocation($query)
    {
        if (empty($query)) return;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'KembaranNgadu/1.0 (admin@kembaran.go.id)'
            ])->get("https://nominatim.openstreetmap.org/search", [
                'format' => 'json',
                'q' => $query . ' Banyumas',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data)) {
                    $this->latitude = (float) $data[0]['lat'];
                    $this->longitude = (float) $data[0]['lon'];
                    $this->lokasi_kejadian = $data[0]['display_name'];
                    
                    // Dispatch event to update map
                    $this->dispatch('location-updated', 
                        lat: $this->latitude, 
                        lng: $this->longitude
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('Search Location Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.warga.pengaduan-form', [
            'kategoris' => Kategori::all()
        ])->layout('layouts.app');
    }
}