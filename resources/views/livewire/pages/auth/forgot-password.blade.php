<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.');

            return;
        }

        $this->reset('email');

        session()->flash('status', 'Kami telah mengirimkan tautan reset kata sandi ke email Anda.');
    }
}; ?>

<div class="flex flex-col md:flex-row w-full max-w-4xl mx-4 my-8 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200">
    
    {{-- Sisi Kiri (Gambar) --}}
    <div class="hidden relative md:block md:w-5/12">
        <img src="{{ asset('storage/assets/banner.jpg') }}" alt="Kecamatan Kembaran" class="absolute inset-0 object-cover w-full h-full" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8 text-white">
            <h2 class="mb-2 text-2xl font-bold">Lupa Kata Sandi?</h2>
            <p class="text-sm opacity-90">Jangan khawatir, kami akan membantu Anda memulihkan akses ke akun Anda.</p>
        </div>
    </div>

    {{-- Sisi Kanan (Konten) --}}
    <div class="flex flex-col w-full p-8 md:w-7/12 sm:p-10 bg-base-100">
        
        <div class="flex items-center justify-center gap-4 mb-8">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas" class="w-10 h-10 object-contain" />
            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <x-icon name="o-key" class="w-6 h-6" />
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-base-content mb-2">Pemulihan Akun</h1>
            <p class="text-sm text-base-content/60 leading-relaxed">
                Masukkan alamat email Anda yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-8 p-4 bg-success/10 border border-success/20 rounded-xl text-success text-sm font-medium text-center italic">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="sendPasswordResetLink" class="space-y-6">
            <x-input label="Alamat Email" wire:model="email" id="email" type="email" placeholder="nama@email.com" icon="o-envelope" required autofocus />

            <div class="space-y-4">
                <x-button type="submit" label="Kirim Tautan Reset" class="w-full btn-primary text-white rounded-xl shadow-md" spinner="sendPasswordResetLink" />
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-primary hover:underline" wire:navigate>
                        Kembali ke Halaman Masuk
                    </a>
                </div>
            </div>
        </form>

    </div>
</div>
