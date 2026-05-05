<div class="w-full max-w-7xl mx-auto px-1.5 lg:px-2">
    <style>


        .banner-paksa-atas {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            /* Tarik ke atas melampaui padding x-main (sesuaikan angka ini) */
            margin-top: -5rem !important;
        }

        @media (min-width: 1024px) {
            .banner-paksa-atas {
                margin-top: -6.5rem !important;
            }
        }
    </style>

    {{-- Banner Wrapper --}}
    <div class="overflow-hidden mb-0 banner-paksa-atas">
        <div class="absolute inset-0 z-0 bg-neutral">
            <img class="absolute inset-0 opacity-60 w-full h-full object-cover"
                src="{{ isset($settings['app_banner']) ? asset('storage/' . $settings['app_banner']) : asset('storage/assets/banner.jpg') }}">
            {{-- Tambahkan gradient agar teks lebih terbaca dan tidak terlalu putih --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-transparent"></div>
        </div>

        {{-- Padding Top (pt) di sini diperbesar agar Logo & Tulisan turun dari bawah Navbar --}}
        <div
            class="relative z-10 flex flex-col items-center justify-center text-center pt-32 pb-24 md:pt-44 md:pb-32 lg:pt-52 lg:pb-40 px-6 max-w-7xl mx-auto">

            <img src="{{ isset($settings['app_logo']) ? asset('storage/' . $settings['app_logo']) : asset('storage/assets/logobanyumas.png') }}"
                class="w-20 md:w-28 lg:w-32 h-auto mb-6 drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)]" alt="Logo" />

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-semibold text-white mb-4 tracking-tight drop-shadow-2xl">
                Selamat Datang
            </h1>

            <p
                class="text-sm md:text-lg lg:text-xl text-white/80 mb-10 max-w-2xl leading-relaxed font-medium drop-shadow-md">
                Layanan Pengaduan Masyarakat Kecamatan Kembaran.<br class="hidden sm:block" />
                Silakan klik tombol di bawah untuk mulai melaporkan masalah maupun aspirasi Anda.
            </p>

            {{-- Tombol Auth Tetap Sama --}}
            @auth
            <a href="/pengaduan/create" wire:navigate
                class="btn border-none text-white shadow-2xl bg-[#0085FF] hover:bg-white hover:text-[#0085FF] hover:-translate-y-1 px-10 md:px-14 py-4 rounded-full font-black text-sm md:text-lg transition-all duration-300">
                Mulai Pengaduan
            </a>
            @else
            <a href="/login" wire:navigate
                class="btn border-none text-white shadow-2xl bg-[#0085FF] hover:bg-white hover:text-[#0085FF] hover:-translate-y-1 px-10 md:px-14 py-4 rounded-full font-black text-sm md:text-lg transition-all duration-300">
                Mulai Pengaduan
            </a>
            @endauth
        </div>
    </div>

    {{-- Section Cek Status & SOP --}}
    <div class="w-full max-w-7xl mx-auto px-2 lg:px-4 mt-[-2rem] md:mt-[-3rem] relative z-20 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
            
            {{-- Lacak Laporan Card --}}
            <div class="bg-base-100 rounded-2xl shadow-xl border border-base-200 p-5 lg:p-6 flex flex-col justify-center">
                <h2 class="text-xl font-black text-primary mb-2 flex items-center gap-2">
                    <x-icon name="o-magnifying-glass" class="w-6 h-6" /> Lacak Laporan
                </h2>
                <p class="text-sm text-base-content/70 mb-5">Masukkan Kode Tracking untuk melihat status laporan Anda tanpa harus login.</p>
                
                <form wire:submit.prevent="lacakLaporan" class="flex flex-col gap-3">
                    <x-input wire:model="trackingCode" placeholder="Contoh: KMB-202405-0001" 
                        class="w-full bg-base-200" required />
                    <x-button type="submit" label="Cek Status" icon="o-arrow-right" 
                        class="btn-primary w-full text-white rounded-xl shadow-sm" spinner="lacakLaporan" />
                </form>

                @if (session()->has('error'))
                    <div class="mt-4 text-xs text-error font-medium flex items-center gap-1">
                        <x-icon name="o-exclamation-circle" class="w-4 h-4" /> {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- SOP Card --}}
            <div class="bg-base-100 rounded-2xl shadow-lg border border-base-200 p-5 lg:p-6 lg:col-span-2 flex flex-col justify-center">
                <h2 class="text-xl font-black text-base-content mb-4 flex items-center gap-2">
                    <x-icon name="o-information-circle" class="w-6 h-6 text-info" /> Standar Pelayanan Publik (SOP)
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-info/10 rounded-lg text-info shrink-0">
                            <x-icon name="o-clock" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-base-content">Waktu Pemrosesan</h3>
                            <p class="text-xs text-base-content/70 mt-1">{!! $sop_waktu_pemrosesan !!}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-success/10 rounded-lg text-success shrink-0">
                            <x-icon name="o-calendar" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-base-content">Jam Operasional</h3>
                            <p class="text-xs text-base-content/70 mt-1">{!! $sop_jam_operasional !!}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-warning/10 rounded-lg text-warning shrink-0">
                            <x-icon name="o-shield-check" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-base-content">Dasar Hukum & Privasi</h3>
                            <p class="text-xs text-base-content/70 mt-1">{!! $sop_dasar_hukum !!}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-primary/10 rounded-lg text-primary shrink-0">
                            <x-icon name="o-check-badge" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-base-content">Tindak Lanjut</h3>
                            <p class="text-xs text-base-content/70 mt-1">{!! $sop_tindak_lanjut !!}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="w-full px-1.5 py-6 mx-auto lg:px-2">
        @php
        $sortOptions = [
        ['id' => 'terbaru', 'nama' => 'Terbaru'],
        ['id' => 'terpopuler', 'nama' => 'Populer'],
        ];
        @endphp

        {{-- Search & Filter - Seamless Design --}}
        <form wire:submit.prevent class="mb-8 px-1 sm:px-0">
            <div class="flex flex-row items-center w-full gap-1.5 lg:gap-4">
                <div class="flex-1">
                    <x-input placeholder="Cari..." wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                        clearable />
                </div>
                <div class="w-28 sm:w-40 lg:w-48 shrink-0">
                    <x-select wire:model.live="kategori_id" :options="$kategoris" option-value="id" option-label="nama"
                        placeholder="Semua Kategori" icon="o-tag" />
                </div>
                <div class="w-24 sm:w-36 lg:w-40 shrink-0">
                    <x-select wire:model.live="sort" :options="$sortOptions" option-value="id" option-label="nama"
                        icon="o-arrows-up-down" />
                </div>
            </div>
        </form>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-3">
            @forelse($pengaduans as $pengaduan)
            <div
                class="flex flex-col overflow-hidden transition-all duration-300 transform border shadow-sm bg-base-100 rounded-2xl border-base-200 hover:shadow-lg hover:border-primary/50 hover:-translate-y-1 group">

                {{-- Klik Gambar ke Detail --}}
                @if($pengaduan->foto_bukti && count($pengaduan->foto_bukti) > 0)
                <a href="{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}" wire:navigate
                    class="relative w-full overflow-hidden bg-base-200 cursor-pointer" style="aspect-ratio: 1/1;">
                    <img src="{{ Storage::url($pengaduan->foto_bukti[0]) }}" alt="Bukti {{ $pengaduan->judul }}"
                        class="absolute inset-0 object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" />

                    <div class="absolute top-3 right-3 shadow-sm">
                        <span class="px-3 py-1 text-[10px] font-bold rounded-full 
                            {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                            {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                            {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                            {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }} shadow-sm">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>
                </a>
                @else
                {{-- Placeholder jika tidak ada foto --}}
                <a href="{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}" wire:navigate
                    class="relative w-full overflow-hidden bg-neutral cursor-pointer flex items-center justify-center group/placeholder" style="aspect-ratio: 1/1;">
                    
                    <div class="flex flex-col items-center justify-center opacity-20 group-hover/placeholder:scale-110 transition-transform duration-500">
                        <x-icon name="o-camera" class="w-12 h-12 text-neutral-content" />
                        <span class="text-[10px] font-bold mt-2 uppercase tracking-widest text-neutral-content">Tanpa Foto</span>
                    </div>

                    <div class="absolute top-3 right-3 shadow-sm">
                        <span class="px-3 py-1 text-[10px] font-bold rounded-full 
                            {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                            {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                            {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                            {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }} shadow-sm">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>
                </a>
                @endif

                <div class="flex flex-col flex-1 p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3 text-xs font-medium text-base-content/60">
                        {{-- Tambahkan 'truncate' dan 'max-w' agar tidak dorong-dorongan sama waktu --}}
                        <span class="px-1.5 py-1 rounded-md bg-base-200 text-primary min-w-0"
                            title="{{ $pengaduan->kategori->nama }}">
                            <span class="truncate">{{ $pengaduan->kategori->nama }}</span>
                        </span>

                        <span class="flex items-center gap-1 shrink-0 ml-2">
                            <x-icon name="o-clock" class="w-3.5 h-3.5" /> {{ $pengaduan->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Klik Judul ke Detail --}}
                    <h2
                        class="mb-1 text-base font-bold leading-tight transition-colors sm:text-lg text-base-content hover:text-primary line-clamp-2">
                        <a href="{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}" wire:navigate>
                            {{ $pengaduan->judul }}
                        </a>
                    </h2>

                    <div class="flex items-start gap-1 mb-3 text-xs text-base-content/50">
                        <x-icon name="o-map-pin" class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-error/80" />
                        <span class="line-clamp-1">{{ $pengaduan->lokasi_kejadian ?? 'Lokasi via koordinat peta'
                            }}</span>
                    </div>

                    <p class="flex-1 mb-4 text-sm leading-relaxed text-base-content/70 line-clamp-3">
                        {{ $pengaduan->deskripsi }}
                    </p>

                    <div class="flex items-center justify-between pt-4 mt-auto border-t border-base-200">
                            <div class="flex items-center gap-2 text-sm font-medium text-base-content/80">
                                @if($pengaduan->is_anonymous)
                                    <div class="avatar placeholder">
                                        <div class="bg-base-300 text-base-content/50 rounded-full w-8 h-8 shadow-sm flex items-center justify-center">
                                            <span class="text-[10px] font-black tracking-tighter">AN</span>
                                        </div>
                                    </div>
                                    <span class="line-clamp-1 max-w-[100px]">Anonim</span>
                                @else
                                    <x-user-avatar :user="$pengaduan->user" size="w-8 h-8" />
                                    <span class="line-clamp-1 max-w-[100px]">{{ $pengaduan->user->name }}</span>
                                @endif
                            </div>

                        {{-- Action Buttons (Gak ikutan link detail biar upvote tetep jalan) --}}
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                @if(session()->has('success') && session('id') == $pengaduan->id)
                                <span
                                    class="absolute right-0 bottom-full mb-1 whitespace-nowrap text-[10px] font-bold animate-bounce text-success">Berhasil!</span>
                                @endif
                                <button wire:click="upvote({{ $pengaduan->id }})"
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all duration-300 group/vote
                                    {{ session()->has('success') && session('id') == $pengaduan->id ? 'bg-primary text-white shadow-md scale-105' : 'bg-primary/10 text-primary ' }}">
                                    <x-icon name="o-hand-thumb-up" class="w-6 h-6" />
                                    <span class="text-sm font-black">{{ $pengaduan->dukungans_count }}</span>
                                </button>
                            </div>

                            <button 
                                onclick="if (navigator.share) { 
                                    navigator.share({ 
                                        title: '{{ addslashes($pengaduan->judul) }}', 
                                        text: 'Bantu dukung laporan ini di Kembaran Ngadu: {{ addslashes($pengaduan->judul) }}', 
                                        url: '{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}' 
                                    }).catch(console.error); 
                                } else { 
                                    window.open('https://wa.me/?text=' + encodeURIComponent('Bantu dukung laporan ini di Kembaran Ngadu: *{{ addslashes($pengaduan->judul) }}*. Cek di sini: {{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}'), '_blank');
                                }"
                                class="flex items-center justify-center w-9 h-9 transition-all duration-200 rounded-xl text-base-content/70 hover:bg-primary hover:text-white">
                                <x-icon name="o-share" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>


            </div>
            @empty
            {{-- Empty State --}}
            <div
                class="flex flex-col items-center justify-center py-20 text-center border-2 border-dashed lg:col-span-3 xl:col-span-4 2xl:col-span-3 bg-base-200/50 rounded-[1.5rem] border-base-300">
                <x-icon name="o-inbox" class="w-10 h-10 text-base-content/40 mb-4" />
                <h3 class="text-lg font-bold text-base-content/70">Belum ada laporan pengaduan</h3>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $pengaduans->links() }}
        </div>
    </div>

    {{-- Footer --}}
    <div class="relative w-[100vw] left-1/2 -ml-[50vw] mt-16">
        <x-footer />
    </div>
</div>