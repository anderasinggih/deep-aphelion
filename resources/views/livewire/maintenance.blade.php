<div wire:poll.5s="checkStatus" class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-6 font-sans">
    <div class="max-w-xl w-full">
        {{-- Card --}}
        <div class="bg-white p-10 md:p-14 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 text-center space-y-10">
            
            {{-- Logos --}}
            <div class="flex justify-center gap-8 items-center opacity-90">
                 <img src="{{ asset('storage/' . \App\Models\Setting::get('app_logo', 'assets/logobanyumas.png')) }}" class="h-14 object-contain" />
                 <img src="{{ asset('storage/' . \App\Models\Setting::get('app_logo_sekunder', 'assets/logokominfo.png')) }}" class="h-14 object-contain" />
            </div>

            {{-- Divider --}}
            <div class="w-16 h-1 bg-primary/20 mx-auto rounded-full"></div>

            {{-- Text Content --}}
            <div class="space-y-4">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pemeliharaan Sistem Berkala</h1>
                <p class="text-slate-500 leading-relaxed text-sm">
                    Layanan pengaduan masyarakat <strong>Kembaran Ngadu</strong> saat ini sedang dalam proses pemeliharaan rutin untuk meningkatkan stabilitas dan keamanan data. Kami akan segera kembali melayani Anda.
                </p>
            </div>

            {{-- Info Box --}}
            <div class="bg-slate-50 rounded-2xl p-6 flex items-center gap-5 text-left border border-slate-100">
                <div class="bg-primary/10 p-3 rounded-xl text-primary">
                    <x-icon name="o-information-circle" class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900 uppercase tracking-wider">Status Pemeliharaan</p>
                    <p class="text-xs text-slate-500 mt-0.5">Situs akan aktif kembali dalam waktu dekat.</p>
                </div>
            </div>

            {{-- Action --}}
            <div class="pt-4 flex flex-col items-center gap-6">
                <p class="text-[10px] text-slate-400 font-medium">© {{ date('Y') }} Pemerintah Kecamatan Kembaran</p>
                <a href="/login" class="text-[11px] font-bold text-slate-300 hover:text-primary transition-colors flex items-center gap-2">
                    <x-icon name="o-lock-closed" class="w-3 h-3" /> Akses Internal
                </a>
            </div>
        </div>
    </div>
</div>
