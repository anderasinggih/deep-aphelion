<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;
use App\Models\User;

class PengaduanManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = '';

    // Disposisi Modal State
    public $disposisiModal = false;
    public $selectedPengaduanId = null;
    public $petugas_id = '';
    public $disposisi_notes = '';

    // Selesai Modal State
    public $selesaiModal = false;
    public $foto_bukti_selesai;
    public $keterangan_selesai = '';

    public function openDisposisi($id)
    {
        $this->selectedPengaduanId = $id;
        $pengaduan = Pengaduan::find($id);
        $this->petugas_id = $pengaduan->petugas_id ?? '';
        $this->disposisiModal = true;
    }

    public function setStatus($id, $newStatus)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        // Prevent if 'diproses' but no petugas assigned yet
        if ($newStatus === 'diproses' && !$pengaduan->petugas_id) {
            session()->flash('error', 'Pilih petugas untuk disposisi terlebih dahulu sebelum mengubah status menjadi Diproses.');
            $this->openDisposisi($id);
            return;
        }

        $oldStatus = $pengaduan->status;
        $pengaduan->status = $newStatus;
        $pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $newStatus,
            'keterangan_admin' => "Status diubah menjadi: " . strtoupper($newStatus),
        ]);

        session()->flash('success', "Status laporan berhasil diubah menjadi {$newStatus}.");
    }

    public function saveDisposisi()
    {
        $this->validate([
            'petugas_id' => 'required|exists:users,id',
            'disposisi_notes' => 'nullable|string'
        ]);

        $pengaduan = Pengaduan::findOrFail($this->selectedPengaduanId);
        $pengaduan->petugas_id = $this->petugas_id;

        // Auto change status if waiting
        $oldStatus = $pengaduan->status;
        if ($oldStatus === 'menunggu') {
            $pengaduan->status = 'diproses';
        }
        $pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $pengaduan->status,
            'keterangan_admin' => "Disposisi kepada petugas ID {$this->petugas_id}. Catatan: " . $this->disposisi_notes,
        ]);

        $this->disposisiModal = false;
        $this->disposisi_notes = '';
        session()->flash('success', 'Disposisi berhasil disimpan dan laporan diproses.');
    }

    public function openSelesaiModal($id)
    {
        $this->selectedPengaduanId = $id;
        $this->selesaiModal = true;
    }

    public function markSelesai()
    {
        $this->validate([
            'foto_bukti_selesai' => 'required|image|max:5120',
            'keterangan_selesai' => 'required|string|min:10',
            'selectedPengaduanId' => 'required|exists:pengaduans,id'
        ]);

        $pengaduan = Pengaduan::findOrFail($this->selectedPengaduanId);
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
            'foto_bukti' => $path,
        ]);

        $this->reset('selesaiModal', 'foto_bukti_selesai', 'keterangan_selesai', 'selectedPengaduanId');
        session()->flash('success', 'Laporan berhasil diselesaikan beserta bukti foto terlampir.');
    }

    public function render()
    {
        $query = Pengaduan::with(['user', 'petugas', 'kategori'])
            ->latest();

        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.pengaduan-manager', [
            'pengaduans' => $query->paginate(15),
            'list_petugas' => User::query()->where('role', 'petugas')->get()
        ])->layout('layouts.app');
    }
}