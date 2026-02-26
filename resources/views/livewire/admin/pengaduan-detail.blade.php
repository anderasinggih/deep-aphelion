<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">

    {{-- Header Content --}}
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Detail Laporan</h1>
            <p class="mt-1 text-sm font-medium text-base-content/70">
                ID Pengaduan: <span class="font-mono text-base-content/90 font-bold">#{{ $this->pengaduan->id }}</span>
            </p>
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
            <div class="flex items-center">
                @if($this->pengaduan->status == 'menunggu')
                <x-badge value="Menunggu"
                    class="badge-warning badge-md font-bold px-4 py-3 tracking-wider text-xs shadow-sm" />
                @elseif($this->pengaduan->status == 'diproses')
                <x-badge value="Diproses"
                    class="badge-info badge-md font-bold px-4 py-3 tracking-wider text-xs shadow-sm" />
                @elseif($this->pengaduan->status == 'selesai')
                <x-badge value="Selesai"
                    class="badge-success badge-md font-bold px-4 py-3 tracking-wider text-xs shadow-sm" />
                @elseif($this->pengaduan->status == 'ditolak')
                <x-badge value="Ditolak"
                    class="badge-error badge-md font-bold px-4 py-3 tracking-wider text-xs shadow-sm" />
                @endif
            </div>

            <a href="{{ route('admin.pengaduan') }}" wire:navigate
                class="btn btn-outline border-base-300 shadow-sm rounded-xl hover:bg-base-200 hover:text-base-content transition-all shrink-0">
                <x-icon name="o-arrow-left" class="w-4 h-4 mr-1" /> Kembali
            </a>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- Kolom Kiri: Detil Laporan & Lampiran --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card Utama: Info Laporan --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-6 py-4 bg-base-200/30 border-b border-base-200 flex items-center justify-between">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-xs tracking-widest flex items-center gap-2">
                        <x-icon name="o-document-text" class="w-4 h-4" /> Informasi Utama
                    </h2>
                    <span
                        class="text-xs font-semibold text-base-content/50 flex items-center gap-1.5 bg-base-100 px-2 py-1 rounded-md border border-base-200 shadow-sm">
                        <x-icon name="o-calendar" class="w-3.5 h-3.5" /> {{ $this->pengaduan->created_at->format('d F Y,
                        H:i') }}
                    </span>
                </div>

                <div class="p-6 md:p-8 space-y-6">
                    <div>
                        <div
                            class="flex items-center gap-2 px-2 py-1 bg-base-100 border border-base-300 rounded-xl shadow-sm w-fit mb-2">
                            <x-icon name="{{ $this->pengaduan->kategori->icon ?? 'o-tag' }}"
                                class="w-4 h-4 text-primary" />
                            <span class="text-sm font-bold text-base-content">{{ $this->pengaduan->kategori->nama
                                }}</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black leading-tight text-base-content mb-4">
                            {{ $this->pengaduan->judul }}
                        </h3>
                    </div>

                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-base-content/40 block mb-2">Isi
                            Pengaduan</span>
                        <div
                            class="bg-base-200/30 p-5 rounded-xl border border-base-200 text-sm md:text-base leading-relaxed text-base-content/90 font-medium ">
                            {{ $this->pengaduan->deskripsi }}</div>
                    </div>

                    @if($this->pengaduan->lokasi_kejadian)
                    <div>
                        <span class="block mb-2 text-xs font-bold tracking-widest uppercase text-base-content/40">
                            Lokasi Terkait
                        </span>
                        <div
                            class="flex flex-col gap-4 p-4 transition-colors border shadow-sm sm:flex-row sm:items-center sm:justify-between rounded-xl border-base-200 bg-base-100 hover:border-primary/30">

                            {{-- Info Lokasi (min-w-0 penting biar teks mau menyusut dan kepotong rapi) --}}
                            <div class="flex items-start flex-1 gap-3 min-w-0">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg shrink-0 bg-error/10">
                                    <x-icon name="o-map-pin" class="w-5 h-5 text-error" />
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5 w-full">

                                    {{-- Pakai truncate agar mentok 1 baris langsung titik-titik --}}
                                    <p class="text-sm font-bold leading-tight truncate text-base-content">
                                        {{ $this->pengaduan->lokasi_kejadian }}
                                    </p>

                                    @if($this->pengaduan->latitude)
                                    <p class="text-[10px] font-mono text-base-content/50 truncate mt-1">
                                        {{ $this->pengaduan->latitude }}, {{ $this->pengaduan->longitude }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol Buka Peta (Otomatis pindah ke bawah kalau di HP) --}}
                            @if($this->pengaduan->latitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}"
                                target="_blank"
                                class="w-full sm:w-auto shrink-0 btn btn-sm btn-ghost bg-base-200/50 sm:bg-transparent hover:bg-error/10 hover:text-error text-xs sm:text-sm mt-2 sm:mt-0">
                                <x-icon name="o-globe-asia-australia" class="w-4 h-4" /> Buka Peta
                            </a>
                            @endif

                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Card Foto Lampiran (Sekarang di Kolom Kiri) --}}
            @if($this->pengaduan->foto_bukti)
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-5 py-3 bg-base-200/30 border-b border-base-200 flex items-center justify-between">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-xs tracking-widest flex items-center gap-2">
                        <x-icon name="o-camera" class="w-4 h-4" /> Lampiran Foto Bukti
                    </h2>
                </div>
                <div class="p-6">
                    <div
                        class="relative group rounded-2xl overflow-hidden border border-base-200 bg-base-200 shadow-sm aspect-video md:aspect-auto">
                        <img src="{{ Storage::url($this->pengaduan->foto_bukti) }}" alt="Bukti Lampiran"
                            class="w-full h-auto min-h-[300px] md:max-h-[600px] object-cover transition duration-500 cursor-zoom-in group-hover:scale-105"
                            onclick="window.open(this.src, '_blank')">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center pointer-events-none backdrop-blur-[1px]">
                            <x-icon name="o-magnifying-glass-plus" class="w-12 h-12 text-white drop-shadow-lg mb-2" />
                            <span class="text-white text-xs font-bold uppercase tracking-widest">Klik untuk
                                memperbesar</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Komentar Section --}}
            @livewire('pengaduan-komentar', ['pengaduan_id' => $this->pengaduan->id])

        </div>

        {{-- Kolom Kanan: Info Pelapor, Petugas & Riwayat --}}
        <div class="space-y-6">

            {{-- Card Pelapor --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-5 py-3 bg-base-200/30 border-b border-base-200">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-xs tracking-widest flex items-center gap-2">
                        <x-icon name="o-user" class="w-4 h-4" /> Data Pelapor (Admin View)
                    </h2>
                </div>
                <div class="p-5 flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="avatar placeholder shrink-0">
                            <div
                                class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold border border-primary/20 text-lg shadow-sm">
                                {{ strtoupper(substr($this->pengaduan->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-base-content text-sm md:text-base leading-tight truncate">{{
                                $this->pengaduan->user->name }}</p>
                            <p class="text-xs text-base-content/60 truncate mt-0.5 font-medium">{{
                                $this->pengaduan->user->email }}</p>
                        </div>
                    </div>
                    <div class="bg-base-200/50 rounded-xl p-4 border border-base-200 space-y-3">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-base-content/50 uppercase">NIK Pelapor</span>
                            <span class="font-mono font-black text-base-content/80 text-sm">{{
                                $this->pengaduan->user->nik ?? '-' }}</span>
                        </div>
                        <div class="divider my-0 opacity-10"></div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-base-content/50 uppercase">No. WhatsApp</span>
                            <span class="font-mono font-black text-base-content/80 text-sm">{{
                                $this->pengaduan->user->no_wa ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Milestone/Timeline --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-5 py-4 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-clock" class="w-4 h-4 text-primary" />
                    <h2 class="font-bold text-base-content/80 uppercase text-xs tracking-widest">Riwayat Penanganan</h2>
                </div>
                <div class="p-5">
                    @if($this->pengaduan->histories->count() > 0)
                    <ul class="timeline timeline-vertical timeline-compact">
                        @foreach($this->pengaduan->histories as $index => $history)
                        <li>
                            @if($index !== 0)
                            <hr class="bg-base-300" /> @endif
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
                                        Internal</span>
                                    {{ $history->keterangan_admin }}
                                </div>
                                @endif
                            </div>
                            @if(!$loop->last)
                            <hr class="bg-base-300" /> @endif
                        </li>
                        @endforeach
                        <li>
                            <hr class="bg-base-300" />
                            <div class="timeline-middle"><x-icon name="m-stop-circle" class="w-5 h-5 text-base-300" />
                            </div>
                            <div
                                class="timeline-end timeline-box bg-transparent border-none shadow-none pb-2 pl-2 -mt-1 w-full">
                                <div class="font-black text-sm text-base-content/60 uppercase tracking-wide">Laporan
                                    Diterima</div>
                                <div class="text-[10px] font-semibold text-base-content/50 mt-1">{{
                                    $this->pengaduan->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </li>
                    </ul>
                    @else
                    <div class="text-center py-6 text-base-content/50">
                        <x-icon name="o-clock" class="w-8 h-8 opacity-20 mx-auto mb-2" />
                        <p class="text-xs font-semibold">Belum ada riwayat penanganan.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Card Petugas Disposisi --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-5 py-3 bg-base-200/30 border-b border-base-200">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-xs tracking-widest flex items-center gap-2">
                        <x-icon name="o-clipboard-document-check" class="w-4 h-4" /> Petugas Terkait
                    </h2>
                </div>
                <div class="p-5">
                    @if($this->pengaduan->petugas)
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-info/10 text-info rounded-xl flex items-center justify-center border border-info/20 shadow-sm shrink-0">
                            <x-icon name="o-user-circle" class="w-6 h-6" />
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-info text-sm leading-tight truncate">{{
                                $this->pengaduan->petugas->name }}</p>
                            <p class="text-[10px] text-base-content/50 uppercase tracking-widest font-bold mt-1">
                                Ditugaskan</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 text-base-content/50">
                        <div class="w-10 h-10 bg-base-200 rounded-xl flex items-center justify-center shrink-0">
                            <x-icon name="o-exclamation-circle" class="w-5 h-5" />
                        </div>
                        <p class="text-sm font-medium italic">Belum ada disposisi petugas.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal wire:model="disposisiModal" title="Disposisi Laporan Ke Petugas"
        subtitle="Teruskan pengaduan ini agar segera ditindaklanjuti.">
        <x-form wire:submit="saveDisposisi">
            <x-select label="Pilih Petugas Lapangan" wire:model="petugas_id" :options="$list_petugas" option-value="id"
                option-label="name" placeholder="-- Pilih Petugas --" required />
            <x-textarea label="Catatan Administratif (Opsional)" wire:model="disposisi_notes"
                placeholder="Tambahkan instruksi khusus untuk petugas..." rows="3" />
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.disposisiModal = false" class="btn-ghost" />
                <x-button label="Simpan Disposisi & Proses" type="submit" icon="o-paper-airplane" class="btn-primary"
                    spinner="saveDisposisi" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>