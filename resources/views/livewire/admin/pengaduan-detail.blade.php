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

            {{-- Aksi Status (Dropdown) --}}
            <x-dropdown class="dropdown-end">
                <x-slot:trigger>
                    <x-button icon="o-ellipsis-vertical" class="btn-primary btn-outline shadow-sm rounded-xl shrink-0"
                        label="Aksi" />
                </x-slot:trigger>

                <div class="my-1 opacity-50 divider"><span class="text-[10px] font-bold">UBAH
                        STATUS</span></div>

                @if($this->pengaduan->status !== 'menunggu')
                <x-menu-item title="Set Menunggu" icon="o-clock" wire:click="openUpdateStatusModal('menunggu')" />
                @endif

                @if($this->pengaduan->status !== 'diproses')
                @if($this->pengaduan->petugas_id)
                <x-menu-item title="Set Diproses" icon="o-arrow-path" wire:click="openUpdateStatusModal('diproses')" />
                @else
                <x-menu-item title="Proses (Dispo)" icon="o-arrow-path" class="font-bold text-info"
                    wire:click="openDisposisi" />
                @endif
                @endif

                @if($this->pengaduan->status !== 'selesai')
                <x-menu-item title="Selesaikan" icon="o-check-circle" class="font-bold text-success"
                    wire:click="openUpdateStatusModal('selesai')" />
                @endif

                @if($this->pengaduan->status !== 'ditolak')
                <x-menu-item title="Tolak Laporan" icon="o-x-circle" class="font-bold text-error"
                    wire:click="openUpdateStatusModal('ditolak')" />
                @endif
                <div class="my-1 opacity-50 divider"><span class="text-[10px] font-bold">TUGAS</span>
                </div>
                <x-menu-item title="Atur Disposisi" icon="o-user-plus" wire:click="openDisposisi" />
            </x-dropdown>

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
                    {{-- Custom Timeline Implementation (No DaisyUI bugs/stripes) --}}
                    <div
                        class="relative pl-6 sm:pl-8 space-y-6 sm:space-y-8 before:absolute before:inset-y-2 before:left-[11px] sm:before:left-[15px] before:w-[2px] before:bg-base-300">
                        @foreach($this->pengaduan->histories as $index => $history)
                        @php
                        $timelineColor = match($history->status_baru) {
                        'menunggu' => 'text-warning',
                        'diproses' => 'text-info',
                        'selesai' => 'text-success',
                        'ditolak' => 'text-error',
                        default => 'text-base-300'
                        };
                        $borderColor = match($history->status_baru) {
                        'menunggu' => 'border-warning',
                        'diproses' => 'border-info',
                        'selesai' => 'border-success',
                        'ditolak' => 'border-error',
                        default => 'border-base-300'
                        };
                        $isLatest = $index === 0;
                        @endphp

                        <div class="relative z-10 w-full pl-2 sm:pl-4">
                            {{-- Titik Milestone --}}
                            <div
                                class="absolute -left-[29px] sm:-left-[39px] top-0 sm:top-1 w-4 h-4 sm:w-6 sm:h-6 rounded-full bg-base-100 border-[3px] {{ $isLatest ? $borderColor : 'border-base-300' }} flex items-center justify-center shadow-sm">
                                @if($isLatest)
                                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-current {{ $timelineColor }}">
                                </div>
                                @endif
                            </div>

                            {{-- Konten Utama --}}
                            <div class="flex flex-col gap-1.5">
                                {{-- Status & Waktu --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="font-extrabold text-[13px] sm:text-sm uppercase tracking-widest {{ $timelineColor }}">
                                        {{ $history->status_baru }}
                                    </span>
                                    <span class="text-[10.5px] sm:text-[11px] font-semibold text-base-content/40">
                                        • {{ $history->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- Data User Opsional --}}
                                @if($history->user)
                                <div class="flex items-center gap-1.5 text-base-content/70">
                                    <x-icon name="o-user" class="w-3.5 h-3.5" />
                                    <span class="text-[11px] sm:text-xs font-medium">Oleh: {{ $history->user->name
                                        }}</span>
                                    <span
                                        class="text-[9px] px-1.5 py-0.5 rounded-full bg-base-300 text-base-content/80 uppercase font-black tracking-widest">{{
                                        $history->user->role }}</span>
                                </div>
                                @endif

                                {{-- Catatan & Foto Opsional --}}
                                @if($history->keterangan_admin || $history->foto_bukti)
                                <div class="flex">
                                    <div class="w-[2px] bg-base-300 rounded-full mr-3 sm:mr-4 mt-2"></div>
                                    <div class="flex-1 mt-1 space-y-3">
                                        @if($history->keterangan_admin)
                                        <p
                                            class="text-[12px] sm:text-[13px] leading-relaxed font-medium text-base-content/80 italic">
                                            "{{ $history->keterangan_admin }}"
                                        </p>
                                        @endif

                                        @if($history->foto_bukti)
                                        <div class="cursor-zoom-in w-fit block"
                                            onclick="window.open('{{ Storage::url($history->foto_bukti) }}', '_blank')">
                                            <img src="{{ Storage::url($history->foto_bukti) }}"
                                                alt="Foto Update Tindak Lanjut"
                                                class="w-auto h-24 sm:h-32 object-cover rounded-xl border border-base-200 shadow-sm transition-transform duration-300 hover:scale-[1.03]">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        {{-- Base Laporan Dibuat --}}
                        <div class="relative z-10 w-full pl-2 sm:pl-4 pt-1 sm:pt-2">
                            <div
                                class="absolute -left-[29px] sm:-left-[39px] top-1 sm:top-2.5 w-4 h-4 sm:w-6 sm:h-6 rounded-full bg-base-200 border-[3px] border-base-300 flex items-center justify-center shadow-sm">
                                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-base-300"></div>
                            </div>
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="font-extrabold text-[13px] sm:text-sm uppercase tracking-widest text-base-content/70">
                                    Laporan Diterima
                                </span>
                            </div>
                            <div class="text-[10.5px] sm:text-[11px] font-semibold text-base-content/40">
                                {{ $this->pengaduan->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
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

    <!-- Modal Update Status -->
    <x-modal wire:model="updateModal" title="Perbarui Status Laporan"
        subtitle="Tambahkan catatan dan foto dokumentasi (opsional) untuk update ini.">
        <x-form wire:submit="saveStatusUpdate">
            <x-file label="Foto Dokumentasi (Opsional)" wire:model="update_foto" accept="image/*"
                :required="$update_status === 'selesai'"
                :hint="$update_status === 'selesai' ? 'Wajib menyertakan foto hasil pekerjaan untuk status Selesai.' : 'Lampirkan foto pendukung bila ada.'" />
            @if ($update_foto)
            <div class="mt-2 text-center border border-dashed rounded-lg p-2 bg-base-100">
                <span class="text-sm font-semibold text-gray-500 block">Preview Foto:</span>
                <img src="{{ $update_foto->temporaryUrl() }}"
                    class="rounded shadow w-48 mx-auto mt-1 border border-base-300">
            </div>
            @endif
            <x-textarea label="Catatan / Tindak Lanjut" wire:model="update_keterangan"
                placeholder="Catat detail aktivitas / alasan perubahan status..." rows="3"
                :required="in_array($update_status, ['selesai', 'ditolak'])" />
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.updateModal = false" class="btn-ghost" />
                <x-button label="Simpan Pembaruan" type="submit" icon="o-check-circle" class="btn-primary text-white"
                    spinner="saveStatusUpdate" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>