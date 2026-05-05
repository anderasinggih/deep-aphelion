<div class="max-w-4xl mx-auto px-4 py-8 sm:py-14">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="flex justify-center items-center gap-3 mb-4">
            <img src="{{ $app_logo ? asset('storage/' . $app_logo) : asset('storage/assets/logobanyumas.png') }}" alt="Logo App" class="w-12 h-12 sm:w-16 sm:h-16 object-contain"
                onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Lambang_Kabupaten_Banyumas.png/400px-Lambang_Kabupaten_Banyumas.png'">
            <div class="w-px h-10 bg-base-300"></div>
            <div class="bg-white rounded-xl p-1.5 w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center shadow-sm border border-base-200">
                <img src="{{ asset('storage/assets/logokominfo.png') }}" alt="Kominfo" class="w-full h-full object-contain">
            </div>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-primary leading-tight px-4">Tentang Kembaran Ngadu</h1>
        <p class="mt-2 text-base-content/60 text-xs sm:text-base max-w-xl mx-auto px-6">
            Sistem Pengaduan Masyarakat Online Kecamatan Kembaran, Kabupaten Banyumas
        </p>
    </div>

    {{-- Public Statistics Section --}}
    <div class="grid grid-cols-3 gap-1.5 sm:gap-4 mb-6 sm:mb-10">
        <div class="bg-primary/5 border border-primary/10 p-2 sm:p-5 rounded-xl sm:rounded-2xl text-center shadow-sm">
            <div class="w-7 h-7 sm:w-10 sm:h-10 bg-primary/10 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-1.5 sm:mb-3">
                <x-icon name="o-inbox-stack" class="w-4 h-4 sm:w-6 sm:h-6 text-primary" />
            </div>
            <p class="text-lg sm:text-3xl font-black text-primary leading-tight">{{ $stats['total'] }}</p>
            <p class="text-[8px] sm:text-xs font-bold text-base-content/50 uppercase tracking-tighter mt-0.5 sm:mt-1">Total Laporan</p>
        </div>
        <div class="bg-success/5 border border-success/10 p-2 sm:p-5 rounded-xl sm:rounded-2xl text-center shadow-sm">
            <div class="w-7 h-7 sm:w-10 sm:h-10 bg-success/10 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-1.5 sm:mb-3">
                <x-icon name="o-check-badge" class="w-4 h-4 sm:w-6 sm:h-6 text-success" />
            </div>
            <p class="text-lg sm:text-3xl font-black text-success leading-tight">{{ $stats['selesai'] }}</p>
            <p class="text-[8px] sm:text-xs font-bold text-base-content/50 uppercase tracking-tighter mt-0.5 sm:mt-1">Laporan Selesai</p>
        </div>
        <div class="bg-warning/5 border border-warning/10 p-2 sm:p-5 rounded-xl sm:rounded-2xl text-center shadow-sm">
            <div class="w-7 h-7 sm:w-10 sm:h-10 bg-warning/10 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-1.5 sm:mb-3">
                <x-icon name="o-star" class="w-4 h-4 sm:w-6 sm:h-6 text-warning" />
            </div>
            <p class="text-lg sm:text-3xl font-black text-warning leading-tight">{{ $stats['rating'] }}</p>
            <p class="text-[8px] sm:text-xs font-bold text-base-content/50 uppercase tracking-tighter mt-0.5 sm:mt-1">Rating Kepuasan</p>
        </div>
    </div>

    {{-- Profil Instansi --}}
    <div class="bg-base-100 rounded-2xl border border-base-200 shadow-sm overflow-hidden mb-5">
        <div class="px-4 py-3 bg-primary/5 border-b border-base-200 flex items-center gap-2">
            <x-icon name="o-building-office-2" class="w-4 h-4 text-primary" />
            <h2 class="font-bold text-sm sm:text-base text-base-content">Profil Instansi</h2>
        </div>
        <div class="p-4 sm:p-6 space-y-3 text-xs sm:text-sm">
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-3">
                <span class="font-semibold text-base-content/60 w-32 shrink-0 uppercase tracking-wider text-[10px] sm:text-xs">Nama Instansi</span>
                <span class="text-base-content font-medium">{{ $instansi_nama }}</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-3">
                <span class="font-semibold text-base-content/60 w-32 shrink-0 uppercase tracking-wider text-[10px] sm:text-xs">Kabupaten</span>
                <span class="text-base-content font-medium">Kabupaten Banyumas, Jawa Tengah</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-3">
                <span class="font-semibold text-base-content/60 w-32 shrink-0 uppercase tracking-wider text-[10px] sm:text-xs">Alamat</span>
                <span class="text-base-content font-medium">{{ $instansi_alamat }}</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-3">
                <span class="font-semibold text-base-content/60 w-32 shrink-0 uppercase tracking-wider text-[10px] sm:text-xs">Telepon</span>
                <span class="text-base-content font-medium">{{ $instansi_telepon }}</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-3">
                <span class="font-semibold text-base-content/60 w-32 shrink-0 uppercase tracking-wider text-[10px] sm:text-xs">Email</span>
                <span class="text-base-content font-medium">{{ $instansi_email }}</span>
            </div>
        </div>
    </div>

    {{-- Jam Operasional + Prosedur --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-5">
        <div class="bg-base-100 rounded-2xl border border-base-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-info/5 border-b border-base-200 flex items-center gap-2">
                <x-icon name="o-clock" class="w-4 h-4 text-info" />
                <h2 class="font-bold text-sm sm:text-base text-base-content">Jam Operasional Layanan</h2>
            </div>
            <div class="p-4 space-y-2 text-xs sm:text-sm">
                <div class="flex justify-between items-center py-1">
                    <span class="text-base-content/60">Senin – Kamis</span>
                    <span class="font-bold text-primary">{{ $instansi_jam_senkam }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-base-content/60">Jumat</span>
                    <span class="font-bold text-primary">{{ $instansi_jam_jumat }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-base-content/60">Sabtu – Minggu</span>
                    <span class="font-bold text-error uppercase tracking-tighter">{{ $instansi_jam_sabtu }}</span>
                </div>
                <div class="mt-2 pt-3 border-t border-base-200 text-[10px] sm:text-xs text-base-content/50 leading-relaxed italic">
                    Laporan online 24/7. Tindak lanjut dilakukan sesuai hari & jam kerja resmi instansi.
                </div>
            </div>
        </div>

        <div class="bg-base-100 rounded-2xl border border-base-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-success/5 border-b border-base-200 flex items-center gap-2">
                <x-icon name="o-arrow-path" class="w-4 h-4 text-success" />
                <h2 class="font-bold text-sm sm:text-base text-base-content">Prosedur Layanan</h2>
            </div>
            <div class="p-4 space-y-2.5 text-xs sm:text-sm">
                <div class="flex gap-2.5 items-start">
                    <span class="badge badge-success badge-sm h-5 min-w-[20px] font-black shrink-0 text-[10px]">1</span>
                    <span class="text-base-content/70 leading-tight">Warga mengirimkan laporan disertai bukti foto/dokumen.</span>
                </div>
                <div class="flex gap-2.5 items-start">
                    <span class="badge badge-success badge-sm h-5 min-w-[20px] font-black shrink-0 text-[10px]">2</span>
                    <span class="text-base-content/70 leading-tight">Admin memverifikasi dan mendisposisikan ke instansi terkait.</span>
                </div>
                <div class="flex gap-2.5 items-start">
                    <span class="badge badge-success badge-sm h-5 min-w-[20px] font-black shrink-0 text-[10px]">3</span>
                    <span class="text-base-content/70 leading-tight">Petugas lapangan melakukan penanganan masalah di lokasi.</span>
                </div>
                <div class="flex gap-2.5 items-start">
                    <span class="badge badge-success badge-sm h-5 min-w-[20px] font-black shrink-0 text-[10px]">4</span>
                    <span class="text-base-content/70 leading-tight">Laporan selesai dan bukti penanganan diunggah ke sistem.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Dasar Hukum --}}
    <div class="bg-base-100 rounded-2xl border border-base-200 shadow-sm overflow-hidden mb-8">
        <div class="px-4 py-3 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
            <x-icon name="o-scale" class="w-4 h-4 text-base-content/60" />
            <h2 class="font-bold text-sm sm:text-base text-base-content">Dasar Hukum</h2>
        </div>
        <div class="p-4 sm:p-5">
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-[11px] sm:text-sm text-base-content/70">
                <li class="flex gap-2">
                    <span class="text-primary font-bold shrink-0">•</span>
                    <span>UU No. 25/2009 (Pelayanan Publik)</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-primary font-bold shrink-0">•</span>
                    <span>UU No. 14/2008 (Informasi Publik)</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-primary font-bold shrink-0">•</span>
                    <span>PP No. 96/2012 (Pelaksanaan UU)</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-primary font-bold shrink-0">•</span>
                    <span>Permenpan-RB No. 24/2014</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center pb-6">
        <p class="text-[10px] sm:text-sm text-base-content/40 mb-3">Sampaikan aspirasi Anda untuk kemajuan bersama</p>
        <a href="{{ route('beranda') }}" wire:navigate
            class="btn btn-primary btn-md rounded-xl font-black shadow-lg hover:scale-105 transition-transform px-8">
            <x-icon name="o-megaphone" class="w-4 h-4" />
            MULAI LAPOR
        </a>
    </div>

</div>
