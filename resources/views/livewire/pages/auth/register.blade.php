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
    public int $countdown = 0;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $key = 'register-limit-' . request()->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 1)) {
            $this->countdown = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'nik' => ['required', 'numeric', 'digits:16', 'unique:'.User::class],
            'no_wa' => ['required', 'numeric', 'min_digits:10', 'max_digits:15'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-zA-Z]/', 
                'regex:/[0-9]/',    
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 5 karakter.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 angka.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.min_digits' => 'Nomor WhatsApp minimal 10 angka.',
            'no_wa.max_digits' => 'Nomor WhatsApp maksimal 15 angka.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password wajib mengandung kombinasi huruf dan angka.',
        ]);

        \Illuminate\Support\Facades\RateLimiter::hit($key, 120); // Batasi 2 menit per pendaftaran
        $this->countdown = 0;

        $validated['name'] = strtoupper($validated['name']);
        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $defaultUrl = route('dashboard', absolute: false);
        if ($user->role === 'admin') {
            $defaultUrl = '/admin/dashboard';
        } elseif ($user->role === 'petugas') {
            $defaultUrl = '/petugas/disposisi';
        }

        $this->redirect($defaultUrl, navigate: true);
    }
}; ?>

<div
    class="flex flex-col md:flex-row w-full max-w-5xl mx-4 my-8 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200 max-h-[95vh] md:max-h-[90vh]">

    <div class="hidden relative md:block md:w-5/12">
        <img src="{{ asset('storage/assets/banner.jpg') }}" alt="Kecamatan Kembaran"
            class="absolute inset-0 object-cover w-full h-full" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8">
            <h2 class="mb-2 text-3xl font-bold text-white shadow-black drop-shadow-md">Pendaftaran Pelapor</h2>
            <p class="font-medium text-white/90 drop-shadow-sm">Bergabunglah untuk melaporkan dan memantau aspirasi
                lingkungan Anda secara transparan.</p>
        </div>
    </div>

    <div class="flex flex-col w-full p-8 overflow-y-auto md:w-7/12 sm:p-10 no-scrollbar bg-base-100"
        style="scrollbar-width: none;">

        <div class="flex items-center justify-center gap-4 mb-6">
            <img src="{{ asset('storage/' . \App\Models\Setting::get('app_logo', 'assets/logobanyumas.png')) }}" 
                alt="Logo Utama" class="block object-contain w-10 h-10" />
            <img src="{{ asset('storage/' . \App\Models\Setting::get('app_logo_sekunder', 'assets/logokominfo.png')) }}" 
                alt="Logo Sekunder" class="block object-contain w-10 h-10" />
        </div>

        <div class="mb-6 text-center">
            <h1 class="mb-1 text-2xl font-bold text-base-content">Daftar Akun Baru</h1>
            <p class="text-sm text-base-content/60">Silakan lengkapi data diri Anda di bawah ini.</p>
        </div>

        @if($countdown > 0)
            <div class="mb-5 p-3 bg-warning/10 border border-warning/20 rounded-xl text-warning text-[11px] font-bold text-center flex items-center justify-center gap-2 uppercase italic transition-all animate-pulse">
                <x-icon name="o-clock" class="w-4 h-4" />
                IP Anda terdeteksi melakukan banyak permintaan. Tunggu {{ $countdown }} detik lagi.
            </div>
        @endif

        <form wire:submit="register" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <x-input wire:model="name" id="name" label="Nama Lengkap (Sesuai KTP)" type="text"
                        placeholder="Contoh: BUDI SANTOSO" required autofocus autocomplete="name" icon="o-user"
                        class="uppercase" />
                </div>

                <div>
                    <x-input wire:model="nik" id="nik" label="Nomor Induk Kependudukan (NIK)" type="text"
                        placeholder="16 digit NIK" required autocomplete="off" icon="o-identification"
                        maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <x-input wire:model="no_wa" id="no_wa" label="Nomor WhatsApp" type="text"
                        placeholder="Contoh: 081234567890" required autocomplete="tel" icon="o-phone"
                        maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                </div>

                <div>
                    <x-input wire:model="email" id="email" label="Email" type="email"
                        placeholder="Contoh: nama@email.com" autocomplete="email" icon="o-envelope" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 pt-3 mt-2 border-t md:grid-cols-2 border-base-200">
                <div>
                    <div x-data="{ show: false }" class="relative">
                        <x-input label="Password" x-bind:type="show ? 'text' : 'password'" wire:model="password" id="password"
                            placeholder="Buat password baru" required autocomplete="new-password"
                            icon="o-lock-closed" />

                        <button type="button" @click="show = !show"
                            class="absolute bottom-0 right-0 h-12 flex items-center pr-3 transition-colors cursor-pointer text-base-content/40 hover:text-primary z-10">
                            <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                            <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                        </button>
                    </div>
                </div>

                <div>
                    <div x-data="{ show: false }" class="relative">
                        <x-input label="Konfirmasi Password" x-bind:type="show ? 'text' : 'password'" wire:model="password_confirmation"
                            id="password_confirmation" placeholder="Ketik ulang password" required
                            autocomplete="new-password" icon="o-lock-closed" />

                        <button type="button" @click="show = !show"
                            class="absolute bottom-0 right-0 flex items-center h-12 pr-3 transition-colors cursor-pointer text-base-content/40 hover:text-primary">
                            <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                            <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                        </button>
                    </div>
                </div>
            </div>

            <p class="pb-2 text-xs text-center text-base-content/50">Password minimal 8 karakter, wajib kombinasi huruf
                dan angka.</p>

            <div class="pt-2">
                <button type="submit"
                    class="w-full text-white border-none shadow-sm btn btn-primary bg-primary hover:bg-primary/90 rounded-xl"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                    <span wire:loading wire:target="register" class="flex items-center gap-2">
                        <span class="loading loading-spinner loading-sm"></span> Memproses...
                    </span>
                </button>
            </div>
        </form>

        <p class="mt-6 text-sm text-center text-base-content/60">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-primary hover:underline" wire:navigate>Masuk</a>
        </p>
    </div>

    <style>
        /* Sembunyikan Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Sembunyikan panah atas-bawah di input type="number" */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</div>