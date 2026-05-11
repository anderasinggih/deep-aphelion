<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengaduan;

class Dashboard extends Component
{
    use WithPagination;

    public $ratingModal = false;
    public $selectedPengaduanId = null;
    public $rating_pelayanan = 5;
    public $rating_respon = 5;
    public $rating_kompetensi = 5;
    public $rating_fasilitas = 5;
    public $rating_komentar = '';
    public $search = '';
    public $orderBy = 'latest';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedOrderBy()
    {
        $this->resetPage();
    }

    public function openRatingModal($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->where('user_id', Auth::id())->first();
        if ($pengaduan && $pengaduan->status === 'selesai' && !$pengaduan->rating && auth()->user()->role === 'warga') {
            $this->selectedPengaduanId = $pengaduan->id;
            $this->rating_pelayanan = 5;
            $this->rating_respon = 5;
            $this->rating_kompetensi = 5;
            $this->rating_fasilitas = 5;
            $this->rating_komentar = '';
            $this->ratingModal = true;
        }
    }

    public function saveRating()
    {
        $this->validate([
            'rating_pelayanan' => 'required|integer|min:1|max:5',
            'rating_respon' => 'required|integer|min:1|max:5',
            'rating_kompetensi' => 'required|integer|min:1|max:5',
            'rating_fasilitas' => 'required|integer|min:1|max:5',
            'rating_komentar' => 'nullable|string|max:500',
        ]);

        $pengaduan = Pengaduan::where('id', $this->selectedPengaduanId)
            ->where('user_id', Auth::id())
            ->first();

        if ($pengaduan && $pengaduan->status === 'selesai') {
            $averageRating = round(($this->rating_pelayanan + $this->rating_respon + $this->rating_kompetensi + $this->rating_fasilitas) / 4);

            $pengaduan->update([
                'rating' => $averageRating,
                'rating_pelayanan' => $this->rating_pelayanan,
                'rating_respon' => $this->rating_respon,
                'rating_kompetensi' => $this->rating_kompetensi,
                'rating_fasilitas' => $this->rating_fasilitas,
                'rating_komentar' => $this->rating_komentar
            ]);

            $this->reset(['ratingModal', 'selectedPengaduanId', 'rating_pelayanan', 'rating_respon', 'rating_kompetensi', 'rating_fasilitas', 'rating_komentar']);
            session()->flash('success', 'Terima kasih atas penilaian Anda!');
        }
    }

    public function render()
    {
        $query = Auth::user()->pengaduans()
            ->with('kategori')
            ->when($this->search, function($q) {
                $q->where(function($sq) {
                    $sq->where('judul', 'like', '%' . $this->search . '%')
                       ->orWhere('kode_tracking', 'like', '%' . $this->search . '%');
                });
            });

        if ($this->orderBy === 'latest') {
            $query->latest();
        } elseif ($this->orderBy === 'oldest') {
            $query->oldest();
        }

        $pengaduans = $query->paginate(10);

        $isAdminView = request()->routeIs('admin.aduan-internal');
        
        return view('livewire.warga.dashboard', [
            'pengaduans' => $pengaduans,
            'viewTitle' => $isAdminView ? 'Aduan Internal' : 'Dashboard Warga',
            'viewSubtitle' => $isAdminView ? 'Pantau laporan pengaduan internal Anda' : 'Pantau laporan pengaduan yang telah Anda buat'
        ])->layout('layouts.app');
    }

    public function deletePengaduan($id)
    {
        $pengaduan = Pengaduan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pengaduan) {
            session()->flash('error', 'Laporan tidak ditemukan atau Anda tidak berhak menghapusnya.');
            return;
        }

        if ($pengaduan->status !== 'menunggu') {
            session()->flash('error', 'Laporan yang sudah diproses tidak dapat dihapus.');
            return;
        }

        // Hapus file foto jika ada
        if ($pengaduan->foto_bukti) {
            Storage::disk('public')->delete($pengaduan->foto_bukti);
        }

        // history otomatis terhapus karena on cascade delete (kalau diset di db), 
        // pastikan hapus history dulu if no cascade
        $pengaduan->histories()->delete();
        $pengaduan->delete();

        session()->flash('success', 'Laporan pengaduan berhasil dihapus.');
    }
}