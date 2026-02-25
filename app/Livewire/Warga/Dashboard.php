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