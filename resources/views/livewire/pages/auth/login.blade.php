<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
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
        // 1. Validasi Input Dasar dengan Bahasa Indonesia
        $this->validate([
            'form.email' => ['required', 'string', 'email'],
            'form.password' => ['required', 'string'],
        ], [
            'form.email.required' => 'Email wajib diisi.',
            'form.email.email' => 'Format email tidak valid.',
            'form.password.required' => 'Password wajib diisi.',
        ]);

        // 2. Proses Autentikasi (Cek ke Database)
        try {
            $this->form->authenticate();
        } catch (ValidationException $e) {
            // Mengubah pesan bawaan Laravel "These credentials do not match..."
            throw ValidationException::withMessages([
                'form.email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        Session::regenerate();

        $user = auth()->user();
        $defaultUrl = route('dashboard', absolute: false);

        if ($user->role === 'admin') {
            $defaultUrl = '/admin/dashboard';
        } elseif ($user->role === 'petugas') {
            $defaultUrl = '/petugas/disposisi';
        }

        $this->redirectIntended(default: $defaultUrl, navigate: true);
    }
}; ?>

<div
    class="flex flex-col md:flex-row w-full max-w-4xl mx-4 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200">

    <div class="hidden relative md:block md:w-1/2">
        <img src="https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop"
            alt="Kabupaten Banyumas" class="absolute inset-0 object-cover w-full h-full" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8">
            <h2 class="mb-2 text-3xl font-bold text-white shadow-black drop-shadow-md">Kembaran Ngadu</h2>
            <p class="font-medium text-white/90 drop-shadow-sm">Layanan aspirasi dan pengaduan masyarakat terpadu
                Kecamatan Kembaran.</p>
        </div>
    </div>

    <div class="flex flex-col justify-center w-full p-8 md:w-1/2 sm:p-12 bg-base-100">

        <div class="flex items-center justify-center gap-4 mb-6">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                class="block object-contain w-12 h-12" />
            <div class="flex items-center justify-center w-12 h-12 p-1 rounded-xl bg-primary/10 text-primary">
                <x-icon name="o-megaphone" class="w-7 h-7" />
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="mb-2 text-2xl font-bold text-base-content">Masuk</h1>
            <p class="text-sm text-base-content/60">Selamat datang di website pengaduan masyarakat Kecamatan Kembaran!
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-5">
            <div>
                <x-input wire:model="form.email" id="email" label="Alamat email" placeholder="email@example.com"
                    type="email" required autofocus autocomplete="username" icon="o-envelope" />
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-base-content/80">Password</label>
                    @if (Route::has('password.request'))
                    <a class="text-xs font-medium transition-colors text-primary hover:text-primary/80"
                        href="{{ route('password.request') }}" wire:navigate>
                        Lupa password?
                    </a>
                    @endif
                </div>

                <div x-data="{ show: false }" class="relative">
                    <x-input x-bind:type="show ? 'text' : 'password'" wire:model="form.password" id="password"
                        placeholder="Masukkan password" required autocomplete="current-password" icon="o-lock-closed" />

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 transition-colors cursor-pointer text-base-content/40 hover:text-primary">
                        <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                        <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <label for="remember" class="flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded checkbox checkbox-sm checkbox-primary bg-base-200 border-base-300"
                        name="remember">
                    <span class="ml-3 text-sm text-base-content/70">Ingat Saya</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full text-white border-none shadow-sm btn btn-primary bg-primary hover:bg-primary/90 rounded-xl">
                    Masuk
                </button>
            </div>
        </form>

        <p class="mt-8 text-sm text-center text-base-content/60">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-primary hover:underline" wire:navigate>Daftar</a>
        </p>
    </div>

    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            /* Tahan background agar tetap ikut tema gelap */
            transition: background-color 5000s ease-in-out 0s !important;

            /* Paksa warna teks jadi putih keabu-abuan (Hex murni, bukan variabel) */
            -webkit-text-fill-color: #f3f4f6 !important;
            font-weight: 500 !important;
        }
    </style>
</div>