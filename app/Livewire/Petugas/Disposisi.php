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

    // Update Modal State
    public $updateModal = false;
    public $update_status = '';
    public $update_foto;
    public $update_keterangan = '';
    public $selectedPengaduanId = null;

    public function openUpdateStatusModal($id, $newStatus)
    {
        $this->selectedPengaduanId = $id;
        $this->update_status = $newStatus;
        $this->updateModal = true;
    }

    public function saveStatusUpdate()
    {
        $rules = [
            'selectedPengaduanId' => 'required|exists:pengaduans,id',
            'update_status' => 'required|in:diproses,selesai',
            'update_keterangan' => 'nullable|string',
            'update_foto' => 'nullable|image|max:5120',
        ];

        if ($this->update_status === 'selesai') {
            $rules['update_keterangan'] = 'required|string|min:5';
            $rules['update_foto'] = 'required|image|max:5120';
        }

        $this->validate($rules);

        $pengaduan = Pengaduan::where('petugas_id', auth()->id())->findOrFail($this->selectedPengaduanId);
        $path = null;

        if ($this->update_foto) {
            $path = $this->update_foto->store('bukti_selesai', 'public');
        }

        $oldStatus = $pengaduan->status;
        $pengaduan->status = $this->update_status;
        $pengaduan->save();

        // Default note for 'diproses' if empty
        $keterangan = $this->update_keterangan;
        if ($this->update_status === 'diproses' && empty($keterangan)) {
            $keterangan = 'Petugas mulai menindaklanjuti laporan di lapangan.';
        }

        PengaduanHistory::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $this->update_status,
            'keterangan_admin' => $keterangan,
            'foto_bukti' => $path,
        ]);

        $this->reset('updateModal', 'update_status', 'update_foto', 'update_keterangan', 'selectedPengaduanId');
        session()->flash('success', 'Status laporan berhasil diperbarui.');
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