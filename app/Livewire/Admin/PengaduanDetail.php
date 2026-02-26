<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;
use App\Models\User;

class PengaduanDetail extends Component
{
    use WithFileUploads;

    public Pengaduan $pengaduan;

    public $disposisiModal = false;
    public $petugas_id = '';
    public $disposisi_notes = '';

    public $selesaiModal = false;
    public $foto_bukti_selesai;
    public $keterangan_selesai = '';

    public function mount($id)
    {
        $this->pengaduan = Pengaduan::with(['user', 'kategori', 'petugas'])->findOrFail($id);
    }

    public function openDisposisi()
    {
        $this->petugas_id = $this->pengaduan->petugas_id ?? '';
        $this->disposisiModal = true;
    }

    public function changeStatus($newStatus)
    {
        if ($newStatus === 'selesai') {
            $this->selesaiModal = true;
            return;
        }
        // Prevent if 'diproses' but no petugas assigned yet
        if ($newStatus === 'diproses' && !$this->pengaduan->petugas_id) {
            session()->flash('error', 'Pilih petugas untuk disposisi terlebih dahulu sebelum mengubah status menjadi Diproses.');
            $this->pengaduan->refresh(); // Revert selcet state
            $this->openDisposisi();
            return;
        }

        $oldStatus = $this->pengaduan->getOriginal('status') ?? $this->pengaduan->status;
        $this->pengaduan->status = $newStatus;
        $this->pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $newStatus,
            'keterangan_admin' => "Status diubah menjadi: " . strtoupper($newStatus),
        ]);

        session()->flash('success', "Status laporan berhasil diubah menjadi {$newStatus}.");
        $this->pengaduan->refresh();
    }

    public function saveDisposisi()
    {
        $this->validate([
            'petugas_id' => 'required|exists:users,id',
            'disposisi_notes' => 'nullable|string'
        ]);

        $this->pengaduan->petugas_id = $this->petugas_id;

        // Auto change status if waiting
        $oldStatus = $this->pengaduan->status;
        if ($oldStatus === 'menunggu') {
            $this->pengaduan->status = 'diproses';
        }
        $this->pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $this->pengaduan->status,
            'keterangan_admin' => "Disposisi kepada petugas ID {$this->petugas_id}. Catatan: " . $this->disposisi_notes,
        ]);

        $this->disposisiModal = false;
        $this->disposisi_notes = '';
        session()->flash('success', 'Disposisi berhasil disimpan dan laporan diproses.');
        $this->pengaduan->refresh();
    }

    public function markSelesai()
    {
        $this->validate([
            'foto_bukti_selesai' => 'required|image|max:5120',
            'keterangan_selesai' => 'required|string|min:10',
        ]);

        $path = $this->foto_bukti_selesai->store('bukti_selesai', 'public');
        $oldStatus = $this->pengaduan->getOriginal('status') ?? $this->pengaduan->status;

        $this->pengaduan->status = 'selesai';
        $this->pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'selesai',
            'keterangan_admin' => $this->keterangan_selesai,
            'foto_bukti' => $path,
        ]);

        $this->reset('selesaiModal', 'foto_bukti_selesai', 'keterangan_selesai');
        session()->flash('success', 'Laporan berhasil diselesaikan beserta bukti foto terlampir.');
        $this->pengaduan->refresh();
    }

    public function render()
    {
        return view('livewire.admin.pengaduan-detail', [
            'list_petugas' => User::query()->where('role', 'petugas')->get()
        ])->layout('layouts.app');
    }
}