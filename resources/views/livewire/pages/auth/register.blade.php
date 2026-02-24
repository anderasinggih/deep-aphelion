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
        // Menambahkan Custom Messages berbahasa Indonesia
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'nik' => ['required', 'numeric', 'digits:16', 'unique:'.User::class],
            'no_wa' => ['required', 'numeric', 'min_digits:10', 'max_digits:15'], // Ditambah max_digits agar aman di database
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-zA-Z]/', // Harus ada huruf
                'regex:/[0-9]/',    // Harus ada angka
            ],
        ], [
            // Kustomisasi pesan error agar ramah untuk warga
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

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div
    class="flex flex-col md:flex-row w-full max-w-5xl mx-4 my-8 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200 max-h-[95vh] md:max-h-[90vh]">

    <div class="hidden md:block md:w-5/12 relative">
        <img src="https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1000&auto=format&fit=crop"
            alt="Kecamatan Kembaran" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8">
            <h2 class="mb-2 text-3xl font-bold text-white shadow-black drop-shadow-md">Pendaftaran Warga</h2>
            <p class="font-medium text-white/90 drop-shadow-sm">Bergabunglah untuk melaporkan dan memantau aspirasi
                lingkungan Anda secara transparan.</p>
        </div>
    </div>

    <div class="flex flex-col w-full p-8 overflow-y-auto md:w-7/12 sm:p-10 no-scrollbar bg-base-100"
        style="scrollbar-width: none;">

        <div class="flex items-center justify-center gap-4 mb-6">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                class="block object-contain w-10 h-10" />
            <div class="flex items-center justify-center w-10 h-10 p-1 rounded-xl bg-primary/10 text-primary">
                <x-icon name="o-megaphone" class="w-6 h-6" />
            </div>
        </div>

        <div class="mb-6 text-center">
            <h1 class="mb-1 text-2xl font-bold text-base-content">Daftar Akun Baru</h1>
            <p class="text-sm text-base-content/60">Silakan lengkapi data diri Anda di bawah ini.</p>
        </div>

        <form wire:submit="register" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <x-input wire:model="name" id="name" label="Nama Lengkap (Sesuai KTP)" type="text" required
                        autofocus autocomplete="name"
                        class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />
                </div>

                <div>
                    <x-input wire:model="nik" id="nik" label="Nomor Induk Kependudukan (NIK)" type="number" required
                        autocomplete="off"
                        class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <x-input wire:model="no_wa" id="no_wa" label="Nomor WhatsApp" type="number" placeholder="08xxxxxxxx"
                        required autocomplete="tel"
                        class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />
                </div>

                <div>
                    <x-input wire:model="email" id="email" label="Email (Opsional)" type="email"
                        placeholder="email@example.com" autocomplete="email"
                        class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />
                </div>
            </div>

            <div class="pt-3 mt-2 border-t grid grid-cols-1 md:grid-cols-2 gap-4 border-base-200">
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-base-content/80">Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <x-input x-bind:type="show ? 'text' : 'password'" wire:model="password" id="password" required
                            autocomplete="new-password"
                            class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-base-content/40 hover:text-primary transition-colors">
                            <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                            <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block mb-1 text-sm font-medium text-base-content/80">Konfirmasi Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <x-input x-bind:type="show ? 'text' : 'password'" wire:model="password_confirmation"
                            id="password_confirmation" required autocomplete="new-password"
                            class="bg-base-200 border-base-300 text-base-content focus:border-primary focus:ring-primary" />

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-base-content/40 hover:text-primary transition-colors">
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
                    class="w-full text-white border-none shadow-sm btn btn-primary bg-primary hover:bg-primary/90 rounded-xl">
                    Daftar Sekarang
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

        /* Sembunyikan panah atas-bawah di input type="number" (Chrome, Safari, Edge, Opera) */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Sembunyikan panah atas-bawah di input type="number" (Firefox) */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</div>