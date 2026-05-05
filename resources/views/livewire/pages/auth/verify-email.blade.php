<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row w-full max-w-4xl mx-4 my-8 bg-base-100 rounded-[2rem] overflow-hidden shadow-xl border border-base-200">
    
    {{-- Sisi Kiri (Gambar) --}}
    <div class="hidden relative md:block md:w-5/12">
        <img src="{{ asset('storage/assets/banner.jpg') }}" alt="Kecamatan Kembaran" class="absolute inset-0 object-cover w-full h-full" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8 text-white">
            <h2 class="mb-2 text-2xl font-bold">Verifikasi Email</h2>
            <p class="text-sm opacity-90">Keamanan akun Anda adalah prioritas kami untuk memastikan laporan Anda valid.</p>
        </div>
    </div>

    {{-- Sisi Kanan (Konten) --}}
    <div class="flex flex-col w-full p-8 md:w-7/12 sm:p-10 bg-base-100">
        
        <div class="flex items-center justify-center gap-4 mb-8">
            <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas" class="w-10 h-10 object-contain" />
            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <x-icon name="o-envelope-open" class="w-6 h-6" />
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-base-content mb-2">Terima Kasih Telah Mendaftar!</h1>
            <p class="text-sm text-base-content/60 leading-relaxed">
                Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. 
                Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkannya kembali.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-8 p-4 bg-success/10 border border-success/20 rounded-xl text-success text-sm font-medium text-center italic">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
        @endif

        <div class="space-y-4">
            <x-button wire:click="sendVerification" label="Kirim Ulang Email Verifikasi" class="w-full btn-primary text-white rounded-xl" spinner="sendVerification" />
            
            <div class="text-center">
                <button wire:click="logout" type="button" class="text-sm text-base-content/50 hover:text-error transition-colors font-medium">
                    Keluar dari Sesi
                </button>
            </div>
        </div>

    </div>
</div>
