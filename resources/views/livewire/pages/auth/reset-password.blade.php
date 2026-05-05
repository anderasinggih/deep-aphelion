<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-zA-Z]/', 
                'regex:/[0-9]/',    
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password wajib mengandung kombinasi huruf dan angka.',
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', 'Terjadi kesalahan saat mereset kata sandi. Silakan coba lagi.');

            return;
        }

        Session::flash('status', 'Kata sandi Anda telah berhasil direset. Silakan masuk dengan kata sandi baru Anda.');

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row w-full max-w-4xl mx-4 my-8 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200">
    
    {{-- Sisi Kiri (Gambar) --}}
    <div class="hidden relative md:block md:w-5/12">
        <img src="{{ asset('storage/assets/banner.jpg') }}" alt="Kecamatan Kembaran" class="absolute inset-0 object-cover w-full h-full" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8 text-white">
            <h2 class="mb-2 text-2xl font-bold">Atur Ulang Sandi</h2>
            <p class="text-sm opacity-90">Buat kata sandi baru yang kuat untuk menjaga keamanan akun Anda.</p>
        </div>
    </div>

    {{-- Sisi Kanan (Konten) --}}
    <div class="flex flex-col w-full p-8 md:w-7/12 sm:p-10 bg-base-100">
        
        <div class="flex items-center justify-center gap-4 mb-8">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas" class="w-10 h-10 object-contain" />
            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <x-icon name="o-shield-check" class="w-6 h-6" />
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-base-content mb-2">Kata Sandi Baru</h1>
            <p class="text-sm text-base-content/60 leading-relaxed">
                Silakan masukkan email Anda dan tentukan kata sandi baru untuk akun Anda.
            </p>
        </div>

        <form wire:submit="resetPassword" class="space-y-4">
            <x-input label="Alamat Email" wire:model="email" id="email" type="email" placeholder="nama@email.com" icon="o-envelope" required readonly />

            <div class="grid grid-cols-1 gap-4 pt-4 border-t border-base-200">
                <div x-data="{ show: false }" class="relative">
                    <x-input label="Kata Sandi Baru" wire:model="password" id="password" x-bind:type="show ? 'text' : 'password'" placeholder="Buat password minimal 8 karakter" icon="o-lock-closed" required />
                    <button type="button" @click="show = !show" class="absolute top-[38px] right-0 pr-3 text-base-content/40 hover:text-primary">
                        <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                        <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                    </button>
                </div>

                <div x-data="{ show: false }" class="relative">
                    <x-input label="Konfirmasi Kata Sandi" wire:model="password_confirmation" id="password_confirmation" x-bind:type="show ? 'text' : 'password'" placeholder="Ulangi password baru" icon="o-check-circle" required />
                    <button type="button" @click="show = !show" class="absolute top-[38px] right-0 pr-3 text-base-content/40 hover:text-primary">
                        <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                        <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                    </button>
                </div>
            </div>

            <p class="text-xs text-base-content/50 italic text-center pb-2">
                *Wajib kombinasi huruf dan angka, minimal 8 karakter.
            </p>

            <x-button type="submit" label="Perbarui Kata Sandi" class="w-full btn-primary text-white rounded-xl shadow-md" spinner="resetPassword" />
        </form>

    </div>
</div>
