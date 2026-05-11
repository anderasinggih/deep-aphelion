<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\PengaduanDukungan;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

class Beranda extends Component
{
    use WithPagination, Toast;

    public $kategori_id = '';
    public $search = '';
    public $sort = 'terbaru';
    public $trackingCode = '';
    
    #[Url]
    public $viewMode = 'grid';

    public function updatedTrackingCode($value)
    {
        // 1. Force Uppercase
        $value = strtoupper($value);
        
        // 2. Allow only A-Z, 0-9, hyphen (-), and slash (/)
        $value = preg_replace('/[^A-Z0-9-\/]/', '', $value);
        
        // 3. Limit to 25 characters (Scalable for millions of reports)
        $this->trackingCode = substr($value, 0, 25);
    }

    public function lacakLaporan()
    {
        $this->validate([
            'trackingCode' => 'required|string|min:10|max:25'
        ], [
            'trackingCode.min' => 'Kode tracking terlalu pendek.',
            'trackingCode.max' => 'Kode tracking terlalu panjang.'
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
        $pengaduan = Pengaduan::find($pengaduan_id);

        if (!$pengaduan) {
            return;
        }

        // 1. Anti-Cheat: Prevent self-support
        if ($pengaduan->user_id === $userId) {
            $this->error('Anda tidak dapat memberikan dukungan pada laporan sendiri.');
            return;
        }

        // 2. Status Check: Only active reports can be supported
        if (!in_array($pengaduan->status, ['menunggu', 'diproses'])) {
            $this->error('Dukungan hanya dapat diberikan pada laporan yang sedang aktif.');
            return;
        }

        $existing = PengaduanDukungan::query()->where('pengaduan_id', $pengaduan_id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete(); // Toggle (Cancel upvote)
            $this->success('Dukungan dibatalkan.');
        }
        else {
            PengaduanDukungan::create([
                'pengaduan_id' => $pengaduan_id,
                'user_id' => $userId
            ]);
            $this->success('Terima kasih atas dukungan Anda!');
        }

        // Auto-priority logic: > 50 upvotes automatic HIGH priority
        $count = $pengaduan->dukungans()->count();
        if ($count >= 50 && $pengaduan->prioritas !== 'tinggi' && in_array($pengaduan->status, ['menunggu', 'diproses'])) {
            $pengaduan->update(['prioritas' => 'tinggi']);
        }
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedKategoriId() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }

    public function render()
    {
        $query = Pengaduan::query()
            ->select('id', 'user_id', 'kategori_id', 'judul', 'deskripsi', 'status', 'foto_bukti', 'kode_tracking', 'is_anonymous', 'lokasi_kejadian', 'created_at')
            ->with([
                'user:id,name', 
                'kategori:id,nama'
            ])
            ->withCount('dukungans')
            ->withExists(['dukungans as has_liked' => function($q) {
                $q->where('user_id', auth()->id());
            }])
            ->where('is_private', false)
            ->where('status', '!=', 'ditolak');

        if ($this->kategori_id) {
            $query->where('kategori_id', $this->kategori_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('lokasi_kejadian', 'like', '%' . $this->search . '%');
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