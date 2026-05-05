<!DOCTYPE html>
<html lang="id" data-theme="night">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server | Kembaran Ngadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-8">
        <div class="relative">
            <h1 class="text-[120px] font-black text-warning/10 leading-none select-none">500</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="p-5 bg-base-100 shadow-2xl rounded-[2.5rem] border border-base-300">
                    <x-icon name="o-cog" class="w-16 h-16 text-warning animate-spin" />
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <h2 class="text-2xl font-black text-base-content uppercase tracking-tight">Kesalahan Server Internal</h2>
            <p class="text-base-content/60 font-medium">Ups! Sesuatu yang salah terjadi di server kami. Tim teknis kami sedang berusaha memperbaikinya secepat mungkin.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <x-button label="Refresh Halaman" icon="o-arrow-path" class="btn-primary rounded-2xl px-8" onclick="window.location.reload()" />
            <x-button label="Beranda" icon="o-home" class="btn-ghost rounded-2xl" link="/" />
        </div>

        <div class="pt-8 opacity-30">
            <p class="text-xs font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Kembaran Ngadu</p>
        </div>
    </div>
</body>
</html>
