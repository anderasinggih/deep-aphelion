<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pengaduan;

class PengaduanDetail extends Component
{
    public Pengaduan $pengaduan;

    public function mount($id)
    {
        $this->pengaduan = Pengaduan::with(['user', 'kategori', 'petugas'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.pengaduan-detail')
            ->layout('layouts.app');
    }
}