<div class="px-0.1 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">

    {{-- Button Kembali --}}
    <div class="mb-4">
        <a href="{{ route('beranda') }}" wire:navigate class="btn btn-sm btn-ghost hover:bg-base-200 transition-colors">
            <x-icon name="o-arrow-left" class="w-4 h-4 mr-1" /> Kembali ke Beranda
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- Kolom Kiri: Detil Laporan --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card Utama --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="p-6 md:p-8 space-y-6">

                    {{-- Header Laporan --}}
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <span
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-base-200 text-primary font-bold text-xs tracking-wider">
                                <x-icon name="{{ $this->pengaduan->kategori->icon ?? 'o-tag' }}" class="w-4 h-4" />
                                {{ $this->pengaduan->kategori->nama }}
                            </span>

                            @if($this->pengaduan->status == 'menunggu')
                            <x-badge value="Menunggu" class="badge-warning font-bold shadow-sm" />
                            @elseif($this->pengaduan->status == 'diproses')
                            <x-badge value="Diproses" class="badge-info font-bold shadow-sm" />
                            @elseif($this->pengaduan->status == 'selesai')
                            <x-badge value="Selesai" class="badge-success font-bold shadow-sm" />
                            @elseif($this->pengaduan->status == 'ditolak')
                            <x-badge value="Ditolak" class="badge-error font-bold shadow-sm" />
                            @endif
                        </div>

                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-black leading-tight text-base-content mb-4">
                            {{ $this->pengaduan->judul }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-base-content/60">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-full bg-base-300 flex items-center justify-center">
                                    <x-icon name="o-user" class="w-3 h-3 opacity-70" />
                                </div>
                                <span>{{ $this->pengaduan->is_anonymous ? 'Anonim' : $this->pengaduan->user->name
                                    }}</span>
                            </div>
                            <span>&bull;</span>
                            <div class="flex items-center gap-1.5"
                                title="{{ $this->pengaduan->created_at->format('d M Y, H:i') }}">
                                <x-icon name="o-calendar" class="w-4 h-4" />
                                <span>{{ $this->pengaduan->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>



                    @if($this->pengaduan->foto_bukti)
                    <div class="mt-8">
                        <span
                            class="text-[10px] font-black uppercase text-base-content/40 tracking-[0.2em] block mb-3 ml-1">
                            Lampiran Foto Bukti
                        </span>

                        {{-- Container Foto: Hapus max-w-2xl biar selebar card --}}
                        <div
                            class="relative group rounded-2xl overflow-hidden border border-base-200 bg-base-200 shadow-sm aspect-video md:aspect-auto">
                            <img src="{{ Storage::url($this->pengaduan->foto_bukti) }}" alt="Bukti Lampiran"
                                class="w-full h-auto min-h-[300px] md:max-h-[500px] object-cover transition duration-500 cursor-zoom-in group-hover:scale-105"
                                onclick="window.open(this.src, '_blank')">

                            {{-- Overlay Hover --}}
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center pointer-events-none backdrop-blur-[1px]">
                                <x-icon name="o-magnifying-glass-plus"
                                    class="w-12 h-12 text-white drop-shadow-lg mb-2" />
                                <span class="text-white text-xs font-bold uppercase tracking-widest">Klik untuk
                                    memperbesar</span>
                            </div>


                        </div>
                    </div>
                    @endif

                    {{-- Lokasi Kejadian --}}
                    {{-- Lokasi Kejadian --}}
                    @if($this->pengaduan->lokasi_kejadian)
                    <div class="bg-base-200/50 p-4 rounded-xl border border-base-200 flex items-start gap-3">
                        <div class="p-2 bg-error/10 rounded-lg text-error shrink-0">
                            <x-icon name="o-map-pin" class="w-5 h-5" />
                        </div>

                        {{-- Tambahkan flex-1 dan min-w-0 di sini --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black uppercase text-base-content/40 tracking-wider mb-0.5">
                                Lokasi Terkait
                            </p>

                            {{-- Tambahkan truncate agar kalau panjang mentok 1 baris --}}
                            <p class="text-sm font-bold text-base-content leading-tight truncate">
                                {{ $this->pengaduan->lokasi_kejadian }}
                            </p>

                            @if($this->pengaduan->latitude)
                            {{-- URL Google Maps diperbaiki --}}
                            <a href="https://maps.google.com/?q={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}"
                                target="_blank"
                                class="text-primary hover:underline text-xs mt-1 inline-block font-semibold">
                                Buka di Peta
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Deskripsi Keluhan --}}
                    <div class="text-sm md:text-base leading-relaxed text-base-content/90 font-medium
                        ">
                        {{ $this->pengaduan->deskripsi }}

                    </div>



                    {{-- Foto Lampiran: Pas dengan kontainer --}}



                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Milestone/Timeline --}}
        <div class="space-y-6">

            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden sticky top-6">
                <div class="px-5 py-4 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-clock" class="w-4 h-4 text-primary" />
                    <h2 class="font-bold text-base-content/80 uppercase text-xs tracking-widest">Riwayat Laporan</h2>
                </div>

                <div class="p-5">

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