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

        // Load SMTP Settings from DB
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $mailSettings = \App\Models\Setting::where('key', 'like', 'mail_%')->pluck('value', 'key');
                
                if ($mailSettings->isNotEmpty()) {
                    config([
                        'mail.mailers.smtp.host' => $mailSettings['mail_host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port' => $mailSettings['mail_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.username' => $mailSettings['mail_username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $mailSettings['mail_password'] ?? config('mail.mailers.smtp.password'),
                        'mail.mailers.smtp.encryption' => $mailSettings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
                        'mail.from.address' => $mailSettings['mail_username'] ?? config('mail.from.address'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Table might not exist yet during migration
        }

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
