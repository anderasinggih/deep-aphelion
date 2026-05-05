<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="night">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - Kembaran Ngadu' : 'Kembaran Ngadu' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    
    {{-- PWA Meta Tags --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Kembaran Ngadu">
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Lambang_Kabupaten_Banyumas.png/192px-Lambang_Kabupaten_Banyumas.png">

    @stack('styles')
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- Wrapper untuk ngatur posisi melayang (ada jarak dari atas dan samping) --}}
    <div class="sticky top-4 z-[100] w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all">

        {{-- Navbar utamanya dibikin melengkung, blur tinggi, dan ada border kaca --}}
        <div
            class="navbar bg-base-100/40 backdrop-blur-sm border border-white/30 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-[2rem] px-4 sm:px-6 min-h-[3.2rem] py-0">

            <div class="navbar-start ">
                <div class="dropdown ">
                    <div tabindex="0" role="button" class="btn btn-ghost lg:hidden rounded-full ">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content mt-5 z-[50] p-2 shadow-2xl bg-base-100 rounded-2xl w-64 border border-base-300">
                        <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}"><x-icon name="o-home"
                                     class="w-4 h-4" /> Beranda</a></li>
                        <li><a href="{{ route('tentang-kami') }}" wire:navigate class="{{ request()->is('tentang-kami') ? 'active' : '' }}"><x-icon name="o-information-circle"
                                     class="w-4 h-4" /> Tentang Kami</a></li>
                        @auth
                        @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                        <li><a href="/admin/dashboard"
                                class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"><x-icon
                                    name="o-chart-bar" class="w-4 h-4" /> Dashboard Admin</a></li>
                        <li><a href="/admin/pengaduan"
                                class="{{ request()->is('admin/pengaduan') ? 'active' : '' }}"><x-icon
                                    name="o-inbox-stack" class="w-4 h-4" /> Kelola Pengaduan</a></li>
                        
                        @if(auth()->user()->role === 'admin')
                        <li>
                            {{-- MOBILE: Hapus 'open', tambah class hidden arrow --}}
                            <details class="[&>summary::after]:hidden">
                                <summary
                                    class="{{ request()->is('admin/kategori') || request()->is('admin/users') ? 'active' : '' }}">
                                    <x-icon name="o-circle-stack" class="w-4 h-4" /> Data Set
                                </summary>
                                <ul>
                                    <li><a href="/admin/kategori"
                                            onclick="this.closest('details').removeAttribute('open')"
                                            class="{{ request()->is('admin/kategori') ? 'active' : '' }}"><x-icon
                                                name="o-folder-open" class="w-4 h-4" /> Kategori</a></li>
                                    <li><a href="/admin/users" onclick="this.closest('details').removeAttribute('open')"
                                            class="{{ request()->is('admin/users') ? 'active' : '' }}"><x-icon
                                                name="o-users" class="w-4 h-4" /> Pengguna</a></li>
                                    <div class="my-1 opacity-20 divider"></div>
                                    <li><a href="/admin/pengaturan" onclick="this.closest('details').removeAttribute('open')"
                                            class="{{ request()->is('admin/pengaturan') ? 'active' : '' }}"><x-icon
                                                name="o-cog-8-tooth" class="w-4 h-4" /> Pengaturan Web</a></li>
                                </ul>
                            </details>
                        </li>
                        @endif

                        @else
                        <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}"><x-icon
                                     name="o-chart-pie" class="w-4 h-4" /> Dashboard</a></li>
                        @endif

                        @endauth
                    </ul>
                </div>
                <a href="/"
                    class="text-xl font-bold text-brand flex items-center gap-2 lg:ml-2 whitespace-nowrap hover:scale-105 transition-transform">
                    @php
                        $appLogo = \App\Models\Setting::where('key', 'app_logo')->first()?->value;
                    @endphp
                    <img src="{{ $appLogo ? asset('storage/' . $appLogo) : asset('storage/assets/logobanyumas.png') }}" alt="Logo App"
                        class="w-7 h-7 object-contain drop-shadow-sm" />
                    <span class="hidden lg:block text-base-content/90">Kembaran Ngadu</span>
                </a>
            </div>

            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 gap-1 text-sm font-bold text-base-content/80">
                    <li><a href="/"
                            class="{{ request()->is('/') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                 name="o-home" class="w-4 h-4" /> Beranda</a></li>
                    <li><a href="{{ route('tentang-kami') }}" wire:navigate
                            class="{{ request()->is('tentang-kami') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                 name="o-information-circle" class="w-4 h-4" /> Tentang Kami</a></li>
                    @auth
                    @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                    <li><a href="/admin/dashboard"
                            class="{{ request()->is('admin/dashboard') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                name="o-chart-bar" class="w-4 h-4" /> Dashboard</a></li>
                    <li><a href="/admin/pengaduan"
                            class="{{ request()->is('admin/pengaduan') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                name="o-inbox-stack" class="w-4 h-4" /> Kelola Aduan</a></li>
                    
                    @if(auth()->user()->role === 'admin')
                    <li>
                        {{-- DESKTOP: Hapus 'open', tambah class hidden arrow --}}
                        <details class="[&>summary::after]:hidden">
                            <summary
                                class="{{ request()->is('admin/kategori') || request()->is('admin/users') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5">
                                <x-icon name="o-circle-stack" class="w-4 h-4" /> Data Set
                            </summary>
                            <ul class="p-2 bg-base-100 rounded-2xl shadow-xl w-48 mt-3 z-[100] border border-base-200">
                                <li><a href="/admin/kategori" onclick="this.closest('details').removeAttribute('open')"
                                        class="{{ request()->is('admin/kategori') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all mb-1"><x-icon
                                            name="o-folder-open" class="w-4 h-4" /> Kategori</a></li>
                                <li><a href="/admin/users" onclick="this.closest('details').removeAttribute('open')"
                                        class="{{ request()->is('admin/users') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                            name="o-users" class="w-4 h-4" /> Pengguna</a></li>
                                <div class="my-1 opacity-20 divider"></div>
                                <li><a href="/admin/pengaturan" onclick="this.closest('details').removeAttribute('open')"
                                        class="{{ request()->is('admin/pengaturan') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                            name="o-cog-8-tooth" class="w-4 h-4" /> Pengaturan Web</a></li>
                            </ul>
                        </details>
                    </li>
                    @endif

                    @else
                    <li><a href="/dashboard"
                            class="{{ request()->is('dashboard') ? 'active bg-base-200/50 text-primary shadow-sm' : 'hover:bg-base-200/30' }} rounded-xl transition-all py-1.5"><x-icon
                                name="o-chart-pie" class="w-4 h-4" /> Dashboard</a></li>
                    @endif

                    @endauth
                </ul>
            </div>

            <div class="navbar-end gap-2">
                @auth
                {{-- Notification Bell --}}
                @livewire('warga.notification-bell')

                @php
                $nameParts = explode(' ', auth()->user()->name);
                $initials = collect($nameParts)->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
                @endphp
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="avatar placeholder flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity ml-2">
                        <div class="bg-primary text-primary-content rounded-full w-8 h-8 shadow-md flex items-center justify-center">
                            <span class="text-[10px] font-black tracking-tighter">{{ $initials }}</span>
                        </div>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content mt-3 z-[100] p-2 shadow-2xl bg-base-100 rounded-2xl w-56 border border-base-200">
                        <li class="px-4 py-3 border-b border-base-200/50 mb-1 pointer-events-none">
                            <div class="flex flex-col w-full min-w-0 overflow-hidden items-start gap-0.5 !bg-transparent !p-0">
                                <span class="text-[10px] font-black text-primary uppercase">{{ auth()->user()->role }}</span>
                                <span class="font-bold text-sm truncate w-full text-base-content" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-base-content/60 truncate w-full font-medium" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
                            </div>
                        </li>
                        @if(auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard" class="py-2.5 rounded-xl font-bold"><x-icon name="o-squares-2x2"
                                    class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                        @else
                        <li><a href="/dashboard" class="py-2.5 rounded-xl font-bold"><x-icon name="o-squares-2x2"
                                    class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                        @endif
                        <li><a href="/profile" class="py-2.5 rounded-xl font-bold"><x-icon name="o-cog-6-tooth"
                                    class="w-4 h-4 opacity-70" /> Settings</a></li>
                        <div class="divider my-0 opacity-30"></div>
                        <li><a href="/logout"
                                class="py-2.5 text-error hover:bg-error/10 hover:text-error rounded-xl font-bold"><x-icon
                                    name="o-arrow-right-start-on-rectangle" class="w-4 h-4" /> Log Out</a></li>
                    </ul>
                </div>
                @else
                <div class="flex items-center gap-2">
                    <a href="/login"
                        class="btn btn-ghost btn-sm rounded-full font-bold px-4 hover:bg-base-200/50 border border-transparent hover:border-white/20">Masuk</a>
                </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- Gunakan main tag standar untuk kontrol penuh --}}
    <main class="w-full pt-6 sm:pt-10">
        @isset($header)
        <div class="px-4 py-4 mx-auto mb-6 shadow-sm max-w-7xl sm:px-6 lg:px-8 bg-base-100 rounded-2xl border border-base-200">
            {{ $header }}
        </div>
        @endisset

        {{ $slot }}
    </main>

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
    @stack('scripts')
</body>

</html>