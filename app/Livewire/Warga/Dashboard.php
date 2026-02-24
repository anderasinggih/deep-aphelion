<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $pengaduans = Auth::user()->pengaduans()
            ->with('kategori')
            ->latest()
            ->paginate(5);

        return view('livewire.warga.dashboard', [
            'pengaduans' => $pengaduans
        ])->layout('layouts.app');
    }
}