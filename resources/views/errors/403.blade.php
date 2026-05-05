<!DOCTYPE html>
<html lang="id" data-theme="night">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Kembaran Ngadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-8">
        <div class="relative">
            <h1 class="text-[120px] font-black text-error/10 leading-none select-none">403</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="p-5 bg-base-100 shadow-2xl rounded-[2.5rem] border border-base-300">
                    <x-icon name="o-shield-exclamation" class="w-16 h-16 text-error animate-bounce" />
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <h2 class="text-2xl font-black text-base-content uppercase tracking-tight">Akses Terbatas</h2>
            <p class="text-base-content/60 font-medium">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi admin jika Anda rasa ini adalah kesalahan.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <x-button label="Kembali ke Beranda" icon="o-home" class="btn-primary rounded-2xl px-8" link="/" />
            <x-button label="Hubungi Kami" icon="o-envelope" class="btn-ghost rounded-2xl" link="/tentang-kami" />
        </div>

        <div class="pt-8 opacity-30">
            <p class="text-xs font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Kembaran Ngadu</p>
        </div>
    </div>
</body>
</html>
