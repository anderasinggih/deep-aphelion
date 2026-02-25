<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">

    {{-- Header Content --}}
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Detail Laporan</h1>
            <p class="mt-1 text-sm font-medium text-base-content/70">
                ID Pengaduan: <span class="font-mono text-base-content/90 font-bold">#{{ $this->pengaduan->id }}</span>
            </p>
        </div>

        {{-- Tambahkan gap dan w-full sm:w-auto agar responsif --}}
        <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
            <div class="flex items-center">
                @if($this->pengaduan->status == 'menunggu')
                <x-badge value="Menunggu"
                    class="badge-warning badge-md font-bold px-4 py-3  tracking-wider text-xs shadow-sm" />
                @elseif($this->pengaduan->status == 'diproses')
                <x-badge value="Diproses"
                    class="badge-info badge-md font-bold px-4 py-3  tracking-wider text-xs shadow-sm" />
                @elseif($this->pengaduan->status == 'selesai')
                <x-badge value="Selesai"
                    class="badge-success badge-md font-bold px-4 py-3  tracking-wider text-xs shadow-sm" />
                @elseif($this->pengaduan->status == 'ditolak')
                <x-badge value="Ditolak"
                    class="badge-error badge-md font-bold px-4 py-3  tracking-wider text-xs shadow-sm" />
                @endif
            </div>

            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.pengaduan') }}" wire:navigate
                class="btn btn-outline border-base-300 shadow-sm rounded-xl hover:bg-base-200 hover:text-base-content transition-all shrink-0">
                <x-icon name="o-arrow-left" class="w-4 h-4 mr-1" /> Kembali
            </a>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- Kolom Kiri: Detil Laporan --}}
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
                            class="bg-base-200/30 p-5 rounded-xl border border-base-200 text-sm md:text-base leading-relaxed text-base-content/90 font-medium whitespace-pre-wrap">
                            {{ $this->pengaduan->deskripsi }}
                        </div>
                    </div>

                    @if($this->pengaduan->lokasi_kejadian)
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-base-content/40 block mb-2">Lokasi
                            Terkait</span>
                        <div
                            class="flex items-start md:items-center justify-between gap-4 p-4 rounded-xl border border-base-200 bg-base-100 shadow-sm hover:border-primary/30 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-error/10 flex items-center justify-center shrink-0">
                                    <x-icon name="o-map-pin" class="w-5 h-5 text-error" />
                                </div>
                                <div class="truncate">
                                    <p class="text-sm font-bold text-base-content truncate">
                                        {{ $this->pengaduan->lokasi_kejadian }}
                                    </p>
                                    @if($this->pengaduan->latitude)
                                    <p class="text-[10px] font-mono text-base-content/50 truncate mt-0.5">
                                        {{ $this->pengaduan->latitude }}, {{ $this->pengaduan->longitude }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            @if($this->pengaduan->latitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}"
                                target="_blank"
                                class="btn btn-sm btn-ghost hover:bg-error/10 hover:text-error shrink-0">
                                <x-icon name="o-globe-asia-australia" class="w-4 h-4" /> Buka Peta
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Info Pelapor & Lampiran --}}
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
                            <p class="font-bold text-base-content text-sm md:text-base leading-tight truncate">
                                {{ $this->pengaduan->user->name }}
                            </p>
                            <p class="text-xs text-base-content/60 truncate mt-0.5 font-medium">
                                {{ $this->pengaduan->user->email }}
                            </p>
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

                    @if($this->pengaduan->is_anonymous)
                    <div
                        class="bg-warning/10 border border-warning/20 rounded-xl p-3 flex items-start gap-2 text-warning font-medium text-xs">
                        <x-icon name="o-eye-slash" class="w-4 h-4 shrink-0 mt-0.5" />
                        <p>Warga ini meminta mode <strong>Anonim</strong> di publik. Data ini hanya tampil untuk Admin
                            dan Petugas.</p>
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
                            <p class="font-bold text-info text-sm leading-tight truncate">
                                {{ $this->pengaduan->petugas->name }}
                            </p>
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

            {{-- Card Foto Lampiran --}}
            @if($this->pengaduan->foto_bukti)
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-5 py-3 bg-base-200/30 border-b border-base-200">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-xs tracking-widest flex items-center gap-2">
                        <x-icon name="o-camera" class="w-4 h-4" /> Foto Keluhan
                    </h2>
                </div>
                <div class="p-4">
                    <div
                        class="relative group rounded-xl overflow-hidden shadow-sm border border-base-200 aspect-video md:aspect-[4/3]">
                        <img src="{{ Storage::url($this->pengaduan->foto_bukti) }}" alt="Bukti Lampiran"
                            class="w-full h-full object-cover transition duration-500 cursor-zoom-in group-hover:scale-105"
                            onclick="window.open(this.src, '_blank')">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none backdrop-blur-[1px]">
                            <x-icon name="o-magnifying-glass-plus" class="w-8 h-8 text-white drop-shadow-md" />
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
        {{-- Panel Kontrol Admin --}}
        <div class="bg-base-100 rounded-[2rem] shadow-sm border border-base-200 overflow-hidden">
            <div class="px-6 py-4 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-primary/10 text-primary">
                    <x-icon name="o-megaphone" class="w-4 h-4" />
                </div>
                <h2 class="font-bold text-base-content/80 uppercase text-[10px] tracking-widest">Kontrol Admin</h2>
            </div>

            <div class="p-6 space-y-5">
                {{-- Tombol Disposisi Gaya Login --}}
                <div>
                    <span class="text-[10px] font-black uppercase text-base-content/40 block mb-2 ml-1">Penugasan</span>
                    <x-button label="{{ $this->pengaduan->petugas_id ? 'Ubah Petugas' : 'Tunjuk Petugas' }}"
                        icon="o-user-plus"
                        class="w-full text-white border-none shadow-sm btn-primary bg-[#0085FF] hover:bg-[#0073e6] rounded-xl font-bold transition-all"
                        wire:click="openDisposisi({{ $this->pengaduan->id }})" />
                </div>

                <div class="divider my-0 opacity-10"></div>

                {{-- Dropdown Status --}}
                <div>
                    <span class="text-[10px] font-black uppercase text-base-content/40 block mb-2 ml-1">Update
                        Status</span>
                    <x-dropdown label="Pilih Status" icon="o-chevron-down"
                        class="btn-block bg-base-200/50 border-base-300 rounded-xl font-bold text-sm">

                        @if($this->pengaduan->status !== 'menunggu')
                        <x-menu-item title="Menunggu" icon="o-clock"
                            wire:click="setStatus({{ $this->pengaduan->id }}, 'menunggu')" />
                        @endif

                        @if($this->pengaduan->status !== 'diproses')
                        @if($this->pengaduan->petugas_id)
                        <x-menu-item title="Diproses" icon="o-arrow-path" class="text-info"
                            wire:click="setStatus({{ $this->pengaduan->id }}, 'diproses')" />
                        @else
                        <x-menu-item title="Diproses (Disposisi)" icon="o-arrow-path" class="text-info"
                            wire:click="openDisposisi({{ $this->pengaduan->id }})" />
                        @endif
                        @endif

                        @if($this->pengaduan->status !== 'selesai')
                        <x-menu-item title="Selesai" icon="o-check" class="text-success"
                            wire:click="setStatus({{ $this->pengaduan->id }}, 'selesai')" />
                        @endif

                        @if($this->pengaduan->status !== 'ditolak')
                        <x-menu-item title="Tolak" icon="o-x-mark" class="text-error"
                            wire:click="setStatus({{ $this->pengaduan->id }}, 'ditolak')" />
                        @endif

                    </x-dropdown>
                </div>
            </div>
        </div>


    </div>
</div>