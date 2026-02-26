<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $nik = '';
    public string $no_wa = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->nik = Auth::user()->nik ?? '';
        $this->no_wa = Auth::user()->no_wa ?? '';
        $this->email = Auth::user()->email ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique(User::class)->ignore($user->id)],
            'no_wa' => ['required', 'numeric', 'min_digits:10', 'max_digits:15'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 angka.',
            'nik.unique' => 'NIK ini sudah digunakan oleh akun lain.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.min_digits' => 'Nomor WhatsApp minimal 10 angka.',
            'no_wa.max_digits' => 'Nomor WhatsApp maksimal 15 angka.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-base-content">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-base-content/70">
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <x-form wire:submit="updateProfileInformation">
        <style>
            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus,
            input:-webkit-autofill:active {
                /* Tahan background biar ga berubah putih */
                transition: background-color 5000s ease-in-out 0s !important;

                /* PAKSA WARNA TEKS JADI INHERIT MENGHINDARI TEKS HITAM DI DARK MODE */
                -webkit-text-fill-color: inherit !important;
                color: inherit !important;
                font-weight: 500 !important;
            }
        </style>

        <div class="space-y-4">
            <x-input label="Nama Lengkap" wire:model="name" id="name" required autofocus autocomplete="name"
                icon="o-user" />

            <x-input label="Nomor Induk Kependudukan (NIK)" wire:model="nik" id="nik" type="number" required
                autocomplete="off" icon="o-identification" />

            <x-input label="Nomor WhatsApp" wire:model="no_wa" id="no_wa" type="number" required autocomplete="tel"
                icon="o-phone" />

            <x-input label="Email" wire:model="email" id="email" type="email" autocomplete="username"
                icon="o-envelope" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !
            auth()->user()->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-base-content/80">
                    {{ __('Alamat email Anda belum diverifikasi.') }}

                    <button wire:click.prevent="sendVerification"
                        class="underline text-sm text-base-content/60 hover:text-base-content rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-success">
                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <x-slot:actions>
            <div class="flex items-center gap-4">
                <x-button label="Simpan" type="submit"
                    class="text-white border-none shadow-sm p-4 rounded-xl btn-primary bg-primary hover:bg-primary/90"
                    spinner="updateProfileInformation" />

                <x-action-message class="me-3" on="profile-updated">
                    <span class="text-sm text-success">Berhasil disimpan.</span>
                </x-action-message>
            </div>
        </x-slot:actions>
    </x-form>
</section>