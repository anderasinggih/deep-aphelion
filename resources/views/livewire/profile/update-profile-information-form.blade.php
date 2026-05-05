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
    
    // Email Change Security
    public bool $isEmailEditable = false;
    public bool $otpSent = false;
    public string $otpInput = '';
    public int $countdown = 0;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->nik = $user->nik ?? '';
        $this->no_wa = $user->no_wa ?? '';
        $this->email = $user->email ?? '';
    }

    /**
     * Send OTP to current email to unlock editing.
     */
    public function requestEmailChange(): void
    {
        $user = Auth::user();
        $key = 'otp-limit-' . $user->id;

        // Rate Limiting (60 detik)
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 1)) {
            $this->countdown = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            $this->dispatch('notify', message: "Tunggu {$this->countdown} detik sebelum meminta kode baru.", type: 'warning');
            return;
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
        $this->countdown = 0;

        $otp = (string) rand(100000, 999999);
        
        // Simpan OTP di Cache selama 10 menit
        \Illuminate\Support\Facades\Cache::put('email-otp-' . $user->id, $otp, now()->addMinutes(10));
        
        \Illuminate\Support\Facades\Mail::to($user->email)->send(
            new \App\Mail\Security\EmailChangeOTP($user, $otp)
        );

        $this->otpSent = true;
        $this->dispatch('notify', message: 'Kode OTP telah dikirim ke email Anda saat ini.', type: 'info');
    }

    /**
     * Verify OTP and unlock email field.
     */
    public function verifyEmailChangeOtp(): void
    {
        $user = Auth::user();
        $cachedOtp = \Illuminate\Support\Facades\Cache::get('email-otp-' . $user->id);

        if ($this->otpInput && $this->otpInput === $cachedOtp) {
            $this->isEmailEditable = true;
            $this->otpSent = false;
            $this->otpInput = '';
            \Illuminate\Support\Facades\Cache::forget('email-otp-' . $user->id);
            $this->dispatch('notify', message: 'Verifikasi berhasil! Silakan masukkan email baru Anda.', type: 'success');
        } else {
            $this->addError('otpInput', 'Kode OTP salah atau sudah kedaluwarsa.');
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique(User::class)->ignore($user->id)],
            'no_wa' => ['required', 'numeric', 'min_digits:10', 'max_digits:15'],
        ];

        // Hanya validasi email jika sedang bisa diedit
        if ($this->isEmailEditable) {
            $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)];
        }

        $validated = $this->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 angka.',
            'nik.unique' => 'NIK ini sudah digunakan oleh akun lain.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.min_digits' => 'Nomor WhatsApp minimal 10 angka.',
            'no_wa.max_digits' => 'Nomor WhatsApp maksimal 15 angka.',
            'email.required' => 'Email baru wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();
            
            // Kirim notifikasi ke email LAMA (opsional, tapi bagus untuk keamanan)
            // \Illuminate\Support\Facades\Mail::to($user->getOriginal('email'))->send(new \App\Mail\Security\EmailChanged($user));
            
            // Kirim verifikasi ke email BARU (Otomatis ditangani Laravel MustVerifyEmail)
            $user->sendEmailVerificationNotification();
            
            $this->isEmailEditable = false;
            $this->dispatch('notify', message: 'Email berhasil diperbarui. Silakan verifikasi email baru Anda.', type: 'success');
        } else {
            $user->save();
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();
        $key = 'verification-limit-' . $user->id;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 1)) {
            $this->countdown = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            $this->dispatch('notify', message: "Tunggu {$this->countdown} detik sebelum mengirim ulang email verifikasi.", type: 'warning');
            return;
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
        $this->countdown = 0;

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
        $this->dispatch('notify', message: 'Tautan verifikasi telah dikirim ke email Anda.', type: 'info');
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
            <x-input label="Nama Lengkap (Sesuai KTP)" wire:model="name" id="name" type="text" required autofocus autocomplete="name"
                icon="o-user" class="uppercase" placeholder="Contoh: BUDI SANTOSO" />

            <x-input label="Nomor Induk Kependudukan (NIK)" wire:model="nik" id="nik" type="text" required
                autocomplete="off" icon="o-identification" maxlength="16" placeholder="16 digit NIK" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />

            <x-input label="Nomor WhatsApp" wire:model="no_wa" id="no_wa" type="text" required autocomplete="tel"
                icon="o-phone" maxlength="15" placeholder="Contoh: 081234567890" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />

            <x-input label="Email" wire:model="email" id="email" type="email" autocomplete="username"
                icon="o-envelope" placeholder="Contoh: nama@email.com" :disabled="!$isEmailEditable">
                
                @if(!$isEmailEditable && !$otpSent)
                <x-slot:append>
                    <x-button label="Ubah Email" wire:click="requestEmailChange" 
                        class="btn-primary rounded-l-none text-white btn-sm" icon="o-pencil-square"
                        spinner="requestEmailChange" />
                </x-slot:append>
                @endif
            </x-input>
            
            @if($countdown > 0)
            <div class="mt-1 flex items-center gap-2 text-[10px] text-warning font-bold uppercase italic">
                <x-icon name="o-clock" class="w-3 h-3" />
                Silakan tunggu {{ $countdown }} detik sebelum meminta kode baru.
            </div>
            @endif

            @if($otpSent)
            <div class="p-4 bg-info/5 border border-info/20 rounded-xl space-y-3">
                <div class="flex items-start gap-3">
                    <x-icon name="o-shield-check" class="w-5 h-5 text-info mt-0.5" />
                    <div>
                        <p class="text-sm font-bold text-info">Verifikasi Email Lama</p>
                        <p class="text-xs text-base-content/60">Kami telah mengirimkan kode OTP ke email <b>{{ auth()->user()->email }}</b>. Masukkan kode tersebut untuk melanjutkan.</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <x-input wire:model="otpInput" placeholder="6 Digit OTP" class="input-sm flex-1" maxlength="6" />
                    <x-button label="Verifikasi" wire:click="verifyEmailChangeOtp" class="btn-sm btn-primary text-white" spinner="verifyEmailChangeOtp" />
                </div>
                @error('otpInput') <span class="text-xs text-error font-medium italic">{{ $message }}</span> @enderror
            </div>
            @endif

            @if ($isEmailEditable)
            <div class="p-3 bg-warning/5 border border-warning/20 rounded-lg">
                <p class="text-xs text-warning font-medium flex items-center gap-2">
                    <x-icon name="o-information-circle" class="w-4 h-4" />
                    Setelah menyimpan, Anda harus memverifikasi email baru ini sebelum bisa login kembali.
                </p>
            </div>
            @endif

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !
            auth()->user()->hasVerifiedEmail() && !$isEmailEditable)
            <div>
                <p class="text-sm mt-2 text-base-content/80">
                    {{ __('Alamat email Anda belum diverifikasi.') }}
 
                    <button wire:click.prevent="sendVerification"
                        class="underline text-sm text-base-content/60 hover:text-base-content rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        <span wire:loading wire:target="sendVerification">...</span>
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