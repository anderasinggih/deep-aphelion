<footer class="bg-[#111] text-neutral-content relative z-20">
    <div class="border-t border-white/5"></div>
    <div class="container mx-auto max-w-7xl px-6 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center md:items-start gap-10 text-center md:text-left">

            {{-- Kiri: Logo + Info --}}
            <div class="flex flex-col items-center md:items-start gap-5 max-w-md">
                <div class="flex items-center gap-4">
                    {{-- Logo Banyumas --}}
                    <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Kabupaten Banyumas"
                        class="w-14 h-14 object-contain drop-shadow-md">

                    {{-- Logo Kominfo --}}
                    <div
                        class="bg-white rounded-2xl p-2 w-14 h-14 flex items-center justify-center shadow-md overflow-hidden shrink-0">
                        <img src="{{ asset('storage/assets/logokominfo.png') }}" alt="Kominfo"
                            class="w-full h-full object-contain">
                    </div>
                </div>

                <div>
                    <p class="font-black text-lg text-white leading-tight">Kembaran Ngadu</p>
                    <p class="font-bold text-sm text-neutral-content/60 leading-tight">Kecamatan Kembaran, Kab. Banyumas</p>
                    <p class="text-xs text-neutral-content/40 mt-3 leading-relaxed font-medium">
                        Jl. Kyai Kembar No. 17, Kembaran,<br>Kabupaten Banyumas, Jawa Tengah 53182
                    </p>
                </div>
            </div>

            {{-- Kanan: Link + Hak Cipta --}}
            <div class="flex flex-col items-center md:items-end">
                <nav class="flex items-center gap-6 mb-6">
                    <a href="{{ route('beranda') }}" wire:navigate class="text-xs text-neutral-content/60 hover:text-white transition-all font-black uppercase tracking-widest">Beranda</a>
                    <a href="{{ route('tentang-kami') }}" wire:navigate class="text-xs text-neutral-content/60 hover:text-white transition-all font-black uppercase tracking-widest">Tentang Kami</a>
                </nav>
                
                <div class="text-center md:text-right space-y-1">
                    <p class="text-xs text-neutral-content/40 font-bold">
                        &copy; {{ date('Y') }} Kecamatan Kembaran
                    </p>
                    <p class="text-[10px] text-neutral-content/20 uppercase tracking-widest font-black">
                        Sistem Pengaduan Masyarakat
                    </p>
                </div>
            </div>

        </div>
    </div>
</footer>