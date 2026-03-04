<div class="px-0 py-2 sm:py-4 mx-auto max-w-7xl sm:px-0 lg:px-8 text-base-content -mx-5 sm:mx-auto">

    {{-- Top Action Bar (Kembali & Admin Actions) --}}
    <div class="mb-3 px-3 sm:px-0 sm:mb-5 flex items-center justify-between">
        <a href="{{ route('admin.pengaduan') }}" wire:navigate
            class="btn btn-sm sm:h-10 sm:min-h-[2.5rem] sm:px-4 sm:text-sm sm:font-bold btn-ghost hover:bg-base-200 transition-colors -ml-2 sm:ml-0">
            <x-icon name="o-arrow-left" class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-1.5" /> Kembali
        </a>

        {{-- Aksi Status Admin (Dropdown) --}}
        <x-dropdown class="dropdown-end">
            <x-slot:trigger>
                <x-button icon="o-ellipsis-vertical"
                    class="btn-primary btn-outline shadow-sm rounded-xl shrink-0 btn-sm sm:btn-md" label="Aksi" />
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

                            <div class="flex items-center gap-2">
                                <span
                                    class="font-mono text-[10px] sm:text-xs text-base-content/50 font-bold hidden sm:block">#{{
                                    $this->pengaduan->id }}</span>
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
                                <span>{{ $this->pengaduan->created_at->format('d F Y, H:i') }}</span>
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

        {{-- Kolom Kanan: Detail Pelapor, Petugas, Timeline --}}
        <div class="space-y-4 sm:space-y-6 px-3 sm:px-0">

            {{-- Card Pelapor (Admin/Petugas Only View) --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-4 py-3 bg-base-200/30 border-b border-base-200">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <x-icon name="o-user" class="w-3.5 h-3.5" /> Data Pelapor (Admin/Petugas View)
                    </h2>
                </div>
                <div class="p-4 flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <x-user-avatar :user="$this->pengaduan->user" size="w-10 h-10" class="shrink-0" />
                        <div class="overflow-hidden">
                            <p class="font-bold text-base-content text-sm leading-tight truncate">{{
                                $this->pengaduan->user->name }}</p>
                            <p class="text-[11px] text-base-content/60 truncate mt-0.5 font-medium">{{
                                $this->pengaduan->user->email }}</p>
                        </div>
                    </div>
                    <div class="bg-base-200/50 rounded-xl p-3 border border-base-200 space-y-2">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="font-bold text-base-content/50 uppercase">NIK</span>
                            <span class="font-mono font-black text-base-content/80">{{
                                $this->pengaduan->user->nik ?? '-' }}</span>
                        </div>
                        <div class="divider my-0 opacity-10"></div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="font-bold text-base-content/50 uppercase">No. WA</span>
                            <span class="font-mono font-black text-base-content/80">{{
                                $this->pengaduan->user->no_wa ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Petugas Disposisi (Admin/Petugas Only View) --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-4 py-3 bg-base-200/30 border-b border-base-200">
                    <h2
                        class="font-bold text-base-content/80 uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <x-icon name="o-clipboard-document-check" class="w-3.5 h-3.5" /> Petugas Terkait
                    </h2>
                </div>
                <div class="p-4">
                    @if($this->pengaduan->petugas)
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-info/10 text-info rounded-xl flex items-center justify-center border border-info/20 shadow-sm shrink-0">
                            <x-icon name="o-user-circle" class="w-6 h-6" />
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-info text-sm leading-tight truncate">{{
                                $this->pengaduan->petugas->name }}</p>
                            <p class="text-[9px] text-base-content/50 uppercase tracking-widest font-bold mt-1">
                                Ditugaskan</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 text-base-content/50">
                        <div class="w-10 h-10 bg-base-200 rounded-xl flex items-center justify-center shrink-0">
                            <x-icon name="o-exclamation-circle" class="w-5 h-5 text-warning" />
                        </div>
                        <p class="text-xs font-medium italic">Belum ada disposisi petugas.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Card Timeline Laporan --}}
            <div class="bg-base-100 sm:rounded-2xl sm:shadow-sm border border-base-200 overflow-hidden sticky top-6">
                <div class="px-4 py-3 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-clock" class="w-3.5 h-3.5 text-primary" />
                    <h2 class="font-bold text-base-content/80 uppercase text-[10px] tracking-widest">Riwayat Laporan
                    </h2>
                </div>

                <div class="px-3 py-4 sm:p-5">

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
                                class="absolute -left-[20px] sm:-left-[28px] top-0 sm:top-1 w-4 h-4 sm:w-6 sm:h-6 rounded-full bg-base-100 border-[3px] {{ $isLatest ? $borderColor : 'border-base-300' }} flex items-center justify-center shadow-sm">
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
                                class="absolute -left-[20px] sm:-left-[28px] top-1 sm:top-2.5 w-4 h-4 sm:w-6 sm:h-6 rounded-full bg-base-200 border-[3px] border-base-300 flex items-center justify-center shadow-sm">
                                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-base-300"></div>
                            </div>
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="font-extrabold text-[13px] sm:text-sm uppercase tracking-widest text-base-content/70">
                                    Laporan Dibuat
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
                        <p class="text-xs font-semibold">Belum ada pembaruan status.</p>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- Modals from Admin Setup --}}
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