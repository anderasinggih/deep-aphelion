<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $name = '';
    public string $nik = '';
    public string $no_wa = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'nik' => ['required', 'numeric', 'digits:16', 'unique:'.User::class],
            'no_wa' => ['required', 'numeric', 'min_digits:10'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-zA-Z]/', // must contain at least one letter
                'regex:/[0-9]/', // must contain at least one number
            ],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div
    class="flex flex-col md:flex-row w-full max-w-5xl mx-4 my-8 bg-[#1f2937] rounded-3xl overflow-hidden shadow-2xl max-h-[95vh] md:max-h-[90vh]">

    <!-- Left Side: Image Banner -->
    <div class="hidden md:block md:w-5/12 relative">
        <img src="https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop"
            alt="Kabupaten Banyumas" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8 w-full">
            <h2 class="text-3xl font-bold text-white mb-2">Pendaftaran Warga</h2>
            <p class="text-white/80">Bergabunglah untuk melaporkan dan memantau aspirasi lingkungan Anda secara
                transparan.</p>
        </div>
    </div>

    <!-- Right Side: Register Form (Scrollable) -->
    <div class="w-full md:w-7/12 p-8 sm:p-10 flex flex-col overflow-y-auto no-scrollbar" style="scrollbar-width: none;">

        <!-- Header Logos -->
        <div class="flex items-center justify-center gap-4 mb-6">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                class="w-10 h-10 object-contain" />
            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center p-1">
                <x-icon name="o-megaphone" class="w-6 h-6 text-primary" />
            </div>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-white mb-1">Daftar Akun Baru</h1>
            <p class="text-sm text-gray-400">Silakan lengkapi data diri Anda di bawah ini.</p>
        </div>

        <form wire:submit="register" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <x-input wire:model="name" id="name" label="Nama Lengkap (Sesuai KTP)" type="text" required
                        autofocus autocomplete="name"
                        class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
                </div>

                <!-- NIK -->
                <div>
                    <x-input wire:model="nik" id="nik" label="Nomor Induk Kependudukan (NIK)" type="number" required
                        autocomplete="off"
                        class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- WhatsApp -->
                <div>
                    <x-input wire:model="no_wa" id="no_wa" label="Nomor WhatsApp" type="number" placeholder="08xxxxxxxx"
                        required autocomplete="tel"
                        class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
                </div>

                <!-- Email -->
                <div>
                    <x-input wire:model="email" id="email" label="Email (Opsional)" type="email"
                        placeholder="email@example.com" autocomplete="email"
                        class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-700 pt-3 mt-2">
                <!-- Password -->
                <div>
                    <x-input wire:model="password" id="password" label="Password" type="password" icon-right="o-eye"
                        required autocomplete="new-password"
                        class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input wire:model="password_confirmation" id="password_confirmation" label="Konfirmasi Password"
                        type="password" icon-right="o-eye" required autocomplete="new-password"
                        class="bg-[#374151] border-gray-600 text-white placeholder-gray-400 focus:border-primary focus:ring-primary" />
                </div>
            </div>

            <p class="text-xs text-gray-500 text-center pb-2">Password minimal 8 karakter, wajib kombinasi huruf dan
                angka.</p>

            <div class="pt-2">
                <button type="submit"
                    class="w-full btn btn-primary text-white border-none bg-blue-600 hover:bg-blue-700 rounded-full">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary hover:underline font-medium" wire:navigate>Masuk</a>
        </p>
    </div>

    <!-- Inline Style for hiding scrollbar -->
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</div>