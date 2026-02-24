<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;

class Disposisi extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    // Selesai Modal State
    public $selesaiModal = false;
    public $selectedPengaduanId = null;
    public $foto_bukti_selesai;
    public $keterangan_selesai = '';

    public function openSelesaiModal($id)
    {
        $this->selectedPengaduanId = $id;
        $this->selesaiModal = true;
    }

    public function markSelesai()
    {
        $this->validate([
            'foto_bukti_selesai' => 'required|image|max:2048', // Wajib untuk bukti selesai
            'keterangan_selesai' => 'required|string|min:10',
        ]);

        $pengaduan = Pengaduan::where('petugas_id', auth()->id())->findOrFail($this->selectedPengaduanId);

        $path = $this->foto_bukti_selesai->store('bukti_selesai', 'public');

        $oldStatus = $pengaduan->status;
        $pengaduan->status = 'selesai';
        $pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'selesai',
            'keterangan_admin' => $this->keterangan_selesai,
            'foto_bukti' => $path, // Foto after (Bukti Selesai)
        ]);

        $this->reset('selesaiModal', 'foto_bukti_selesai', 'keterangan_selesai', 'selectedPengaduanId');
        session()->flash('success', 'Laporan berhasil diselesaikan beserta bukti foto terlampir.');
    }

    public function processReport($id)
    {
        $pengaduan = Pengaduan::where('petugas_id', auth()->id())->findOrFail($id);

        if ($pengaduan->status !== 'diproses') {
            $oldStatus = $pengaduan->status;
            $pengaduan->status = 'diproses';
            $pengaduan->save();

            PengaduanHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'user_id' => auth()->id(),
                'status_sebelumnya' => $oldStatus,
                'status_baru' => 'diproses',
                'keterangan_admin' => 'Petugas mulai menindaklanjuti laporan di lapangan.',
            ]);

            session()->flash('success', 'Status diubah ke diproses.');
        }
    }

    public function render()
    {
        $query = Pengaduan::query()
            ->with(['user', 'kategori'])
            ->where('petugas_id', auth()->id())
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('lokasi_kejadian', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.petugas.disposisi', [
            'pengaduans' => $query->paginate(10)
        ])->layout('layouts.app');
    }
}