<div class="px-0 py-2 sm:py-4 mx-auto max-w-7xl sm:px-0 lg:px-8 text-base-content -mx-5 sm:mx-auto">

    {{-- Button Kembali --}}
    <div class="mb-3 px-3 sm:px-0 sm:mb-5">
        <a href="{{ route('beranda') }}" wire:navigate
            class="btn btn-sm sm:h-10 sm:min-h-[2.5rem] sm:px-4 sm:text-sm sm:font-bold btn-ghost hover:bg-base-200 transition-colors -ml-2 sm:ml-0">
            <x-icon name="o-arrow-left" class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-1.5" /> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 sm:gap-6 lg:gap-8">

        {{-- Kolom Kiri: Detil Laporan --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- Card Utama --}}
            <div class="bg-base-100 sm:rounded-2xl sm:shadow-sm border-y sm:border border-base-200 overflow-hidden">
                <div class="py-4 px-3 sm:p-6 sm:px-0 md:p-8 space-y-4 sm:space-y-6">

                    {{-- Header Laporan --}}
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3 sm:mb-4">
                            <span
                                class="flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg bg-base-200 text-primary font-bold text-[10px] sm:text-xs tracking-wider">
                                <x-icon name="{{ $this->pengaduan->kategori->icon ?? 'o-tag' }}"
                                    class="w-3 h-3 sm:w-4 sm:h-4" />
                                {{ $this->pengaduan->kategori->nama }}
                            </span>

                            @if($this->pengaduan->status == 'menunggu')
                            <x-badge value="Menunggu"
                                class="badge-warning font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                            @elseif($this->pengaduan->status == 'diproses')
                            <x-badge value="Diproses"
                                class="badge-info font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                            @elseif($this->pengaduan->status == 'selesai')
                            <x-badge value="Selesai"
                                class="badge-success font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                            @elseif($this->pengaduan->status == 'ditolak')
                            <x-badge value="Ditolak"
                                class="badge-error font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                            @endif
                        </div>

                        <h1
                            class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-black leading-tight text-base-content mb-2 sm:mb-4">
                            {{ $this->pengaduan->judul }}
                        </h1>

                        <div
                            class="flex flex-wrap items-center gap-2 sm:gap-4 text-[11px] sm:text-xs font-semibold text-base-content/60">
                            <div class="flex items-center gap-1 sm:gap-1.5">
                                <div
                                    class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-base-300 flex items-center justify-center">
                                    <x-icon name="o-user" class="w-3 h-3 opacity-70" />
                                </div>
                                <span>{{ $this->pengaduan->is_anonymous ? 'Anonim' : $this->pengaduan->user->name
                                    }}</span>
                            </div>
                            <span>&bull;</span>
                            <div class="flex items-center gap-1 sm:gap-1.5"
                                title="{{ $this->pengaduan->created_at->format('d M Y, H:i') }}">
                                <x-icon name="o-calendar" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                                <span>{{ $this->pengaduan->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($this->pengaduan->foto_bukti)
                    <div class="mt-4 sm:mt-8">
                        <span
                            class="text-[9px] sm:text-[10px] font-black uppercase text-base-content/40 tracking-[0.2em] mb-2 sm:mb-3 ml-1 hidden sm:block">
                            Lampiran Foto Bukti
                        </span>

                        {{-- Container Foto: Edge to edge di mobile (negative margin) --}}
                        <div
                            class="-mx-3 sm:mx-0 relative group sm:rounded-2xl overflow-hidden sm:border border-base-200 bg-base-200 sm:shadow-sm">
                            <img src="{{ Storage::url($this->pengaduan->foto_bukti) }}" alt="Bukti Lampiran"
                                class="w-full h-auto object-cover transition duration-500 cursor-zoom-in group-hover:scale-105 max-h-[75vh] md:max-h-[500px]"
                                onclick="window.open(this.src, '_blank')">

                            {{-- Overlay Hover --}}
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center pointer-events-none backdrop-blur-[1px]">
                                <x-icon name="o-magnifying-glass-plus"
                                    class="w-10 h-10 sm:w-12 sm:h-12 text-white drop-shadow-lg mb-2" />
                                <span
                                    class="text-white text-[10px] sm:text-xs font-bold uppercase tracking-widest hidden sm:block">Klik
                                    untuk
                                    memperbesar</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Lokasi Kejadian --}}
                    @if($this->pengaduan->lokasi_kejadian)
                    <div
                        class="bg-base-200/50 p-3 sm:p-4 rounded-lg sm:rounded-xl border border-base-200 flex items-start gap-2.5 sm:gap-3">
                        <div class="p-1.5 sm:p-2 bg-error/10 rounded-md sm:rounded-lg text-error shrink-0">
                            <x-icon name="o-map-pin" class="w-4 h-4 sm:w-5 sm:h-5 mt-0.5" />
                        </div>

                        <div class="flex-1 min-w-0">
                            <p
                                class="text-[9px] sm:text-[10px] font-black uppercase text-base-content/40 tracking-wider mb-0.5">
                                Lokasi Terkait
                            </p>

                            <p class="text-[13px] sm:text-sm font-bold text-base-content leading-tight line-clamp-2">
                                {{ $this->pengaduan->lokasi_kejadian }}
                            </p>

                            @if($this->pengaduan->latitude)
                            <a href="https://maps.google.com/?q={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}"
                                target="_blank"
                                class="text-primary hover:underline text-[11px] sm:text-xs mt-1 inline-block font-semibold">
                                Buka di Peta
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Deskripsi Keluhan --}}
                    <div
                        class="text-[13px] sm:text-base leading-relaxed text-base-content/90 font-medium whitespace-pre-line">
                        {{ $this->pengaduan->deskripsi }}
                    </div>

                    {{-- Komentar --}}
                    @livewire('pengaduan-komentar', ['pengaduan_id' => $this->pengaduan->id])

                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Milestone/Timeline --}}
        <div class="space-y-4 sm:space-y-6 px-0">

            <div
                class="bg-base-100 sm:rounded-2xl sm:shadow-sm border-y sm:border border-base-200 overflow-hidden sticky top-6">
                <div class="px-3 sm:px-5 py-3 sm:py-4 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-clock" class="w-4 h-4 text-primary" />
                    <h2 class="font-bold text-base-content/80 uppercase text-xs tracking-widest">Riwayat Laporan</h2>
                </div>

                <div class="px-3 py-4 sm:p-5">

                    @if($this->pengaduan->histories->count() > 0)
                    <ul class="timeline timeline-vertical timeline-compact">
                        @foreach($this->pengaduan->histories as $index => $history)
                        <li>
                            {{-- Garis vertikal (kecuali item pertama/terakhir) --}}
                            @if($index !== 0)
                            <hr class="bg-base-300" /> @endif

                            {{-- Lingkaran Timeline --}}
                            @php
                            $timelineColor = match($history->status_baru) {
                            'menunggu' => 'text-warning',
                            'diproses' => 'text-info',
                            'selesai' => 'text-success',
                            'ditolak' => 'text-error',
                            default => 'text-base-300'
                            };
                            $isLatest = $index === 0;
                            @endphp
                            <div class="timeline-middle">
                                <x-icon name="{{ $isLatest ? 'o-check-circle' : 'm-stop-circle' }}"
                                    class="w-5 h-5 {{ $isLatest ? $timelineColor : 'text-base-300' }}" />
                            </div>

                            {{-- Konten Timeline --}}
                            <div
                                class="timeline-end timeline-box bg-transparent border-none shadow-none pb-6 pl-2 -mt-1 w-full max-w-full">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-black text-sm uppercase tracking-wide {{ $timelineColor }}">
                                        {{ $history->status_baru }}
                                    </div>
                                    <div class="text-[10px] font-semibold text-base-content/50">
                                        {{ $history->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="text-xs text-base-content/70 font-medium">
                                    {{ $history->created_at->format('d M Y, H:i') }}
                                </div>

                                @if($history->keterangan_admin)
                                <div
                                    class="mt-2 text-xs bg-base-200/50 border border-base-200 p-3 rounded-lg leading-relaxed font-medium">
                                    <span
                                        class="block text-[10px] font-black uppercase text-base-content/40 mb-1">Catatan
                                        Admin</span>
                                    {{ $history->keterangan_admin }}
                                </div>
                                @endif
                            </div>

                            @if(!$loop->last)
                            <hr class="bg-base-300" /> @endif
                        </li>
                        @endforeach

                        {{-- Laporan Dibuat (Base Case) --}}
                        <li>
                            <hr class="bg-base-300" />
                            <div class="timeline-middle">
                                <x-icon name="m-stop-circle" class="w-5 h-5 text-base-300" />
                            </div>
                            <div
                                class="timeline-end timeline-box bg-transparent border-none shadow-none pb-2 pl-2 -mt-1 w-full">
                                <div class="font-black text-sm text-base-content/60">Laporan Dibuat</div>
                                <div class="text-[10px] font-semibold text-base-content/50 mt-1">
                                    {{ $this->pengaduan->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </li>
                    </ul>
                    @else
                    <div class="text-center py-6 text-base-content/50">
                        <x-icon name="o-clock" class="w-8 h-8 opacity-20 mx-auto mb-2" />
                        <p class="text-xs font-semibold">Belum ada pembaruan status.</p>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>