<?php

namespace App\Livewire\Admin;

use App\Models\Kategori;
use Livewire\Component;
use Livewire\WithPagination;

class KategoriManager extends Component
{
    use WithPagination;

    public $kategoriId, $nama, $deskripsi;
    public $isEdit = false;
    public $showModal = false;
    public $showDeleted = false;
    public $search = '';

    public function mount()
    {
        abort_unless(auth()->user()->role === 'admin', 403);
    }

    protected $rules = [
        'nama' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
    ];

    protected $messages = [
        'nama.required' => 'Nama kategori wajib diisi.',
    ];

    public function render()
    {
        $query = Kategori::query()
            ->where('nama', 'like', '%' . $this->search . '%');

        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        $kategoris = $query->orderBy('id', 'desc')->paginate(10);

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
            'nama' => strtoupper($this->nama),
            'deskripsi' => $this->deskripsi,
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
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $kategori = Kategori::findOrFail($this->kategoriId);
        $kategori->update([
            'nama' => strtoupper($this->nama),
            'deskripsi' => $this->deskripsi,
        ]);

        session()->flash('success', 'Kategori pengaduan berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();
        session()->flash('success', 'Kategori pengaduan berhasil dinonaktifkan.');
    }

    public function restore($id)
    {
        $kategori = Kategori::withTrashed()->findOrFail($id);
        $kategori->restore();
        session()->flash('success', 'Kategori pengaduan berhasil diaktifkan kembali.');
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
        $this->resetValidation();
    }
}