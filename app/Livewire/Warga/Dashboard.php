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
    public $rating_value = 5;
    public $rating_komentar = '';

    public function openRatingModal($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->where('user_id', Auth::id())->first();
        if ($pengaduan && $pengaduan->status === 'selesai' && !$pengaduan->rating) {
            $this->selectedPengaduanId = $pengaduan->id;
            $this->rating_value = 5; // default 5 stars
            $this->rating_komentar = '';
            $this->ratingModal = true;
        }
    }

    public function saveRating()
    {
        $this->validate([
            'rating_value' => 'required|integer|min:1|max:5',
            'rating_komentar' => 'nullable|string|max:500',
        ]);

        $pengaduan = Pengaduan::where('id', $this->selectedPengaduanId)
            ->where('user_id', Auth::id())
            ->first();

        if ($pengaduan && $pengaduan->status === 'selesai') {
            $pengaduan->update([
                'rating' => $this->rating_value,
                'rating_komentar' => $this->rating_komentar
            ]);

            $this->reset(['ratingModal', 'selectedPengaduanId', 'rating_value', 'rating_komentar']);
            session()->flash('success', 'Terima kasih atas penilaian Anda!');
        }
    }

    public function render()
    {
        $pengaduans = Auth::user()->pengaduans()
            ->with('kategori')
            ->latest()
            ->paginate(5);

        return view('livewire.warga.dashboard', [
            'pengaduans' => $pengaduans
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