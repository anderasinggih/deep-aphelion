<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row w-full max-w-4xl mx-4 bg-[#1f2937] rounded-2xl overflow-hidden shadow-2xl">

    <!-- Left Side: Image Banner -->
    <div class="hidden md:block md:w-1/2 relative">
        <img src="https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop"
            alt="Kabupaten Banyumas" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8">
            <h2 class="text-3xl font-bold text-white mb-2">Kembaran Ngadu</h2>
            <p class="text-white/80">Layanan aspirasi dan pengaduan masyarakat terpadu Kecamatan Kembaran.</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">

        <!-- Header Logos -->
        <div class="flex items-center justify-center gap-4 mb-6">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                class="w-12 h-12 object-contain" />
            <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center p-1">
                <!-- Fallback info logo layout similar to screenshot -->
                <x-icon name="o-megaphone" class="w-8 h-8 text-primary" />
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Masuk</h1>
            <p class="text-sm text-gray-400">Selamat datang di website pengaduan masyarakat Kecamatan Kembaran!</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-5">
            <!-- Email Address -->
            <div>
                <x-input wire:model="form.email" id="email" label="Alamat email" placeholder="email@example.com"
                    type="email" required autofocus autocomplete="username"
                    class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    @if (Route::has('password.request'))
                    <a class="text-xs text-primary hover:text-primary-focus transition-colors"
                        href="{{ route('password.request') }}" wire:navigate>
                        Lupa password?
                    </a>
                    @endif
                </div>
                <x-input wire:model="form.password" id="password" placeholder="Masukkan password" type="password"
                    icon-right="o-eye" required autocomplete="current-password"
                    class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
            </div>

            <!-- Remember Me -->
            <div class="pt-2">
                <label for="remember" class="flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="checkbox checkbox-sm checkbox-primary rounded bg-[#374151] border-gray-600"
                        name="remember">
                    <span class="ml-3 text-sm text-gray-300">Remember me</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full btn btn-primary text-white border-none bg-blue-600 hover:bg-blue-700">
                    Masuk
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary hover:underline font-medium" wire:navigate>Daftar</a>
        </p>
    </div>

</div>