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

<div
    class="flex flex-col md:flex-row w-full max-w-4xl mx-4 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200">

    <!-- Left Side: Image Banner -->
    <div class="hidden md:block md:w-1/2 relative">
        <img src="https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop"
            alt="Kabupaten Banyumas" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8">
            <h2 class="text-3xl font-bold text-white mb-2 shadow-black drop-shadow-md">Kembaran Ngadu</h2>
            <p class="text-white/90 drop-shadow-sm font-medium">Layanan aspirasi dan pengaduan masyarakat terpadu
                Kecamatan Kembaran.</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center bg-base-100">

        <!-- Header Logos -->
        <div class="flex items-center justify-center gap-4 mb-6">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                class="w-12 h-12 object-contain" />
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center p-1 text-primary">
                <x-icon name="o-megaphone" class="w-7 h-7" />
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-base-content mb-2">Masuk</h1>
            <p class="text-sm text-base-content/60">Selamat datang di website pengaduan masyarakat Kecamatan Kembaran!
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-5">
            <!-- Email Address -->
            <div>
                <x-input wire:model="form.email" id="email" label="Alamat email" placeholder="email@example.com"
                    type="email" required autofocus autocomplete="username"
                    class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-base-content/80">Password</label>
                    @if (Route::has('password.request'))
                    <a class="text-xs text-primary hover:text-primary/80 transition-colors font-medium"
                        href="{{ route('password.request') }}" wire:navigate>
                        Lupa password?
                    </a>
                    @endif
                </div>
                <x-input wire:model="form.password" id="password" placeholder="Masukkan password" type="password"
                    required autocomplete="current-password"
                    class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />


            </div>



            <!-- Remember Me -->
            <div class="pt-2">
                <label for="remember" class="flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="checkbox checkbox-sm checkbox-primary rounded bg-base-200 border-base-300"
                        name="remember">
                    <span class="ml-3 text-sm text-base-content/70">Ingat Saya</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full btn btn-primary bg-primary border-none hover:bg-primary/90 text-white shadow-sm rounded-xl">
                    Masuk
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-base-content/60">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary hover:underline font-bold" wire:navigate>Daftar</a>
        </p>
    </div>

</div>
</div>