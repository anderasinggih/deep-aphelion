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

    // Update Status Modal State
    public $updateModal = false;
    public $update_status = '';
    public $update_foto;
    public $update_keterangan = '';

    public function openDisposisi($id)
    {
        $this->selectedPengaduanId = $id;
        $pengaduan = Pengaduan::find($id);
        $this->petugas_id = $pengaduan->petugas_id ?? '';
        $this->disposisiModal = true;
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

    public function openUpdateStatusModal($id, $newStatus)
    {
        $pengaduan = Pengaduan::find($id);
        if ($newStatus === 'diproses' && !$pengaduan->petugas_id) {
            session()->flash('error', 'Pilih petugas untuk disposisi terlebih dahulu sebelum mengubah status menjadi Diproses.');
            $this->openDisposisi($id);
            return;
        }

        $this->selectedPengaduanId = $id;
        $this->update_status = $newStatus;
        $this->updateModal = true;
    }

    public function saveStatusUpdate()
    {
        $rules = [
            'selectedPengaduanId' => 'required|exists:pengaduans,id',
            'update_status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'update_keterangan' => 'nullable|string',
            'update_foto' => 'nullable|image|max:5120',
        ];

        if ($this->update_status === 'selesai' || $this->update_status === 'ditolak') {
            $rules['update_keterangan'] = 'required|string|min:5';
        }
        if ($this->update_status === 'selesai') {
            $rules['update_foto'] = 'required|image|max:5120';
        }

        $this->validate($rules);

        $pengaduan = Pengaduan::findOrFail($this->selectedPengaduanId);
        $path = null;

        if ($this->update_foto) {
            $path = $this->update_foto->store('bukti_selesai', 'public');
        }

        $oldStatus = $pengaduan->status;
        $pengaduan->status = $this->update_status;
        $pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $this->update_status,
            'keterangan_admin' => $this->update_keterangan,
            'foto_bukti' => $path,
        ]);

        $this->reset('updateModal', 'update_status', 'update_foto', 'update_keterangan', 'selectedPengaduanId');
        session()->flash('success', 'Status laporan berhasil diperbarui.');
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