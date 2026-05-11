<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ImpersonationBanner extends Component
{
    public function stopImpersonating()
    {
        if (session()->has('impersonator_id')) {
            $adminId = session()->pull('impersonator_id');
            Auth::loginUsingId($adminId);
            
            return redirect()->to('/admin/users')->with('success', 'Kembali ke akun Admin.');
        }

        return redirect()->to('/');
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            @if(session()->has('impersonator_id'))
            <div class="bg-primary text-primary-content px-4 py-2 text-center text-xs font-black sticky top-0 z-[200] flex items-center justify-center gap-4 shadow-lg border-b border-white/20">
                <div class="flex items-center gap-2">
                    <x-icon name="o-exclamation-triangle" class="w-4 h-4 animate-pulse" />
                    <span>ANDA SEDANG LOGIN SEBAGAI: <span class="underline uppercase">{{ auth()->user()->name }}</span></span>
                </div>
                <x-button label="KEMBALI KE ADMIN" 
                    wire:click="stopImpersonating" 
                    spinner="stopImpersonating"
                    class="btn-xs btn-outline border-white text-white hover:bg-white hover:text-primary rounded-full px-4" />
            </div>
            @endif
        </div>
        HTML;
    }
}
