@extends('emails.layout')

@section('content')
    <h2>Halo, {{ $user->name }}!</h2>
    <p>Kami ingin menginformasikan bahwa kata sandi akun Anda telah <strong>berhasil diperbarui</strong>.</p>
    
    <p>Perubahan ini dilakukan pada: <strong>{{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB</strong>.</p>
    
    <p>Jika Anda merasa tidak melakukan perubahan ini, silakan segera melakukan reset kata sandi melalui halaman "Lupa Password" atau hubungi admin kami untuk mengamankan akun Anda.</p>
    
    <div class="button-container">
        <a href="{{ route('login') }}" class="button">Masuk ke Akun</a>
    </div>
@endsection
