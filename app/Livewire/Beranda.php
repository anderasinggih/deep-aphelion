<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\PengaduanDukungan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Beranda extends Component
{
    use WithPagination;

    public $kategori_id = '';
    public $search = '';
    public $sort = 'terbaru';
    public $trackingCode = '';

    public function lacakLaporan()
    {
        $this->validate([
            'trackingCode' => 'required|string|max:50'
        ]);

        $pengaduan = Pengaduan::where('kode_tracking', $this->trackingCode)->first();

        if ($pengaduan) {
            return redirect()->route('pengaduan.feed-detail', $pengaduan->kode_tracking);
        } else {
            session()->flash('error', 'Laporan dengan kode ' . $this->trackingCode . ' tidak ditemukan.');
        }
    }

    public function upvote($pengaduan_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'warga') {
            session()->flash('error', 'Silakan login sebagai warga untuk memberikan dukungan.');
            return;
        }

        $userId = Auth::id();
        $existing = PengaduanDukungan::query()->where('pengaduan_id', $pengaduan_id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete(); // Toggle (Cancel upvote)
            session()->flash('success', 'Dukungan dibatalkan.');
        }
        else {
            PengaduanDukungan::create([
                'pengaduan_id' => $pengaduan_id,
                'user_id' => $userId
            ]);
            session()->flash('success', 'Terima kasih atas dukungan Anda!');
        }
    }

    public function render()
    {
        $query = Pengaduan::query()
            ->with(['user', 'kategori', 'dukungans'])
            ->withCount('dukungans')
            ->where('is_private', false)
            ->where('status', '!=', 'ditolak');

        if ($this->kategori_id) {
            $query->where('kategori_id', $this->kategori_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sort === 'terpopuler') {
            $query->orderBy('dukungans_count', 'desc');
        }
        else {
            $query->orderBy('created_at', 'desc');
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        return view('livewire.beranda', [
            'pengaduans' => $query->paginate(10),
            'kategoris' => Kategori::all(),
            'settings' => $settings,
            'sop_waktu_pemrosesan' => $settings['sop_waktu_pemrosesan'] ?? 'Laporan akan diverifikasi maksimal 3x24 Jam Kerja sejak dikirimkan.',
            'sop_jam_operasional' => $settings['sop_jam_operasional'] ?? 'Senin - Jumat <br> Pukul 08:00 - 15:00 WIB.',
            'sop_dasar_hukum' => $settings['sop_dasar_hukum'] ?? 'Sesuai UU Pelayanan Publik dan UU PDP. Identitas Anda dijamin aman.',
            'sop_tindak_lanjut' => $settings['sop_tindak_lanjut'] ?? 'Laporan yang valid akan langsung diteruskan ke instansi terkait untuk diselesaikan.',
        ])->layout('layouts.app');
    }
}