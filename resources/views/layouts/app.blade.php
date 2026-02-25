<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - Kembaran Ngadu' : 'Kembaran Ngadu' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @stack('styles')
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- Wrapper untuk ngatur posisi melayang (ada jarak dari atas dan samping) --}}
    <div class="sticky top-4 z-[100] w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all">
        
        {{-- Navbar utamanya dibikin melengkung, blur tinggi, dan ada border kaca --}}
        <div class="navbar bg-base-100/40 backdrop-blur-xl border border-white/30 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-[2rem] px-4 sm:px-6">
            
            <div class="navbar-start ">
                <div class="dropdown ">
                    <div tabindex="0" role="button" class="btn btn-ghost lg:hidden rounded-full ">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content mt-5 z-[50] p-2 shadow-lg bg-base-100/90 backdrop-blur-lg rounded-2xl w-52 border border-white/20">
                        <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}"><x-icon name="o-home" class="w-4 h-4" /> Beranda Publik</a></li>
                        @auth
                        @if(auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"><x-icon name="o-chart-bar" class="w-4 h-4" /> Dashboard Admin</a></li>
                        <li><a href="/admin/pengaduan" class="{{ request()->is('admin/pengaduan') ? 'active' : '' }}"><x-icon name="o-inbox-stack" class="w-4 h-4" /> Kelola Pengaduan</a></li>
                        @elseif(auth()->user()->role === 'petugas')
                        <li><a href="/petugas/disposisi" class="{{ request()->is('petugas/disposisi') ? 'active' : '' }}"><x-icon name="o-clipboard-document-check" class="w-4 h-4" /> Disposisi</a></li>
                        @else
                        <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}"><x-icon name="o-chart-pie" class="w-4 h-4" /> Dashboard</a></li>
                        @endif
                        <li><a href="/pengaduan/create" class="{{ request()->is('pengaduan/create') ? 'active' : '' }}"><x-icon name="o-plus-circle" class="w-4 h-4" /> Buat Pengaduan</a></li>
                        @endauth
                    </ul>
                </div>
                <a href="/" class="text-xl font-bold text-brand flex items-center gap-2 lg:ml-2 whitespace-nowrap hover:scale-105 transition-transform">
                    <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas" class="w-9 h-9 object-contain drop-shadow-sm" />
                    <span class="hidden lg:block text-base-content/90">Kembaran Ngadu</span>
                </a>
            </div>

            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 gap-1 text-sm font-bold text-base-content/80">
                    <li><a href="/" class="{{ request()->is('/') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all"><x-icon name="o-home" class="w-4 h-4" /> Beranda</a></li>
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <li><a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all"><x-icon name="o-chart-bar" class="w-4 h-4" /> Dashboard</a></li>
                    <li><a href="/admin/pengaduan" class="{{ request()->is('admin/pengaduan') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all"><x-icon name="o-inbox-stack" class="w-4 h-4" /> Kelola Aduan</a></li>
                    @elseif(auth()->user()->role === 'petugas')
                    <li><a href="/petugas/disposisi" class="{{ request()->is('petugas/disposisi') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all"><x-icon name="o-clipboard-document-check" class="w-4 h-4" /> Disposisi</a></li>
                    @else
                    <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all"><x-icon name="o-chart-pie" class="w-4 h-4" /> Dashboard</a></li>
                    @endif
                    <li><a href="/pengaduan/create" class="{{ request()->is('pengaduan/create') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all"><x-icon name="o-plus-circle" class="w-4 h-4" /> Buat Pengaduan</a></li>
                    @endauth
                </ul>
            </div>

            <div class="navbar-end gap-2">
                @auth
                @php
                $nameParts = explode(' ', auth()->user()->name);
                $initials = collect($nameParts)->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                @endphp
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost px-2 flex items-center gap-2 hover:bg-base-200/50 rounded-full transition-all border border-transparent hover:border-white/20">
                        <div class="avatar placeholder shadow-sm">
                            <div class="bg-primary text-white rounded-full w-9 h-9 flex items-center justify-center">
                                <span class="text-xs font-black">{{ strtoupper($initials) }}</span>
                            </div>
                        </div>
                        <x-icon name="o-chevron-down" class="w-3.5 h-3.5 opacity-50 hidden sm:block" />
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-5 z-[50] p-2 shadow-xl bg-base-100/95 backdrop-blur-lg rounded-2xl w-64 border border-white/20">
                        <li class="px-4 py-3 border-b border-base-200/50 mb-1 hover:bg-transparent pointer-events-none">
                            <div class="font-black text-base-content text-sm">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-base-content/60 opacity-80 break-all font-medium">{{ auth()->user()->email }}</div>
                        </li>
                        @if(auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard" class="py-2.5 rounded-xl font-bold"><x-icon name="o-squares-2x2" class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                        @else
                        <li><a href="/dashboard" class="py-2.5 rounded-xl font-bold"><x-icon name="o-squares-2x2" class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                        @endif
                        <li><a href="/profile" class="py-2.5 rounded-xl font-bold"><x-icon name="o-cog-6-tooth" class="w-4 h-4 opacity-70" /> Settings</a></li>
                        <div class="divider my-0 opacity-30"></div>
                        <li><a href="/logout" class="py-2.5 text-error hover:bg-error/10 hover:text-error rounded-xl font-bold"><x-icon name="o-arrow-right-start-on-rectangle" class="w-4 h-4" /> Log Out</a></li>
                    </ul>
                </div>
                @else
                <div class="flex items-center gap-2">
                    <a href="/login" class="btn btn-ghost btn-sm rounded-full font-bold px-4 hover:bg-base-200/50 border border-transparent hover:border-white/20">Masuk</a>
                </div>
                @endauth
            </div>
        </div>
    </div>
    {{-- Tambahin margin-top ekstra biar konten gak ketutup navbar yang melayang --}}
    <x-main full-width class="pt-6 sm:pt-10">
        <x-slot:content>
            @isset($header)
            <div class="px-4 py-4 mx-auto mb-6 shadow-sm max-w-7xl sm:px-6 lg:px-8 bg-base-100 rounded-2xl border border-base-200">
                {{ $header }}
            </div>
            @endisset

            <div class="mx-auto max-w-7xl">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    <x-toast />
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js').then(function (registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function (err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>