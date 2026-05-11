<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class Maintenance extends Component
{
    public function mount()
    {
        $this->checkStatus();
    }

    public function checkStatus()
    {
        if (Setting::get('maintenance_mode', '0') === '0') {
            return redirect()->route('beranda');
        }
    }

    public function render()
    {
        return view('livewire.maintenance')->layout('layouts.maintenance');
    }
}
