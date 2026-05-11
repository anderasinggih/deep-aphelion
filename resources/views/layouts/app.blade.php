<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="night">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
    <link rel="apple-touch-icon" href="{{ asset('storage/assets/logobanyumas.png') }}">
    
    @stack('meta')

    @stack('styles')
    <style>
        [wire\:loading], [wire\:loading\.delay], [wire\:loading\.delay\.long] {
            display: none !important;
        }
        html {
            height: 100%;
            overflow-x: clip;
        }
        body {
            touch-action: manipulation;
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            overflow-x: clip;
        }
        .toast {
            z-index: 2000 !important;
        }
        /* Hide details triangle */
        summary::-webkit-details-marker {
            display: none !important;
        }
        summary {
            list-style: none !important;
        }
    </style>

</head>

<body class="font-sans antialiased bg-base-200 flex flex-col">

    {{-- Wrapper untuk ngatur posisi melayang (ada jarak dari atas dan samping) --}}
    <div class="sticky top-4 z-[1000] w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all print:hidden">

        {{-- Navbar utamanya dibikin melengkung, blur tinggi, dan ada border kaca --}}
        <div
            class="navbar bg-base-100/40 backdrop-blur-sm border border-white/30 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-[2rem] px-4 sm:px-6 min-h-[3.2rem] py-0">

            <div class="navbar-start ">
                <details class="dropdown">
                    <summary class="btn btn-ghost lg:hidden rounded-full list-none [&::-webkit-details-marker]:hidden">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </summary>
                    <ul onclick="if(event.target.closest('a')) this.closest('details').removeAttribute('open')"
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
                </details>
                <a href="/"
                    class="text-xl font-bold text-brand flex items-center gap-2 lg:ml-2 whitespace-nowrap hover:scale-105 transition-transform">
                    @php
                        $appLogo = \App\Models\Setting::get('app_logo');
                    @endphp
                    <img src="{{ $appLogo ? asset('storage/' . $appLogo) : asset('storage/assets/logobanyumas.png') }}" alt="Logo App"
                        class="w-7 h-7 object-contain drop-shadow-sm" />
                    <span class="hidden lg:block text-base-content/90">Kembaran Ngadu</span>
                </a>

            </div>

            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 gap-1 text-sm font-bold text-base-content/80">
                    <li><a href="/" wire:navigate
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
                <details class="dropdown dropdown-end">
                    <summary class="avatar placeholder flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity ml-2 list-none [&::-webkit-details-marker]:hidden">
                        <div class="bg-primary text-primary-content rounded-full w-8 h-8 shadow-md flex items-center justify-center">
                            <span class="text-[10px] font-black tracking-tighter">{{ $initials }}</span>
                        </div>
                    </summary>
                <ul onclick="if(event.target.closest('a')) this.closest('details').removeAttribute('open')"
                    class="menu menu-sm dropdown-content mt-3 z-[100] p-2 shadow-2xl bg-base-100 rounded-2xl w-64 border border-base-200">
                    <li class="px-4 py-3 border-b border-base-200/50 mb-1 pointer-events-none">
                        <div class="flex flex-col w-full min-w-0 overflow-hidden items-start gap-0.5 !bg-transparent !p-0">
                            <span class="text-[10px] font-black text-primary uppercase leading-none mb-1">{{ auth()->user()->role }}</span>
                            <span class="font-black text-sm truncate w-full text-base-content" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] text-base-content/60 truncate w-full font-medium block" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
                        </div>
                    </li>
                        @if(auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard" wire:navigate class="py-2.5 rounded-xl font-bold"><x-icon name="o-squares-2x2"
                                    class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                        @else
                        <li><a href="/dashboard" wire:navigate class="py-2.5 rounded-xl font-bold"><x-icon name="o-squares-2x2"
                                    class="w-4 h-4 opacity-70" /> Dashboard</a></li>
                        @endif
                        <li><a href="/profile" wire:navigate class="py-2.5 rounded-xl font-bold"><x-icon name="o-cog-6-tooth"
                                    class="w-4 h-4 opacity-70" /> Settings</a></li>
                        <div class="divider my-0 opacity-30"></div>
                        <li><a href="/logout"
                                class="py-2.5 text-error hover:bg-error/10 hover:text-error rounded-xl font-bold"><x-icon
                                    name="o-arrow-right-start-on-rectangle" class="w-4 h-4" /> Log Out</a></li>
                    </ul>
                </details>
                @else

                <div class="flex items-center gap-2">
                    <a href="/login"
                        class="btn btn-ghost btn-sm rounded-full font-bold px-4 hover:bg-base-200/50 border border-transparent hover:border-white/20">Masuk</a>
                </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="flex-grow w-full pt-6 sm:pt-10">
        @isset($header)
        <div class="px-4 py-4 mx-auto mb-6 shadow-sm max-w-7xl sm:px-6 lg:px-8 bg-base-100 rounded-2xl border border-base-200">
            {{ $header }}
        </div>
        @endisset

        {{ $slot }}
    </main>

    {{-- Centralized Footer --}}
    @if(!request()->routeIs('admin.*'))
    <div class="print:hidden">
        <x-footer />
    </div>
    @endif

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
        // Global Share Function
        function nativeShare(title, text, url) {
            // Check if native share is available (usually requires HTTPS)
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: text,
                    url: url
                })
                .catch((error) => {
                    if (error.name !== 'AbortError') {
                        openShareModal(title, text, url);
                    }
                });
            } else {
                // Fallback to custom modal instead of direct WA
                openShareModal(title, text, url);
            }
        }

        function openShareModal(title, text, url) {
            const modal = document.getElementById('global_share_modal');
            const waBtn = document.getElementById('share_wa_btn');
            const copyBtn = document.getElementById('share_copy_btn');
            
            // Set WhatsApp link
            waBtn.onclick = () => {
                window.open(`https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, '_blank');
            };
            
            // Set Copy link
            copyBtn.onclick = () => {
                navigator.clipboard.writeText(url).then(() => {
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<x-icon name="o-check" class="w-5 h-5" /> Tersalin!';
                    copyBtn.classList.add('btn-success');
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.classList.remove('btn-success');
                    }, 2000);
                });
            };
            
            modal.showModal();
        }

        // Scroll Restoration for Livewire Navigate
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        document.addEventListener('livewire:navigating', () => {
            sessionStorage.setItem('scroll_' + window.location.href, window.scrollY);
            sessionStorage.setItem('spa_navigating', 'true');
        });

        document.addEventListener('livewire:navigated', () => {
            // Always scroll to top on any navigation
            window.scrollTo(0, 0);
            
            sessionStorage.removeItem('spa_navigating');
        });

        // --- FAIL-SAFE DROPDOWN SYNC ---
        // Ensure only one <details> is open at a time and close on outside click
        window.addEventListener('click', function (e) {
            const summary = e.target.closest('summary');
            const details = e.target.closest('details');
            
            if (summary && details) {
                // If we are opening a NEW one
                if (!details.open) {
                    document.querySelectorAll('details').forEach(d => {
                        if (d !== details) d.removeAttribute('open');
                    });
                }
            } else if (!details) {
                // Clicking outside: Close all
                document.querySelectorAll('details').forEach(d => d.removeAttribute('open'));
            }
        }, { capture: true });
        // ---------------------------------
    </script>

    {{-- Global Share Modal --}}
    <dialog id="global_share_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 p-0 overflow-hidden border-t sm:border border-base-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-black text-xl">Bagikan Laporan</h3>
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </form>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button id="share_wa_btn" class="btn btn-lg h-24 flex-col gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white border-none transition-all hover:scale-95">
                        <x-icon name="o-chat-bubble-left-right" class="w-8 h-8" />
                        <span class="text-xs font-bold uppercase tracking-wider">WhatsApp</span>
                    </button>
                    
                    <button id="share_copy_btn" class="btn btn-lg h-24 flex-col gap-2 bg-base-200 hover:bg-base-300 border-none transition-all hover:scale-95">
                        <x-icon name="o-link" class="w-8 h-8" />
                        <span class="text-xs font-bold uppercase tracking-wider">Salin Link</span>
                    </button>
                </div>
            </div>
            
            <div class="bg-base-200/50 p-4 text-center">
                <p class="text-[10px] uppercase tracking-[0.2em] font-bold opacity-40">Bantu suarakan aspirasi warga</p>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-black/40">
            <button>close</button>
        </form>
    </dialog>

    @livewireScripts
    @stack('scripts')
</body>

</html>