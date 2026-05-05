@extends('emails.layout')

@section('content')
    <h2>Halo, {{ $user->name }}!</h2>
    <p>Kami ingin menginformasikan bahwa alamat email untuk akun Anda telah <strong>berhasil diperbarui</strong> menjadi: <strong>{{ $user->email }}</strong>.</p>
    
    <p>Perubahan ini dilakukan pada: <strong>{{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB</strong>.</p>
    
    <p>Karena Anda baru saja mengganti email, mohon lakukan verifikasi ulang untuk memastikan email baru Anda aktif dan dapat menerima notifikasi laporan.</p>
    
    <div class="button-container">
        <a href="{{ route('dashboard') }}" class="button">Verifikasi Email Baru</a>
    </div>
@endsection
