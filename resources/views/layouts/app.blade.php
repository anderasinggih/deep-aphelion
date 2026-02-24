<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - Kembaran Ngadu' : 'Kembaran Ngadu' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50">

    <x-nav sticky class="lg:hidden bg-base-100 shadow-sm">
        <x-slot:brand>
            <div class="ml-5 pt-5">
                <span class="font-bold text-2xl text-primary">Kembaran Ngadu</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="w-8 h-8 cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    <x-main with-nav full-width>
        <x-slot:sidebar drawer="main-drawer" collapse-text="Tutup">
            <div class="hidden lg:block pt-5 pb-2 mb-4 px-4 border-b border-base-200 text-center">
                <div class="text-3xl font-black text-primary flex items-center justify-center gap-2">
                    <x-icon name="o-megaphone" class="w-8 h-8" />
                    Kembaran
                </div>
            </div>

            <x-menu activate-by-route>
                @if($user = auth()->user())
                <x-menu-separator />
                <x-list-item :item="$user" value="name" sub-value="role" no-separator no-hover
                    class="-mx-2 !-my-2 rounded text-base-content relative">
                    <x-slot:actions>
                        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="Sign Out"
                            no-wire-navigate link="/logout" />
                    </x-slot:actions>
                </x-list-item>
                <x-menu-separator />
                @endif

                <x-menu-item title="Beranda Publik" icon="o-home" link="/" />

                @auth
                @if(auth()->user()->role === 'admin')
                <x-menu-sub title="Admin Panel" icon="o-cog-6-tooth">
                    <x-menu-item title="Dashboard Admin" icon="o-chart-bar" link="/admin/dashboard" />
                    <x-menu-item title="Manajemen Pengaduan" icon="o-inbox-stack" link="/admin/pengaduan" />
                </x-menu-sub>
                @elseif(auth()->user()->role === 'petugas')
                <x-menu-item title="Tugas Disposisi" icon="o-clipboard-document-check" link="/petugas/disposisi" />
                @endif
                <x-menu-item title="Dashboard Warga" icon="o-user" link="/dashboard" />
                <x-menu-item title="Buat Pengaduan" icon="o-plus-circle" link="/pengaduan/create" />
                @else
                <x-menu-item title="Login" icon="o-arrow-right-on-rectangle" link="/login" />
                <x-menu-item title="Registrasi" icon="o-user-plus" link="/register" />
                @endauth
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            {{ $slot }}
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