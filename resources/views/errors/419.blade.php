<!DOCTYPE html>
<html lang="id" data-theme="night">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Berakhir | Kembaran Ngadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-8">
        <div class="relative">
            <h1 class="text-[120px] font-black text-info/10 leading-none select-none">419</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="p-5 bg-base-100 shadow-2xl rounded-[2.5rem] border border-base-300">
                    <x-icon name="o-clock" class="w-16 h-16 text-info animate-pulse" />
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <h2 class="text-2xl font-black text-base-content uppercase tracking-tight">Sesi Berakhir</h2>
            <p class="text-base-content/60 font-medium">Halaman telah kedaluwarsa karena Anda terlalu lama tidak berinteraksi. Silakan segarkan halaman dan coba lagi.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <x-button label="Segarkan Halaman" icon="o-arrow-path" class="btn-primary rounded-2xl px-8" onclick="window.location.reload()" />
            <x-button label="Beranda" icon="o-home" class="btn-ghost rounded-2xl" link="/" />
        </div>

        <div class="pt-8 opacity-30">
            <p class="text-xs font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Kembaran Ngadu</p>
        </div>
    </div>
</body>
</html>
