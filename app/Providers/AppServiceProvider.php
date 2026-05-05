<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale('id');
        config(['app.locale' => 'id']);

        // Customize Verify Email
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('[' . config('app.name') . '] Verifikasi Alamat Email')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
                ->action('Verifikasi Email', $url)
                ->line('Jika Anda tidak merasa mendaftar di sistem kami, abaikan email ini.')
                ->salutation('Salam, Tim ' . config('app.name'));
        });

        // Customize Reset Password
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('[' . config('app.name') . '] Permintaan Reset Kata Sandi')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Kami menerima permintaan untuk mereset kata sandi akun Anda.')
                ->action('Reset Kata Sandi', $url)
                ->line('Tautan reset ini akan kedaluwarsa dalam ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' menit.')
                ->line('Jika Anda tidak merasa meminta reset kata sandi, abaikan email ini.')
                ->salutation('Salam, Tim ' . config('app.name'));
        });
    }
}
