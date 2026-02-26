<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => [
                    'required', 'string', 'min:8', 'confirmed',
                    'regex:/[a-zA-Z]/', // Wajib ada huruf
                    'regex:/[0-9]/',    // Wajib ada angka
                ],
            ], [
                'current_password.required' => 'Kata sandi saat ini wajib diisi.',
                'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan data kami.',
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.regex' => 'Password wajib mengandung kombinasi huruf dan angka.',
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-base-content">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-sm text-base-content/70">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.') }}
        </p>
    </header>

    <x-form wire:submit="updatePassword">
        <div class="space-y-4">
            {{-- Input Password Saat Ini --}}
            <div x-data="{ show: false }" class="relative">
                <x-input label="Kata Sandi Saat Ini" wire:model="current_password" id="update_password_current_password"
                    x-bind:type="show ? 'text' : 'password'" autocomplete="current-password" icon="o-key" required />
                <button type="button" @click="show = !show"
                    class="absolute top-[38px] right-0 flex items-center pr-3 transition-colors cursor-pointer text-base-content/40 hover:text-primary">
                    <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                    <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                </button>
            </div>

            {{-- Input Password Baru --}}
            <div x-data="{ show: false }" class="relative">
                <x-input label="Kata Sandi Baru" wire:model="password" id="update_password_password"
                    x-bind:type="show ? 'text' : 'password'" autocomplete="new-password" icon="o-lock-closed"
                    required />
                <button type="button" @click="show = !show"
                    class="absolute top-[38px] right-0 flex items-center pr-3 transition-colors cursor-pointer text-base-content/40 hover:text-primary">
                    <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                    <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                </button>
            </div>

            {{-- Input Konfirmasi Password --}}
            <div x-data="{ show: false }" class="relative">
                <x-input label="Konfirmasi Kata Sandi" wire:model="password_confirmation"
                    id="update_password_password_confirmation" x-bind:type="show ? 'text' : 'password'"
                    autocomplete="new-password" icon="o-check-circle" required />
                <button type="button" @click="show = !show"
                    class="absolute top-[38px] right-0 flex items-center pr-3 transition-colors cursor-pointer text-base-content/40 hover:text-primary">
                    <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                    <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                </button>
            </div>

            <p class="text-xs text-base-content/50 italic">
                *Password minimal 8 karakter, wajib kombinasi huruf dan angka.
            </p>
        </div>

        <x-slot:actions>
            <div class="flex items-center gap-4">
                <x-button label="Simpan Perubahan" type="submit"
                    class="text-white border-none p-4 shadow-sm rounded-xl btn-primary bg-primary hover:bg-primary/90"
                    spinner="updatePassword" />

                <x-action-message class="me-3" on="password-updated">
                    <span class="text-sm font-bold text-success italic">Berhasil disimpan.</span>
                </x-action-message>
            </div>
        </x-slot:actions>
    </x-form>
</section>