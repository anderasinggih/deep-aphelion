<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;

class PengaduanForm extends Component
{
    use WithFileUploads;

    public $judul;
    public $kategori_id;
    public $deskripsi;
    public $foto_bukti;
    public $lokasi_kejadian;
    public $latitude;
    public $longitude;
    public $is_anonymous = false;

    public $pengaduanId = null;
    public $isEdit = false;
    public $old_foto_bukti = null;

    protected $rules = [
        'judul' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'deskripsi' => 'required|string',
        'foto_bukti' => 'nullable|image|max:2048', // max 2MB
        'lokasi_kejadian' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'is_anonymous' => 'boolean',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $pengaduan = Pengaduan::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

            // Only allow edit if status is menunggu
            if ($pengaduan->status !== 'menunggu') {
                session()->flash('error', 'Laporan yang sudah diproses tidak dapat diubah.');
                return redirect()->route('dashboard');
            }

            $this->isEdit = true;
            $this->pengaduanId = $pengaduan->id;
            $this->judul = $pengaduan->judul;
            $this->kategori_id = $pengaduan->kategori_id;
            $this->deskripsi = $pengaduan->deskripsi;
            $this->lokasi_kejadian = $pengaduan->lokasi_kejadian;
            $this->latitude = $pengaduan->latitude;
            $this->longitude = $pengaduan->longitude;
            $this->is_anonymous = $pengaduan->is_anonymous;
            $this->old_foto_bukti = $pengaduan->foto_bukti;
        }
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();

        // Rate limiting only for creating new reports
        if (!$this->isEdit) {
            $rateLimitKey = 'pengaduan_' . $user->id;

            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                session()->flash('error', 'Rate limit exceeded! Anda hanya dapat mengirim 3 laporan per jam. Coba lagi nanti.');
                return;
            }

            RateLimiter::hit($rateLimitKey, 3600); // 1 hour
        }

        $path = $this->old_foto_bukti;
        if ($this->foto_bukti) {
            $path = $this->foto_bukti->store('pengaduans', 'public');
            // If editing and uploaded new photo, delete the old one
            if ($this->isEdit && $this->old_foto_bukti) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($this->old_foto_bukti);
            }
        }

        if ($this->isEdit) {
            $pengaduan = Pengaduan::find($this->pengaduanId);
            $pengaduan->update([
                'kategori_id' => $this->kategori_id,
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'foto_bukti' => $path,
                'lokasi_kejadian' => $this->lokasi_kejadian,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'is_anonymous' => $this->is_anonymous,
            ]);

            session()->flash('success', 'Laporan berhasil diperbarui.');
        }
        else {
            $pengaduan = Pengaduan::create([
                'user_id' => $user->id,
                'kategori_id' => $this->kategori_id,
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'foto_bukti' => $path,
                'lokasi_kejadian' => $this->lokasi_kejadian,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'is_anonymous' => $this->is_anonymous,
                'status' => 'menunggu',
            ]);

            PengaduanHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'user_id' => $user->id,
                'status_sebelumnya' => null,
                'status_baru' => 'menunggu',
                'keterangan_admin' => 'Laporan berhasil dibuat warga dan masuk ke antrean kecamatan.',
            ]);

            session()->flash('success', 'Laporan berhasil disubmit dan akan segera diperiksa.');
        }

        return redirect()->route('dashboard'); // redirected to Warga Dashboard later
    }

    public function render()
    {
        return view('livewire.warga.pengaduan-form', [
            'kategoris' => Kategori::all()
        ])->layout('layouts.app');
    }
}