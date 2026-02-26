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

    public $updateModal = false;
    public $update_status = '';
    public $update_foto;
    public $update_keterangan = '';

    public function mount($id)
    {
        $this->pengaduan = Pengaduan::with(['user', 'kategori', 'petugas'])->findOrFail($id);
    }

    public function openDisposisi()
    {
        $this->petugas_id = $this->pengaduan->petugas_id ?? '';
        $this->disposisiModal = true;
    }

    public function openUpdateStatusModal($newStatus)
    {
        if ($newStatus === 'diproses' && !$this->pengaduan->petugas_id) {
            session()->flash('error', 'Pilih petugas untuk disposisi terlebih dahulu sebelum mengubah status menjadi Diproses.');
            $this->pengaduan->refresh(); // Revert selcet state
            $this->openDisposisi();
            return;
        }

        $this->update_status = $newStatus;
        $this->updateModal = true;
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

    public function saveStatusUpdate()
    {
        $rules = [
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

        $path = null;
        if ($this->update_foto) {
            $path = $this->update_foto->store('bukti_selesai', 'public');
        }

        $oldStatus = $this->pengaduan->getOriginal('status') ?? $this->pengaduan->status;
        $this->pengaduan->status = $this->update_status;
        $this->pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $this->update_status,
            'keterangan_admin' => $this->update_keterangan,
            'foto_bukti' => $path,
        ]);

        $this->reset('updateModal', 'update_status', 'update_foto', 'update_keterangan');
        session()->flash('success', 'Status laporan berhasil diperbarui.');
        $this->pengaduan->refresh();
    }

    public function render()
    {
        return view('livewire.admin.pengaduan-detail', [
            'list_petugas' => User::query()->where('role', 'petugas')->get()
        ])->layout('layouts.app');
    }
}