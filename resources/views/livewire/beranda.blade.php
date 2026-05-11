<div class="w-full max-w-7xl mx-auto px-1.5 lg:px-2">
    <style>


        [wire\:loading], [wire\:loading\.delay] {
            display: none !important;
        }

        .banner-paksa-atas {
            position: relative;
            /* Safe breakout: Expand to edges of px-1.5 mobile padding */
            margin-left: -0.375rem; 
            margin-right: -0.375rem;
            width: auto;
            /* Tarik ke atas melampaui padding x-main (sesuaikan angka ini) */
            margin-top: -5rem !important;
            overflow-x: clip;
        }

        @media (min-width: 1024px) {
            .banner-paksa-atas {
                margin-left: -0.5rem; /* Align with lg:px-2 */
                margin-right: -0.5rem;
            }
        }

        @media (min-width: 1024px) {
            .banner-paksa-atas {
                margin-top: -6.5rem !important;
            }
        }
    </style>

    {{-- Banner Wrapper with Slideshow --}}
    @php
        $banners = [];
        if(isset($settings['app_banner_1'])) $banners[] = asset('storage/' . $settings['app_banner_1']);
        if(isset($settings['app_banner_2'])) $banners[] = asset('storage/' . $settings['app_banner_2']);
        if(isset($settings['app_banner_3'])) $banners[] = asset('storage/' . $settings['app_banner_3']);
        
        // Fallback if empty
        if(empty($banners)) $banners[] = asset('storage/assets/banner.jpg');
    @endphp

    <div class="overflow-hidden mb-0 banner-paksa-atas min-h-[400px] md:min-h-[500px] lg:min-h-[600px] flex items-center justify-center relative">
        {{-- Single Banner Background --}}
        <div class="absolute inset-0 z-0 bg-neutral">
            <img 
                src="{{ $banners[0] }}" 
                class="absolute inset-0 w-full h-full object-cover opacity-60"
            >
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col items-center justify-center text-center pt-32 pb-24 md:pt-44 md:pb-32 lg:pt-52 lg:pb-40 px-6 max-w-7xl mx-auto w-full">
            <img src="{{ isset($settings['app_logo']) ? asset('storage/' . $settings['app_logo']) : asset('storage/assets/logobanyumas.png') }}"
                class="w-16 md:w-28 lg:w-32 h-auto mb-6 drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)]" alt="Logo" />

            <h1 class="text-3xl md:text-6xl lg:text-7xl font-semibold text-white mb-4 tracking-tight drop-shadow-2xl">
                Selamat Datang
            </h1>

            <p class="text-xs md:text-lg lg:text-xl text-white/80 mb-10 max-w-2xl leading-relaxed font-medium drop-shadow-md">
                Layanan Pengaduan Masyarakat Kecamatan Kembaran.<br class="hidden sm:block" />
                Silakan klik tombol di bawah untuk mulai melaporkan masalah maupun aspirasi Anda.
            </p>

            @auth
            <a href="/pengaduan/create" wire:navigate
                class="btn border-none text-white shadow-2xl bg-[#0085FF] hover:bg-white hover:text-[#0085FF] hover:-translate-y-1 px-8 md:px-14 py-3 md:py-4 rounded-full font-black text-xs md:text-lg transition-all duration-300">
                Mulai Pengaduan
            </a>
            @else
            <a href="/login" wire:navigate
                class="btn border-none text-white shadow-2xl bg-[#0085FF] hover:bg-white hover:text-[#0085FF] hover:-translate-y-1 px-8 md:px-14 py-3 md:py-4 rounded-full font-black text-xs md:text-lg transition-all duration-300">
                Mulai Pengaduan
            </a>
            @endauth
        </div>
    </div>

    {{-- Section Pengumuman, Cek Status & SOP --}}
    <div class="w-full max-w-7xl mx-auto px-1.5 lg:px-2 mt-[-2rem] md:mt-[-3rem] relative z-20 mb-8">
        
        {{-- Pengumuman Section --}}
        @if($settings['pengumuman_aktif'] ?? false)
        <div class="mb-4">
            <div class="alert alert-{{ $settings['pengumuman_tipe'] ?? 'info' }} shadow-xl border-none rounded-2xl p-6 sm:p-8 flex flex-col items-center text-center gap-4">
                <div class="p-3 bg-white/20 rounded-2xl shrink-0">
                    <x-icon name="o-megaphone" class="w-8 h-8 text-white" />
                </div>
                <div class="flex-1 text-white">
                    <h3 class="font-black text-xs sm:text-sm uppercase tracking-widest mb-2 opacity-80">Pengumuman Penting</h3>
                    <div class="text-sm sm:text-base font-bold leading-relaxed">
                        {!! $settings['pengumuman_isi'] !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
            
            {{-- Lacak Laporan Card --}}
            <div class="bg-base-100 rounded-2xl shadow-xl border border-base-300 p-4 lg:p-6 flex flex-col justify-center">
                <h2 class="text-lg lg:text-xl font-black text-primary mb-2 flex items-center gap-2">
                    <x-icon name="o-magnifying-glass" class="w-5 h-5 lg:w-6 lg:h-6" /> Lacak Laporan
                </h2>
                <p class="text-xs lg:text-sm text-base-content/70 mb-4 lg:mb-5">Masukkan Kode Tracking untuk melihat status laporan Anda tanpa harus login.</p>
                
                <form wire:submit.prevent="lacakLaporan" class="flex flex-col gap-3">
                    <x-input wire:model="trackingCode" placeholder="Contoh: PKM-KBR/001/V/2026" 
                        class="w-full bg-base-200 text-xs lg:text-sm font-mono uppercase" required maxlength="30" />
                    
                    <x-button type="submit" label="Cek Status" icon="o-arrow-right" 
                        class="btn-primary w-full text-white rounded-xl shadow-sm text-xs lg:text-sm" />
                </form>

                @if (session()->has('error'))
                    <div class="mt-4 text-xs text-error font-medium flex items-center gap-1">
                        <x-icon name="o-exclamation-circle" class="w-4 h-4" /> {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- SOP Card --}}
            <div class="bg-base-100 rounded-2xl shadow-lg border border-base-300 p-4 lg:p-6 lg:col-span-2 flex flex-col justify-center">
                <h2 class="text-lg lg:text-xl font-black text-base-content mb-4 flex items-center gap-2">
                    <x-icon name="o-information-circle" class="w-5 h-5 lg:w-6 lg:h-6 text-info" /> Standar Pelayanan (SOP)
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
        <form wire:submit.prevent class="mb-8" wire:ignore.self>
            <div class="flex flex-col lg:flex-row items-center w-full gap-3 lg:gap-4">
                <div class="flex-1 w-full">
                    <x-input placeholder="Cari laporan..." wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                        clearable class="w-full" />
                </div>
                
                <div class="grid grid-cols-2 lg:flex items-center gap-2 w-full lg:w-auto">
                    <div class="col-span-1 lg:w-48">
                        <x-select wire:model.live="kategori_id" :options="$kategoris" option-value="id" option-label="nama"
                            placeholder="Kategori" icon="o-tag" />
                    </div>
                    <div class="col-span-1 lg:w-40">
                        <x-select wire:model.live="sort" :options="$sortOptions" option-value="id" option-label="nama"
                            icon="o-arrows-up-down" />
                    </div>
                    
                    {{-- View Mode Toggle - Full width on mobile below the selects --}}
                    <div class="col-span-2 lg:col-span-1 flex bg-base-200 p-1 rounded-xl gap-1 justify-center lg:justify-start">
                        <button type="button" wire:click="$set('viewMode', 'grid')" 
                            class="flex-1 lg:flex-none p-2 rounded-lg transition-all {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content' }}">
                            <x-icon name="o-squares-2x2" class="w-4 h-4 sm:w-5 sm:h-5 mx-auto" />
                        </button>
                        <button type="button" wire:click="$set('viewMode', 'list')" 
                            class="flex-1 lg:flex-none p-2 rounded-lg transition-all {{ $viewMode === 'list' ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content' }}">
                            <x-icon name="o-list-bullet" class="w-4 h-4 sm:w-5 sm:h-5 mx-auto" />
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Reports Display & Loading States --}}
        <div class="relative min-h-[400px]">
            {{-- Main Content --}}
            <div wire:loading.remove wire:target="search, kategori_id, sort, viewMode, gotoPage">
                @if($viewMode === 'grid')
                {{-- Grid Cards --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-3">
                    @forelse($pengaduans as $pengaduan)
                        {{-- Grid Card Content --}}
                        <div wire:key="grid-{{ $pengaduan->id }}" wire:ignore.self
                            class="relative group bg-base-100 rounded-2xl overflow-hidden border border-base-300 hover:shadow-xl transition-all duration-500 animate-in fade-in zoom-in-95">
                            
                            {{-- Stretched Link for SPA Navigation --}}
                            <a href="{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}" 
                                wire:navigate.prefetch 
                                class="absolute inset-0 z-10"></a>

                            {{-- Image Container --}}
                            @if($pengaduan->foto_bukti && count($pengaduan->foto_bukti) > 0)
                            <div class="relative w-full overflow-hidden bg-base-200" style="aspect-ratio: 1/1;">
                                <img src="{{ Storage::url($pengaduan->foto_bukti[0]) }}" alt="Bukti {{ $pengaduan->judul }}"
                                    class="absolute inset-0 object-cover w-full h-full transition-transform duration-300 group-hover:scale-105" />

                                <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full 
                                        {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                                        {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                                        {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                                        {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }} shadow-sm">
                                        {{ ucfirst($pengaduan->status) }}
                                    </span>
                                    @if($pengaduan->dukungans_count >= 50)
                                        <span class="px-2 py-0.5 text-[8px] font-black bg-error text-white rounded-md shadow-lg uppercase tracking-tighter flex items-center gap-1 animate-pulse border border-white/20">
                                            <x-icon name="s-fire" class="w-2.5 h-2.5" /> Mendesak
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="relative w-full overflow-hidden bg-neutral flex items-center justify-center group/placeholder" style="aspect-ratio: 1/1;">
                                <div class="flex flex-col items-center justify-center opacity-20 group-hover/placeholder:scale-110 transition-transform duration-500">
                                    <x-icon name="o-camera" class="w-12 h-12 text-neutral-content" />
                                    <span class="text-[10px] font-bold mt-2 uppercase tracking-widest text-neutral-content">Tanpa Foto</span>
                                </div>

                                <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full 
                                        {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                                        {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                                        {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                                        {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }} shadow-sm">
                                        {{ ucfirst($pengaduan->status) }}
                                    </span>
                                    @if($pengaduan->dukungans_count >= 50)
                                        <span class="px-2 py-0.5 text-[8px] font-black bg-error text-white rounded-md shadow-lg uppercase tracking-tighter flex items-center gap-1 animate-pulse border border-white/20">
                                            <x-icon name="s-fire" class="w-2.5 h-2.5" /> Mendesak
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-col flex-1 p-4 sm:p-5">
                                <div class="flex items-center justify-between mb-3 text-xs font-medium text-base-content/60">
                                    <span class="px-1.5 py-1 rounded-md bg-base-200 text-primary min-w-0"
                                        title="{{ $pengaduan->kategori->nama }}">
                                        <span class="truncate">{{ $pengaduan->kategori->nama }}</span>
                                    </span>

                                    <span class="flex items-center gap-1 shrink-0 ml-2">
                                        <x-icon name="o-clock" class="w-3.5 h-3.5" /> {{ $pengaduan->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h2 class="mb-1 text-sm font-bold leading-tight transition-colors sm:text-lg text-base-content group-hover:text-primary line-clamp-2">
                                    {{ $pengaduan->judul }}
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
                                    <div class="flex items-center gap-2.5 text-sm font-medium text-base-content/80">
                                            @if($pengaduan->is_anonymous)
                                                <x-user-avatar initials="AN" size="w-10 h-10" />
                                                <span class="line-clamp-1 max-w-[100px]">Anonim</span>
                                            @else
                                                <x-user-avatar :user="$pengaduan->user" size="w-10 h-10" />
                                                <span class="line-clamp-1 max-w-[180px]">{{ $pengaduan->user->name }}</span>
                                            @endif
                                        </div>

                                    <div class="relative z-20 flex items-center gap-3">
                                        <button wire:click.stop="upvote({{ $pengaduan->id }})"
                                            class="flex items-center gap-1.5 transition-all duration-300 group/vote
                                            {{ $pengaduan->has_liked ? 'text-primary scale-110' : 'text-black hover:text-primary ' }}">
                                            <x-icon name="{{ $pengaduan->has_liked ? 's-hand-thumb-up' : 'o-hand-thumb-up' }}" class="w-6 h-6" />
                                            <span class="text-sm font-black">{{ $pengaduan->dukungans_count }}</span>
                                        </button>

                                        <button onclick="event.preventDefault(); event.stopPropagation(); nativeShare('{{ addslashes($pengaduan->judul) }}', 'Bantu dukung laporan ini agar segera ditindaklanjuti!', '{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}')"
                                            class="p-2 transition-colors rounded-full hover:bg-base-200 text-black hover:text-primary">
                                            <x-icon name="o-share" class="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-20 text-center lg:col-span-3 xl:col-span-4 2xl:col-span-3 bg-base-100 rounded-3xl border border-dashed border-base-300 p-8">
                            <div class="w-48 h-48 mb-8 relative">
                                <div class="absolute inset-0 bg-primary/10 rounded-full animate-pulse"></div>
                                <img src="https://illustrations.popsy.co/amber/waiting.svg" class="relative z-10 w-full h-full object-contain" alt="Empty">
                            </div>
                            <h3 class="text-2xl font-black text-base-content mb-2">Belum ada laporan di sini</h3>
                            <p class="text-base-content/50 max-w-sm mb-8 font-medium">Jadilah orang pertama yang melaporkan masalah atau memberikan aspirasi untuk kemajuan Kecamatan Kembaran!</p>
                            <a href="/pengaduan/create" wire:navigate class="btn btn-primary btn-wide rounded-2xl text-white font-black shadow-xl shadow-primary/20">
                                <x-icon name="o-plus" class="w-5 h-5" /> Buat Laporan Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            @else
                {{-- Ultra-Compact List View --}}
                <div class="flex flex-col gap-2 sm:gap-3">
                    @forelse($pengaduans as $pengaduan)
                        {{-- List Card Content --}}
                        <div wire:key="list-{{ $pengaduan->id }}" wire:ignore.self
                            class="relative flex flex-row items-center gap-3 sm:gap-6 p-2 sm:p-4 border border-base-300 bg-base-100 rounded-xl sm:rounded-2xl hover:shadow-md hover:border-primary/20 transition-all group animate-in fade-in slide-in-from-bottom-2 duration-500">
                            
                            {{-- Stretched Link for SPA Navigation --}}
                            <a href="{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}" 
                                wire:navigate.prefetch 
                                class="absolute inset-0 z-10"></a>
                            
                            {{-- Larger 1:1 Square Image Container --}}
                            <div class="w-24 sm:w-40 aspect-square shrink-0 bg-base-200 rounded-lg sm:rounded-xl overflow-hidden relative shadow-inner">
                                @if($pengaduan->foto_bukti && count($pengaduan->foto_bukti) > 0)
                                    <img src="{{ Storage::url($pengaduan->foto_bukti[0]) }}" alt="Bukti" loading="lazy" decoding="async"
                                        class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 group-hover:scale-110" />
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                        <x-icon name="o-camera" class="w-6 h-6 sm:w-10 sm:h-10" />
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex flex-col flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-0.5 sm:mb-1">
                                    <div class="flex items-center gap-1.5 sm:gap-2">
                                        <span class="px-1.5 py-0.5 bg-base-200 text-primary text-[9px] sm:text-[11px] font-bold rounded sm:rounded-md truncate max-w-[150px] sm:max-w-[250px]">
                                            {{ $pengaduan->kategori->nama }}
                                        </span>
                                        <span class="hidden xs:inline-block text-[9px] sm:text-[11px] text-base-content/40 font-medium">
                                            {{ $pengaduan->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <div class="hidden sm:flex flex-col items-end gap-1.5">
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full shadow-sm
                                            {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                                            {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                                            {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                                            {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }}">
                                            {{ ucfirst($pengaduan->status) }}
                                        </span>
                                        @if($pengaduan->dukungans_count >= 50)
                                            <span class="px-2 py-0.5 text-[8px] font-black bg-error text-white rounded-md shadow-sm uppercase tracking-tighter flex items-center gap-1 border border-white/10">
                                                <x-icon name="s-fire" class="w-2.5 h-2.5" /> Suara Rakyat
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <h2 class="text-xs sm:text-lg font-bold text-base-content group-hover:text-primary transition-colors line-clamp-1 mb-0.5">
                                    {{ $pengaduan->judul }}
                                </h2>

                                <div class="flex items-start gap-1 mb-1 sm:mb-2 text-[9px] sm:text-[11px] text-base-content/50">
                                    <x-icon name="o-map-pin" class="w-2.5 h-2.5 mt-0.5 shrink-0 text-error/60" />
                                    <span class="line-clamp-1">{{ $pengaduan->lokasi_kejadian ?? 'Lokasi tidak spesifik' }}</span>
                                </div>

                                <p class="hidden sm:block text-sm text-base-content/60 line-clamp-1 mb-3">
                                    {{ $pengaduan->deskripsi }}
                                </p>
                                
                                <div class="sm:hidden mb-1.5">
                                    <span class="px-2 py-0.5 text-[8px] font-bold rounded-full shadow-sm
                                        {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                                        {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                                        {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                                        {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }}">
                                        {{ ucfirst($pengaduan->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center gap-1.5 sm:gap-2">
                                        @if($pengaduan->is_anonymous)
                                            <x-user-avatar initials="AN" size="w-6 h-6 sm:w-8 sm:h-8" />
                                            <span class="text-[9px] sm:text-xs text-base-content/50 truncate max-w-[50px]">Anonim</span>
                                        @else
                                            <x-user-avatar :user="$pengaduan->user" size="w-6 h-6 sm:w-8 sm:h-8" />
                                            <span class="text-[9px] sm:text-xs text-base-content/70 font-medium truncate max-w-[120px] sm:max-w-[200px]">{{ $pengaduan->user->name }}</span>
                                        @endif
                                    </div>

                                    <div class="relative z-20 flex items-center gap-3 sm:gap-4">
                                        <button wire:click.stop="upvote({{ $pengaduan->id }})"
                                            class="flex items-center gap-1.5 sm:gap-2 transition-all {{ $pengaduan->has_liked ? 'text-primary' : 'text-black hover:text-primary' }}">
                                            <x-icon name="{{ $pengaduan->has_liked ? 's-hand-thumb-up' : 'o-hand-thumb-up' }}" class="w-5.5 h-5.5 sm:w-6 sm:h-6" />
                                            <span class="text-xs sm:text-sm font-bold">{{ $pengaduan->dukungans_count }}</span>
                                        </button>
                                        
                                        <button onclick="event.preventDefault(); event.stopPropagation(); nativeShare('{{ addslashes($pengaduan->judul) }}', 'Bantu dukung laporan ini agar segera ditindaklanjuti!', '{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}')"
                                            class="text-black hover:text-primary transition-colors p-1">
                                            <x-icon name="o-share" class="w-5 h-5 sm:w-5.5 sm:h-5.5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-20 text-center bg-base-100 rounded-3xl border border-dashed border-base-300 p-8">
                            <div class="w-48 h-48 mb-8 relative">
                                <div class="absolute inset-0 bg-primary/10 rounded-full animate-pulse"></div>
                                <img src="https://illustrations.popsy.co/amber/waiting.svg" class="relative z-10 w-full h-full object-contain" alt="Empty">
                            </div>
                            <h3 class="text-2xl font-black text-base-content mb-2">Belum ada laporan di sini</h3>
                            <p class="text-base-content/50 max-w-sm mb-8 font-medium">Jadilah orang pertama yang melaporkan masalah atau memberikan aspirasi untuk kemajuan Kecamatan Kembaran!</p>
                            <a href="/pengaduan/create" wire:navigate class="btn btn-primary btn-wide rounded-2xl text-white font-black shadow-xl shadow-primary/20">
                                <x-icon name="o-plus" class="w-5 h-5" /> Buat Laporan Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
                @endif
            </div>

            {{-- Skeleton Loading States (Now inside the same relative container) --}}
            <div wire:loading wire:target="search, kategori_id, sort, viewMode, gotoPage" class="w-full">
                @if($viewMode === 'grid')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-3">
                        @foreach(range(1, 8) as $i)
                            <div class="flex flex-col overflow-hidden border border-base-300 bg-base-100 rounded-2xl animate-pulse">
                                <div class="aspect-square bg-base-200"></div>
                                <div class="p-5 space-y-3">
                                    <div class="h-4 bg-base-200 rounded w-1/4"></div>
                                    <div class="h-6 bg-base-200 rounded w-3/4"></div>
                                    <div class="h-4 bg-base-200 rounded w-full"></div>
                                    <div class="h-4 bg-base-200 rounded w-full"></div>
                                    <div class="pt-4 flex justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-base-200"></div>
                                            <div class="h-4 bg-base-200 rounded w-20"></div>
                                        </div>
                                        <div class="w-12 h-6 rounded-full bg-base-200"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach(range(1, 8) as $i)
                            <div class="flex flex-row items-center gap-4 p-4 border border-base-300 bg-base-100 rounded-2xl animate-pulse">
                                <div class="w-24 sm:w-40 aspect-square bg-base-200 rounded-xl"></div>
                                <div class="flex-1 space-y-3 py-2">
                                    <div class="h-4 bg-base-200 rounded w-1/6"></div>
                                    <div class="h-7 bg-base-200 rounded w-1/2"></div>
                                    <div class="h-4 bg-base-200 rounded w-1/3"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 mb-0">
            {{ $pengaduans->links() }}
        </div>
    </div>

</div>