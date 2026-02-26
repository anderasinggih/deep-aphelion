<?php

namespace App\Livewire\Admin;

use App\Models\Kategori;
use Livewire\Component;
use Livewire\WithPagination;

class KategoriManager extends Component
{
    use WithPagination;

    public $kategoriId, $nama, $deskripsi, $sla_hari;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'sla_hari' => 'required|integer|min:1',
    ];

    protected $messages = [
        'nama.required' => 'Nama kategori wajib diisi.',
        'sla_hari.required' => 'SLA (Hari) wajib diisi.',
        'sla_hari.min' => 'SLA minimal 1 hari.',
    ];

    public function render()
    {
        $kategoris = Kategori::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.kategori-manager', [
            'kategoris' => $kategoris
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        Kategori::create([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'sla_hari' => $this->sla_hari,
        ]);

        session()->flash('success', 'Kategori pengaduan berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        $this->kategoriId = $id;
        $this->nama = $kategori->nama;
        $this->deskripsi = $kategori->deskripsi;
        $this->sla_hari = $kategori->sla_hari;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $kategori = Kategori::findOrFail($this->kategoriId);
        $kategori->update([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'sla_hari' => $this->sla_hari,
        ]);

        session()->flash('success', 'Kategori pengaduan berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Cek apakah ada pengaduan terkait (opsional, ganti logika jika ingin restrict delete)
        if ($kategori->pengaduans()->exists()) {
            session()->flash('error', 'Kategori ini tidak dapat dihapus karena sudah ada laporan pengaduan terkait.');
            return;
        }

        $kategori->delete();
        session()->flash('success', 'Kategori pengaduan berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->kategoriId = null;
        $this->nama = '';
        $this->deskripsi = '';
        $this->sla_hari = '';
        $this->resetValidation();
    }
}