<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;

class PengaduanFeedDetail extends Component
{
    public Pengaduan $pengaduan;

    public function mount($id)
    {
        // Load the pengaduan with user, kategori, and its history timeline along with the admin user who made the change
        $this->pengaduan = Pengaduan::with([
            'user',
            'kategori',
            'histories' => function ($query) {
            // Order histories from newest to oldest for a timeline display
            $query->latest();
        },
            'histories.user' // Get the user (admin/petugas) who created the history
        ])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.pengaduan-feed-detail')
            ->layout('layouts.app');
    }
}