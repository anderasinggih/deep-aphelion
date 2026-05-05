<x-mail::message>
# Halo, {{ $user->name }}!

Kami menerima permintaan untuk mengubah alamat email akun Anda di **{{ config('app.name') }}**.

Untuk melanjutkan proses ini, silakan masukkan kode keamanan di bawah ini pada halaman profil Anda:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

Kode ini berlaku selama 10 menit. Jika Anda tidak merasa melakukan permintaan ini, silakan segera amankan akun Anda atau abaikan email ini.

Terima kasih,<br>
Tim {{ config('app.name') }}
</x-mail::message>
