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

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        $rateLimitKey = 'pengaduan_' . $user->id;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            session()->flash('error', 'Rate limit exceeded! Anda hanya dapat mengirim 3 laporan per jam. Coba lagi nanti.');
            return;
        }

        RateLimiter::hit($rateLimitKey, 3600); // 1 hour

        $path = $this->foto_bukti ? $this->foto_bukti->store('pengaduans', 'public') : null;

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
            'user_id' => $user->id, // sistem otomatis atau user saat itu
            'status_sebelumnya' => null,
            'status_baru' => 'menunggu',
            'keterangan_admin' => 'Laporan berhasil dibuat warga dan masuk ke antrean kecamatan.',
        ]);

        session()->flash('message', 'Laporan berhasil disubmit dan akan segera diperiksa.');
        return redirect()->route('dashboard'); // redirected to Warga Dashboard later
    }

    public function render()
    {
        return view('livewire.warga.pengaduan-form', [
            'kategoris' => Kategori::all()
        ])->layout('layouts.app');
    }
}