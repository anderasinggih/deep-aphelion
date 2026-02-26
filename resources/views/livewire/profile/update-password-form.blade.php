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
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
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
            <x-input label="Kata Sandi Saat Ini" wire:model="current_password" id="update_password_current_password"
                type="password" autocomplete="current-password" icon="o-key" required />

            <x-input label="Kata Sandi Baru" wire:model="password" id="update_password_password" type="password"
                autocomplete="new-password" icon="o-lock-closed" required />

            <x-input label="Konfirmasi Kata Sandi" wire:model="password_confirmation"
                id="update_password_password_confirmation" type="password" autocomplete="new-password"
                icon="o-check-circle" required />
        </div>

        <x-slot:actions>
            <div class="flex items-center gap-4">
                <x-button label="Simpan" type="submit"
                    class="text-white border-none shadow-sm rounded-xl btn-primary bg-primary hover:bg-primary/90"
                    spinner="updatePassword" />

                <x-action-message class="me-3" on="password-updated">
                    <span class="text-sm text-success">Berhasil disimpan.</span>
                </x-action-message>
            </div>
        </x-slot:actions>
    </x-form>
</section>