<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - Kembaran Ngadu' : 'Kembaran Ngadu' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Leaflet Global -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @stack('styles')
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">

    <!-- Top Navbar -->
    <div class="navbar bg-white/5 backdrop-blur-lg sticky top-0 z-[100] shadow-md border- sm:px-8 transition-all">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <x-icon name="o-bars-3" class="w-5 h-5" />
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content mt-3 z-[50] p-2 shadow bg-base-100 rounded-box w-52 border border-base-200">
                    <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}"><x-icon name="o-home"
                                class="w-4 h-4" /> Beranda Publik</a></li>
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <li><a href="/admin/dashboard"
                            class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"><x-icon name="o-chart-bar"
                                class="w-4 h-4" /> Dashboard Admin</a></li>
                    <li><a href="/admin/pengaduan"
                            class="{{ request()->is('admin/pengaduan') ? 'active' : '' }}"><x-icon name="o-inbox-stack"
                                class="w-4 h-4" /> Kelola Pengaduan</a></li>
                    @elseif(auth()->user()->role === 'petugas')
                    <li><a href="/petugas/disposisi"
                            class="{{ request()->is('petugas/disposisi') ? 'active' : '' }}"><x-icon
                                name="o-clipboard-document-check" class="w-4 h-4" /> Disposisi</a></li>
                    @else
                    <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}"><x-icon
                                name="o-chart-pie" class="w-4 h-4" /> Dashboard Warga</a></li>
                    @endif
                    <li><a href="/pengaduan/create"
                            class="{{ request()->is('pengaduan/create') ? 'active' : '' }}"><x-icon name="o-plus-circle"
                                class="w-4 h-4" /> Buat Pengaduan</a></li>
                    @endauth
                </ul>
            </div>
            <a href="/" class="text-xl font-bold text-brand flex items-center gap-2 lg:ml-0 whitespace-nowrap">
                <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                    class="w-9 h-9 object-contain" />
                <span class="hidden lg:block">Kembaran Ngadu</span>
            </a>
        </div>

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1 gap-1 text-sm font-medium text-base-content/80">
                <li><a href="/"
                        class="{{ request()->is('/') ? 'active bg-base-200 text-base-content' : '' }} rounded-lg"><x-icon
                            name="o-home" class="w-4 h-4" /> Beranda</a></li>
                @auth
                @if(auth()->user()->role === 'admin')
                <li><a href="/admin/dashboard"
                        class="{{ request()->is('admin/dashboard') ? 'active bg-base-200 text-base-content' : '' }} rounded-lg"><x-icon
                            name="o-chart-bar" class="w-4 h-4" /> Dashboard</a></li>
                <li><a href="/admin/pengaduan"
                        class="{{ request()->is('admin/pengaduan') ? 'active bg-base-200 text-base-content' : '' }} rounded-lg"><x-icon
                            name="o-inbox-stack" class="w-4 h-4" /> Kelola Aduan</a></li>
                @elseif(auth()->user()->role === 'petugas')
                <li><a href="/petugas/disposisi"
                        class="{{ request()->is('petugas/disposisi') ? 'active bg-base-200 text-base-content' : '' }} rounded-lg"><x-icon
                            name="o-clipboard-document-check" class="w-4 h-4" /> Disposisi</a></li>
                @else
                <li><a href="/dashboard"
                        class="{{ request()->is('dashboard') ? 'active bg-base-200 text-base-content' : '' }} rounded-lg"><x-icon
                            name="o-chart-pie" class="w-4 h-4" /> Dashboard</a></li>
                @endif
                <li><a href="/pengaduan/create"
                        class="{{ request()->is('pengaduan/create') ? 'active bg-base-200 text-base-content' : '' }} rounded-lg"><x-icon
                            name="o-plus-circle" class="w-4 h-4" /> Buat Pengaduan</a></li>
                @endauth
            </ul>
        </div>

        <div class="navbar-end">
            @auth
            @php
            // Build User Initials
            $nameParts = explode(' ', auth()->user()->name);
            $initials = collect($nameParts)->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
            @endphp
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button"
                    class="btn btn-ghost px-2 flex items-center gap-2 hover:bg-base-200/50 rounded-lg">
                    <div class="avatar placeholder">
                        <div class="bg-base-200 text-base-content rounded-md w-8 h-8 flex items-center justify-center">
                            <span class="text-xs font-bold">{{ strtoupper($initials) }}</span>
                        </div>
                    </div>
                    <x-icon name="o-chevron-down" class="w-3.5 h-3.5 opacity-50 hidden sm:block" />
                </div>
                <!-- Profile Menu -->
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content mt-3 z-[50] p-2 shadow-lg bg-base-100 rounded-box w-64 border border-base-200">
                    <li class="px-4 py-3 border-b border-base-200 mb-1 hover:bg-transparent pointer-events-none">
                        <div class="font-bold text-base-content text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-base-content/60 opacity-80 break-all">{{ auth()->user()->email }}</div>
                    </li>
                    @if(auth()->user()->role === 'admin')
                    <li><a href="/admin/dashboard" class="py-2"><x-icon name="o-squares-2x2"
                                class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                    @else
                    <li><a href="/dashboard" class="py-2"><x-icon name="o-squares-2x2" class="w-4 h-4 opacity-70" />
                            Dashboard</a></li>
                    @endif
                    <li><a href="/profile" class="py-2"><x-icon name="o-cog-6-tooth" class="w-4 h-4 opacity-70" />
                            Settings</a></li>
                    <div class="divider my-0"></div>
                    <li><a href="/logout" class="py-2 text-error hover:bg-error/10"><x-icon
                                name="o-arrow-right-start-on-rectangle" class="w-4 h-4" /> Log Out</a></li>
                </ul>
            </div>
            @else
            <div class=" sm:flex gap-2">
                <!-- <div class="div"><a href="/register" class="btn btn-primary btn-sm">Daftar</a> </div> -->
                <div class="div"><a href="/login" class="btn btn-ghost btn-sm">Masuk</a></div>
            </div>

            @endauth
        </div>
    </div>

    <!-- Main Content Area -->
    <x-main full-width class="pt-4">
        <x-slot:content>
            @isset($header)
            <div
                class="px-4 py-4 mx-auto mb-6 shadow-sm max-w-7xl sm:px-6 lg:px-8 bg-base-100 rounded-2xl border border-base-200">
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
    viceWorker' in navigator) {
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