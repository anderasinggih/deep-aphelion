<div class="w-full max-w-7xl mx-auto px-1.5 lg:px-2 pt-8 sm:pt-12 pb-20">
    <div class="text-base-content/80">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="flex justify-center items-center gap-3 mb-5">
            <img src="{{ $app_logo ? asset('storage/' . $app_logo) : asset('storage/assets/logobanyumas.png') }}" alt="Logo App" class="w-12 h-12 sm:w-20 sm:h-20 object-contain">
            <div class="w-px h-10 sm:h-12 bg-base-300"></div>
            <div class="bg-white rounded-xl p-1 w-12 h-12 sm:p-1.5 sm:w-20 sm:h-20 flex items-center justify-center shadow-md border border-base-200">
                <img src="{{ $app_logo_sekunder ? asset('storage/' . $app_logo_sekunder) : asset('storage/assets/logokominfo.png') }}" alt="Logo Sekunder" class="w-full h-full object-contain">
            </div>
        </div>
        <h1 class="text-4xl sm:text-5xl font-bold text-primary leading-tight px-4 tracking-tight">Kembaran Ngadu</h1>
        <p class="text-xs sm:text-sm font-semibold text-base-content/50 mt-1">Sistem Informasi Pengaduan Masyarakat Kecamatan Kembaran</p>
        
        <div class="mt-6 p-4 bg-base-200/40 rounded-xl border border-base-200 max-w-2xl mx-auto">
            <p class="text-xs sm:text-sm text-base-content/70 leading-relaxed italic font-medium">
                "Selamat datang di portal resmi Pengaduan Masyarakat Kecamatan Kembaran. Platform ini merupakan wujud komitmen kami untuk menghadirkan pelayanan publik yang transparan, cepat, dan terintegrasi."
            </p>
        </div>
    </div>

    {{-- Public Statistics Section --}}
    <div class="grid grid-cols-3 gap-3 mb-8">
        <div class="bg-base-100 border border-base-200 p-3 rounded-xl text-center shadow-sm">
            <div class="w-7 h-7 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                <x-icon name="o-inbox-stack" class="w-4 h-4 text-primary" />
            </div>
            <p class="text-lg sm:text-2xl font-bold text-primary leading-tight">{{ $this->formatNumber($stats['total']) }}</p>
            <p class="text-[9px] font-semibold text-base-content/40 mt-1">Total Laporan</p>
        </div>
        <div class="bg-base-100 border border-base-200 p-3 rounded-xl text-center shadow-sm">
            <div class="w-7 h-7 bg-success/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                <x-icon name="o-check-badge" class="w-4 h-4 text-success" />
            </div>
            <p class="text-lg sm:text-2xl font-bold text-success leading-tight">{{ $this->formatNumber($stats['selesai']) }}</p>
            <p class="text-[9px] font-semibold text-base-content/40 mt-1">Laporan Selesai</p>
        </div>
        <div class="bg-base-100 border border-base-200 p-3 rounded-xl text-center shadow-sm">
            <div class="w-7 h-7 bg-warning/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                <x-icon name="o-star" class="w-4 h-4 text-warning" />
            </div>
            <p class="text-lg sm:text-2xl font-bold text-warning leading-tight">{{ number_format($stats['rating'], 1, ',', '.') }}</p>
            <p class="text-[9px] font-semibold text-base-content/40 mt-1">Rating IKM</p>
        </div>
    </div>

    {{-- Sekilas Kecamatan --}}
    <div class="bg-base-100 rounded-xl border border-base-200 shadow-sm overflow-hidden mb-6">
        <div class="p-5 sm:p-6 flex flex-col md:flex-row gap-6 items-center">
            <div class="flex-1">
                <h2 class="text-base sm:text-lg font-bold text-base-content mb-3 flex items-center gap-2">
                    <span class="w-1 h-5 bg-primary rounded-full"></span>
                    Sekilas Kecamatan Kembaran
                </h2>
                <p class="text-xs sm:text-sm text-base-content/70 leading-relaxed">
                    Kecamatan Kembaran adalah bagian administratif Kabupaten Banyumas, Jawa Tengah. Dengan luas wilayah <span class="font-bold">26,64 km²</span>, menaungi <span class="font-bold">16 desa</span> dengan populasi mencapai <span class="font-bold">82.897 jiwa</span>.
                </p>
            </div>
            <div class="w-full md:w-56 shrink-0 grid grid-cols-2 gap-2">
                <div class="p-3 bg-base-200/30 rounded-lg text-center border border-base-200/50">
                    <p class="text-[9px] font-bold text-base-content/30 mb-0.5">LUAS</p>
                    <p class="text-sm font-bold text-primary">26,6 km²</p>
                </div>
                <div class="p-3 bg-base-200/30 rounded-lg text-center border border-base-200/50">
                    <p class="text-[9px] font-bold text-base-content/30 mb-0.5">POPULASI</p>
                    <p class="text-sm font-bold text-primary">82 Ribu+</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        {{-- Profil Instansi --}}
        <div class="bg-base-100 rounded-xl border border-base-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                <x-icon name="o-building-office-2" class="w-4 h-4 text-primary" />
                <h2 class="font-bold text-xs sm:text-sm text-base-content">Profil Instansi</h2>
            </div>
            <div class="p-5 space-y-4 text-xs flex-1">
                <div class="flex items-start gap-3">
                    <x-icon name="o-identification" class="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    <div>
                        <p class="text-[9px] font-bold text-base-content/30 mb-0.5 uppercase">Nama Instansi</p>
                        <p class="font-semibold text-base-content">{{ $instansi_nama }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <x-icon name="o-map-pin" class="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    <div>
                        <p class="text-[9px] font-bold text-base-content/30 mb-0.5 uppercase">Alamat</p>
                        <p class="font-semibold text-base-content">{{ $instansi_alamat }}</p>
                        <p class="text-[10px] text-base-content/50">Kabupaten Banyumas, Jawa Tengah 53182</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <x-icon name="o-phone" class="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    <div>
                        <p class="text-[9px] font-bold text-base-content/30 mb-0.5 uppercase">Telepon</p>
                        <p class="font-semibold text-base-content">{{ $instansi_telepon }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <x-icon name="o-envelope" class="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    <div>
                        <p class="text-[9px] font-bold text-base-content/30 mb-0.5 uppercase">Email</p>
                        <p class="font-semibold text-base-content break-all">{{ $instansi_email }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jam Operasional --}}
        <div class="bg-base-100 rounded-xl border border-base-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                <x-icon name="o-clock" class="w-4 h-4 text-info" />
                <h2 class="font-bold text-xs sm:text-sm text-base-content">Jam Operasional</h2>
            </div>
            <div class="p-5 space-y-3 flex-1">
                <div class="flex items-center justify-between p-3 bg-base-200/20 rounded-lg border border-base-200/50">
                    <span class="text-xs font-medium text-base-content/70">Senin – Kamis</span>
                    <span class="text-xs font-bold text-primary">{{ $instansi_jam_senkam }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-base-200/20 rounded-lg border border-base-200/50">
                    <span class="text-xs font-medium text-base-content/70">Jumat</span>
                    <span class="text-xs font-bold text-primary">{{ $instansi_jam_jumat }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-error/5 rounded-lg border border-error/10">
                    <span class="text-xs font-medium text-error/70">Sabtu – Minggu</span>
                    <span class="text-xs font-bold text-error">Libur</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Prosedur & Dasar Hukum --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        {{-- Prosedur --}}
        <div class="space-y-6">
            <h2 class="text-lg font-bold text-base-content flex items-center gap-3">
                <x-icon name="o-arrow-path" class="w-5 h-5 text-success" />
                Prosedur Layanan
            </h2>
            <div class="space-y-5">
                <div class="flex gap-4 items-start relative">
                    <div class="absolute left-4 top-8 bottom-0 w-px bg-base-300"></div>
                    <div class="w-8 h-8 rounded-lg bg-success text-white flex items-center justify-center shrink-0 font-bold text-sm z-10">1</div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Kirim Laporan</p>
                        <p class="text-xs sm:text-[13px] text-base-content/60 leading-relaxed">Warga mengirimkan laporan keluhan atau aspirasi disertai bukti foto/dokumen pendukung.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start relative">
                    <div class="absolute left-4 top-8 bottom-0 w-px bg-base-300"></div>
                    <div class="w-8 h-8 rounded-lg bg-success text-white flex items-center justify-center shrink-0 font-bold text-sm z-10">2</div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Verifikasi Admin</p>
                        <p class="text-xs sm:text-[13px] text-base-content/60 leading-relaxed">Admin sistem memverifikasi keabsahan laporan dan mendisposisikan ke instansi/desa terkait.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start relative">
                    <div class="absolute left-4 top-8 bottom-0 w-px bg-base-300"></div>
                    <div class="w-8 h-8 rounded-lg bg-success text-white flex items-center justify-center shrink-0 font-bold text-sm z-10">3</div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Tindak Lanjut</p>
                        <p class="text-xs sm:text-[13px] text-base-content/60 leading-relaxed">Petugas lapangan melakukan penanganan dan penyelesaian masalah secara langsung di lokasi.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-8 h-8 rounded-lg bg-success text-white flex items-center justify-center shrink-0 font-bold text-sm z-10">4</div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Selesai</p>
                        <p class="text-xs sm:text-[13px] text-base-content/60 leading-relaxed">Laporan dinyatakan selesai, dan bukti penanganan diunggah kembali untuk transparansi.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dasar Hukum --}}
        <div class="space-y-6">
            <h2 class="text-lg font-bold text-base-content flex items-center gap-3">
                <x-icon name="o-scale" class="w-5 h-5 text-warning" />
                Dasar Hukum
            </h2>
            <div class="bg-base-200/30 rounded-2xl p-6 space-y-5 border border-base-200/50">
                <div class="flex gap-4 items-start border-b border-base-200 pb-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Undang-Undang No. 25 Tahun 2009</p>
                        <p class="text-[10px] text-base-content/50 font-bold tracking-wider">TENTANG PELAYANAN PUBLIK</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start border-b border-base-200 pb-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Undang-Undang No. 14 Tahun 2008</p>
                        <p class="text-[10px] text-base-content/50 font-bold tracking-wider">KETERBUKAAN INFORMASI PUBLIK</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start border-b border-base-200 pb-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-bold text-sm text-base-content">PP No. 96 Tahun 2012</p>
                        <p class="text-[10px] text-base-content/50 font-bold tracking-wider">PELAKSANAAN UU PELAYANAN PUBLIK</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-bold text-sm text-base-content">Permenpan-RB No. 24 Tahun 2014</p>
                        <p class="text-[10px] text-base-content/50 font-bold tracking-wider">PEDOMAN LAYANAN PENGADUAN</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center pt-10 border-t border-base-200 mt-12">
        <p class="text-sm text-base-content/50 mb-8">Sampaikan aspirasi Anda demi mewujudkan Kembaran yang lebih maju.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('beranda') }}" wire:navigate
                class="btn btn-primary btn-md rounded-xl font-bold shadow-lg hover:scale-105 transition-all px-8 w-full sm:w-auto">
                <x-icon name="o-megaphone" class="w-4 h-4" />
                Mulai Lapor Sekarang
            </a>
            <a href="https://maps.google.com/?q=Kantor+Kecamatan+Kembaran" target="_blank"
                class="btn btn-ghost btn-md rounded-xl font-bold border border-base-300 hover:bg-base-200 transition-all px-8 w-full sm:w-auto">
                <x-icon name="o-map" class="w-4 h-4" />
                Lokasi Kantor
            </a>
        </div>
    </div>

    </div>

</div>
