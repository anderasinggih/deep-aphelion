<div class="px-0 py-2 sm:py-4 mx-auto max-w-7xl sm:px-0 lg:px-8 text-base-content -mx-5 sm:mx-auto">

    {{-- Breadcrumb + Action Bar --}}
    <div class="mb-3 px-3 sm:px-0 sm:mb-5 flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-1 text-xs font-semibold text-base-content/50">
            <a href="{{ route('admin.pengaduan') }}" wire:navigate class="hover:text-primary transition-colors">Daftar Laporan</a>
            <x-icon name="o-chevron-right" class="w-3 h-3" />
            <span class="text-base-content/80 truncate max-w-[200px]">{{ Str::limit($this->pengaduan->judul, 30) }}</span>
        </div>

        {{-- Aksi Status Admin (Dropdown) - Sequential Flow --}}
        <x-dropdown class="dropdown-end">
            <x-slot:trigger>
                <x-button icon="o-ellipsis-vertical"
                    class="btn-primary btn-outline shadow-sm rounded-xl shrink-0 btn-sm sm:btn-md" label="Update Status" />
            </x-slot:trigger>

            @if(!$this->pengaduan->trashed())
                <div class="my-1 opacity-50 divider mt-0"><span class="text-[10px] font-bold">Update Progres</span></div>

                @if($this->pengaduan->status === 'menunggu')
                    <x-menu-item title="Mulai Proses" icon="o-arrow-path" class="font-bold text-info"
                        wire:click="openUpdateStatusModal('diproses')" />
                    <x-menu-item title="Tolak Laporan" icon="o-x-circle" class="text-error"
                        wire:click="openUpdateStatusModal('ditolak')" />
                @endif

                @if($this->pengaduan->status !== 'selesai' && $this->pengaduan->status !== 'ditolak')
                    <div class="my-1 opacity-50 divider mt-0"></div>
                    <x-menu-item title="Rujuk Laporan (Duplikat)" icon="o-document-duplicate" class="font-bold text-primary"
                        wire:click="$set('linkModal', true)" />
                @endif

                @if($this->pengaduan->status === 'diproses')
                    <x-menu-item title="Selesaikan" icon="o-check-circle" class="font-bold text-success"
                        wire:click="openUpdateStatusModal('selesai')" />
                    <x-menu-item title="Batalkan (Ke Menunggu)" icon="o-clock"
                        wire:click="openUpdateStatusModal('menunggu')" />
                @endif

                @if($this->pengaduan->status === 'selesai')
                    <x-menu-item title="Buka Kembali (Ke Proses)" icon="o-arrow-path"
                        wire:click="openUpdateStatusModal('diproses')" />
                @endif

                @if($this->pengaduan->status === 'ditolak')
                    <x-menu-item title="Pulihkan (Ke Menunggu)" icon="o-clock"
                        wire:click="openUpdateStatusModal('menunggu')" />
                @endif
            @endif

            <div class="my-1 opacity-50 divider"></div>
            @if($this->pengaduan->trashed())
                <x-menu-item title="Pulihkan Data" icon="o-arrow-path" class="text-success font-bold"
                    wire:click="restore" wire:confirm="Pulihkan laporan ini?" />
            @else
                <x-menu-item title="Hapus Laporan" icon="o-trash" class="text-error"
                    wire:click="delete" wire:confirm="Yakin ingin menghapus laporan ini?" />
            @endif
        </x-dropdown>
    </div>

    @if($this->pengaduan->trashed())
    <div class="mx-3 sm:mx-0 mb-4">
        <x-alert icon="o-trash" title="Laporan ini telah dihapus" class="alert-error shadow-sm rounded-2xl text-white">
            Laporan ini saat ini berada di tempat sampah dan tidak muncul di daftar publik maupun admin utama.
            <x-slot:actions>
                <x-button label="Pulihkan" wire:click="restore" class="btn-sm btn-outline border-white text-white" />
            </x-slot:actions>
        </x-alert>
    </div>
    @endif

    {{-- WA Notifikasi Banner --}}
    @if($waLink)
    <div class="mx-3 sm:mx-0 mb-4 p-3 sm:p-4 rounded-2xl bg-green-50 border border-green-200 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-green-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.114 1.52 5.843L.057 23.535a.5.5 0 0 0 .607.607l5.696-1.462A11.935 11.935 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.923 0-3.716-.52-5.253-1.428l-.376-.222-3.904 1.002 1.003-3.776-.244-.389A9.96 9.96 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-green-800">Status berhasil diperbarui!</p>
                <p class="text-xs text-green-600">Kirim notifikasi ke pelapor via WhatsApp?</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ $waLink }}" target="_blank"
                class="btn btn-sm bg-green-500 hover:bg-green-600 text-white border-0 rounded-xl font-bold">
                Kirim WA
            </a>
            <button wire:click="clearWaLink" class="btn btn-sm btn-ghost rounded-xl">✕</button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 sm:gap-6 lg:gap-8">

        {{-- Kolom Kiri: Detil Laporan (Replicated from Public View) --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- Card Utama --}}
            <div class="bg-base-100 sm:rounded-2xl sm:shadow-sm border-y sm:border border-base-200 overflow-hidden">
                <div class="py-4 px-3 sm:p-6 sm:px-0 md:p-8 space-y-4 sm:space-y-6">

                    {{-- Header Laporan --}}
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3 sm:mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg bg-base-200 text-primary font-bold text-[10px] sm:text-xs">
                                    {{ $this->pengaduan->kategori?->nama ?? 'Kategori Terhapus' }}
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

                                <x-badge value="{{ ucfirst($this->pengaduan->prioritas) }}"
                                    class="{{ $this->pengaduan->prioritas == 'tinggi' ? 'badge-error' : ($this->pengaduan->prioritas == 'sedang' ? 'badge-info' : 'badge-success') }} font-black sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />


                            </div>
                        </div>

                        <h1
                            class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-black leading-tight text-base-content mb-2 sm:mb-4">
                            {{ $this->pengaduan->judul }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-4">
                            {{-- Pelapor --}}
                            <div class="flex items-center gap-2 px-2.5 py-1 sm:px-3 sm:py-1.5 bg-base-200/50 border border-base-200 rounded-lg">
                                @if($this->pengaduan->is_anonymous)
                                    <div class="avatar placeholder">
                                        <div class="bg-base-300 text-base-content/50 rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center">
                                            <span class="text-[8px] font-black">AN</span>
                                        </div>
                                    </div>
                                    <span class="text-[11px] sm:text-xs font-bold text-base-content/70">Anonim ({{ $this->pengaduan->user?->name ?? 'User Terhapus' }})</span>
                                @else
                                    <x-user-avatar :user="$this->pengaduan->user" size="w-5 h-5 sm:w-6 sm:h-6" />
                                    <span class="text-[11px] sm:text-xs font-bold text-base-content/70">{{ $this->pengaduan->user?->name ?? 'User Terhapus' }}</span>
                                @endif
                            </div>

                            {{-- Waktu Lapor --}}
                            <div class="flex items-center gap-1.5 px-2.5 py-1 sm:px-3 sm:py-1.5 bg-base-200/50 border border-base-200 rounded-lg text-base-content/60" title="Dilaporkan: {{ $this->pengaduan->created_at->format('d M Y, H:i') }}">
                                <x-icon name="o-clock" class="w-3.5 h-3.5 sm:w-4 sm:h-4 opacity-70" />
                                <span class="text-[11px] sm:text-xs font-semibold">{{ $this->pengaduan->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Kode Tracking --}}
                            <div class="flex items-center gap-1.5 px-2.5 py-1 sm:px-3 sm:py-1.5 bg-base-200/50 border border-base-200 rounded-lg text-base-content/60">
                                <x-icon name="o-qr-code" class="w-3.5 h-3.5 sm:w-4 sm:h-4 opacity-70" />
                                <span class="text-[11px] sm:text-xs font-mono font-bold tracking-wide">{{ $this->pengaduan->kode_tracking }}</span>
                            </div>

                            @if($this->pengaduan->linked_id)
                            <a href="{{ route('admin.pengaduan.detail', $this->pengaduan->linkedReport->kode_tracking) }}" target="_blank" class="flex items-center gap-1.5 px-2.5 py-1 sm:px-3 sm:py-1.5 bg-primary/10 border border-primary/20 rounded-lg text-primary hover:bg-primary hover:text-white transition-colors">
                                <x-icon name="o-link" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                                <span class="text-[11px] sm:text-xs font-bold tracking-wide">Dirujuk: {{ $this->pengaduan->linkedReport->kode_tracking }}</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Tracker --}}
                    @php
                        $status = $this->pengaduan->status;
                        $steps = [
                            ['id' => 'terkirim', 'label' => 'Terkirim', 'icon' => 'o-paper-airplane', 'active' => true],
                            ['id' => 'verifikasi', 'label' => 'Verifikasi', 'icon' => 'o-magnifying-glass', 'active' => in_array($status, ['diproses', 'selesai', 'ditolak'])],
                            ['id' => 'proses', 'label' => 'Diproses', 'icon' => 'o-arrow-path', 'active' => in_array($status, ['diproses', 'selesai'])],
                            ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'o-check-badge', 'active' => $status == 'selesai'],
                        ];
                        if($status == 'ditolak') {
                            $steps[2] = ['id' => 'ditolak', 'label' => 'Ditolak', 'icon' => 'o-x-circle', 'active' => true, 'error' => true];
                            unset($steps[3]);
                        }
                    @endphp

                    <div class="py-4 border-y border-base-200/50 mb-6 mt-2">
                        <div class="flex items-center justify-between w-full max-w-xl mx-auto px-2">
                            @foreach($steps as $index => $step)
                                <div class="flex flex-col items-center flex-1 relative">
                                    @if($index < count($steps) - 1)
                                        <div class="absolute left-1/2 top-4 w-full h-[2px] {{ isset($steps[$index+1]) && $steps[$index+1]['active'] ? 'bg-success' : 'bg-base-300' }} -z-0"></div>
                                    @endif

                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm relative z-10
                                        {{ $step['active'] ? (isset($step['error']) ? 'bg-error text-white' : 'bg-success text-white') : 'bg-base-200 text-base-content/30' }}">
                                        <x-icon name="{{ $step['icon'] }}" class="w-4 h-4" />
                                    </div>
                                    <span class="text-[9px] sm:text-[10px] font-black mt-2
                                        {{ $step['active'] ? (isset($step['error']) ? 'text-error' : 'text-success') : 'text-base-content/40' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($this->pengaduan->foto_bukti && count($this->pengaduan->foto_bukti) > 0)
                    <div class="mt-4 sm:mt-8">
                        <span class="text-[9px] sm:text-[10px] font-black text-base-content/40 mb-2 sm:mb-3 ml-1">
                            Lampiran Foto Bukti ({{ count($this->pengaduan->foto_bukti) }})
                        </span>

                        <div class="relative group">
                            <div class="carousel w-full snap-x snap-mandatory overflow-x-auto scroll-smooth sm:rounded-2xl bg-base-200 sm:border border-base-200 no-scrollbar" id="carousel-admin">
                                @foreach($this->pengaduan->foto_bukti as $index => $foto)
                                <div id="admin-slide{{ $index }}" class="carousel-item relative w-full snap-start flex justify-center items-center bg-black/5" style="aspect-ratio: 3/4;">
                                    <img src="{{ Storage::url($foto) }}" 
                                         class="absolute inset-0 w-full h-full object-cover transition duration-500 cursor-zoom-in"
                                         onclick="window.open(this.src, '_blank')">
                                    <div class="absolute bottom-4 right-4 bg-black/60 text-white text-[10px] px-2.5 py-1 rounded-full font-bold backdrop-blur-md border border-white/10">
                                        {{ $index + 1 }} / {{ count($this->pengaduan->foto_bukti) }}
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if(count($this->pengaduan->foto_bukti) > 1)
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="document.getElementById('carousel-admin').scrollBy({left: -document.getElementById('carousel-admin').offsetWidth, behavior: 'smooth'})" class="btn btn-circle btn-xs sm:btn-sm bg-white/20 backdrop-blur-md border-none text-white hover:bg-white/40">❮</button>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="document.getElementById('carousel-admin').scrollBy({left: document.getElementById('carousel-admin').offsetWidth, behavior: 'smooth'})" class="btn btn-circle btn-xs sm:btn-sm bg-white/20 backdrop-blur-md border-none text-white hover:bg-white/40">❯</button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Konteks Laporan: Lokasi & Waktu Kejadian --}}
                    @if($this->pengaduan->lokasi_kejadian || $this->pengaduan->tanggal_kejadian)
                        <div class="bg-base-200/30 p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-base-200 flex flex-col sm:flex-row gap-4 sm:gap-6 mt-4">
                            @if($this->pengaduan->lokasi_kejadian)
                            <div class="flex-1 flex items-start gap-3">
                                <div class="p-2 bg-error/10 rounded-lg text-error shrink-0">
                                    <x-icon name="o-map-pin" class="w-4 h-4 sm:w-5 sm:h-5" />
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-black uppercase text-base-content/40 block mb-0.5">Lokasi Terkait</span>
                                    <p class="text-xs sm:text-[13px] font-bold text-base-content leading-snug">
                                        {{ $this->pengaduan->lokasi_kejadian }}
                                    </p>
                                    @if($this->pengaduan->latitude)
                                        <a href="https://maps.google.com/?q={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}"
                                            target="_blank"
                                            class="text-primary hover:underline text-[10px] sm:text-xs mt-1.5 inline-flex items-center gap-1 font-bold">
                                            Buka di Peta <x-icon name="o-arrow-top-right-on-square" class="w-3 h-3" />
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($this->pengaduan->tanggal_kejadian)
                            {{-- Divider on desktop --}}
                            @if($this->pengaduan->lokasi_kejadian)
                                <div class="hidden sm:block w-px bg-base-200"></div>
                            @endif
                            <div class="flex-1 flex items-start gap-3">
                                <div class="p-2 bg-warning/10 rounded-lg text-warning shrink-0">
                                    <x-icon name="o-calendar-days" class="w-4 h-4 sm:w-5 sm:h-5" />
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase text-base-content/40 block mb-0.5">Waktu Kejadian</span>
                                    <p class="text-xs sm:text-[13px] font-bold text-base-content">
                                        {{ $this->pengaduan->tanggal_kejadian->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif

                    <div
                        class="text-[14px] sm:text-[15px] leading-loose text-base-content/80 font-medium whitespace-pre-line break-words overflow-hidden py-2 sm:py-4">
                        {{ $this->pengaduan->deskripsi }}
                    </div>

                    @if($this->pengaduan->harapan_pelapor)
                    <div class="p-4 bg-primary/5 border-l-4 border-primary rounded-r-xl mt-4 break-all overflow-hidden">
                        <p class="text-[10px] font-black text-primary mb-1">Harapan Pelapor:</p>
                        <p class="text-sm font-bold text-base-content/80 italic">"{{ $this->pengaduan->harapan_pelapor }}"</p>
                    </div>
                    @endif

                    {{-- Komentar --}}
                    @livewire('pengaduan-komentar', ['pengaduan_id' => $this->pengaduan->id])

                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Admin Specific Info --}}
        <div class="space-y-4 sm:space-y-6 px-3 sm:px-0">

            {{-- Card Penilaian Warga (Jika Ada) --}}
            @if($this->pengaduan->rating)
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-2 py-3 bg-yellow-50 border-b border-yellow-100 flex items-center gap-2">
                    <x-icon name="o-star" class="w-3.5 h-3.5 text-yellow-600" />
                    <h2 class="font-bold text-yellow-800 text-[10px] uppercase tracking-wider">Kepuasan Pelapor (IKM)</h2>
                </div>
                <div class="p-5 text-center">
                    <div class="flex justify-center gap-1 mb-2">
                        @foreach(range(1, 5) as $i)
                            <x-icon name="s-star" class="w-6 h-6 {{ $i <= $this->pengaduan->rating ? 'text-yellow-400' : 'text-base-300' }}" />
                        @endforeach
                    </div>
                    <div class="badge badge-warning badge-sm font-black mb-4">{{ $this->pengaduan->rating }} / 5</div>
                    
                    @if($this->pengaduan->rating_komentar)
                    <div class="p-3 bg-base-200/50 rounded-xl border border-base-200 text-left relative overflow-hidden break-words">
                        <x-icon name="o-chat-bubble-bottom-center-text" class="absolute -top-2 -right-2 w-5 h-5 text-base-content/10" />
                        <p class="text-[10px] font-black text-base-content/40 mb-1 uppercase tracking-tighter">Ulasan Warga:</p>
                        <p class="text-[12px] leading-relaxed text-base-content font-medium italic">"{{ $this->pengaduan->rating_komentar }}"</p>
                    </div>
                    @else
                        <p class="text-[10px] text-base-content/40 italic">Tidak ada ulasan tertulis.</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Card Catatan Internal --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-2 py-3 bg-base-200/30 border-b border-base-200 flex items-center justify-between">
                    <h2 class="font-bold text-base-content/80 text-[10px] flex items-center gap-2">
                        <x-icon name="o-pencil-square" class="w-3.5 h-3.5" /> Catatan Internal (Rahasia)
                    </h2>
                    @if(session()->has('success_catatan'))
                        <span class="text-[9px] font-bold text-success animate-pulse">Tersimpan!</span>
                    @endif
                </div>
                <div class="p-4">
                    <x-form wire:submit="saveCatatanInternal">
                        <x-textarea wire:model="catatan_internal" placeholder="Tulis catatan rahasia di sini... (Cuma Admin yang bisa lihat)" rows="4" class="text-xs bg-base-200/50 border-none focus:ring-1 focus:ring-primary h-24" />
                        <div class="mt-2 flex justify-end">
                            <x-button label="Simpan Catatan" type="submit" icon="o-check" class="btn-xs btn-primary text-white font-bold" spinner="saveCatatanInternal" />
                        </div>
                    </x-form>
                </div>
            </div>

            {{-- Mini Map Card --}}
            @if($this->pengaduan->latitude && $this->pengaduan->longitude)
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-2 py-3 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-map-pin" class="w-3.5 h-3.5 text-error" />
                    <h2 class="font-bold text-base-content/80 text-[10px]">Peta Lokasi Kejadian</h2>
                </div>
                <div id="admin-mini-map" style="height: 200px; width: 100%;"></div>
                <div class="px-3 py-2 text-[10px] text-base-content/50 font-mono border-t border-base-200">
                    {{ $this->pengaduan->latitude }}, {{ $this->pengaduan->longitude }}
                    <a href="https://maps.google.com/?q={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}" target="_blank" class="ml-2 text-primary font-bold hover:underline">Buka Maps ↗</a>
                </div>
            </div>
            <script>
                function initAdminMap() {
                    var el = document.getElementById('admin-mini-map');
                    if (el && typeof L !== 'undefined' && !el._leaflet_id) {
                        var lat = {{ $this->pengaduan->latitude }};
                        var lng = {{ $this->pengaduan->longitude }};
                        var map = L.map('admin-mini-map', {
                            zoomControl: true,
                            scrollWheelZoom: true,
                            attributionControl: false
                        }).setView([lat, lng], 16);
                        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}').addTo(map);
                        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', { opacity: 1 }).addTo(map);
                        L.circleMarker([lat, lng], {
                            radius: 10,
                            color: '#ef4444',
                            fillColor: '#ef4444',
                            fillOpacity: 0.8,
                            weight: 3
                        }).addTo(map);
                    }
                }
                document.addEventListener('livewire:navigated', initAdminMap);
                document.addEventListener('DOMContentLoaded', initAdminMap);
            </script>
            @endif

            {{-- Card Pelapor --}}
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 overflow-hidden">
                <div class="px-2 py-3 bg-base-200/30 border-b border-base-200">
                    <h2 class="font-bold text-base-content/80 text-[10px] flex items-center gap-2">
                        <x-icon name="o-user" class="w-3.5 h-3.5" /> Data Lengkap Pelapor
                    </h2>
                </div>
                <div class="p-4 flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <x-user-avatar :user="$this->pengaduan->user" size="w-10 h-10" />
                        <div class="overflow-hidden">
                            <p class="font-bold text-base-content text-sm truncate">{{ $this->pengaduan->user?->name ?? 'User Terhapus' }}</p>
                            <p class="text-[11px] text-base-content/60 truncate">{{ $this->pengaduan->user?->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-base-200/50 rounded-xl p-3 border border-base-200 space-y-2">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="font-bold text-base-content/50">NIK</span>
                            <span class="font-mono font-black text-base-content/80">{{ $this->pengaduan->user?->nik ?? '-' }}</span>
                        </div>
                        <div class="divider my-0 opacity-10"></div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="font-bold text-base-content/50">No. WA</span>
                            <span class="font-mono font-black text-base-content/80">{{ $this->pengaduan->user?->no_wa ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Riwayat --}}
            <div class="bg-base-100 sm:rounded-2xl sm:shadow-sm border border-base-200 overflow-hidden">
                <div class="px-2 py-3 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-clock" class="w-3.5 h-3.5 text-primary" />
                    <h2 class="font-bold text-base-content/80 text-[10px]">Log Aktivitas & Riwayat</h2>
                </div>
                <div class="px-3 py-4 sm:p-5">
                    @if($this->pengaduan->histories->count() > 0)
                    <div class="relative pl-6 space-y-6 before:absolute before:inset-y-2 before:left-[11px] before:w-[2px] before:bg-base-300">
                        @foreach($this->pengaduan->histories->sortByDesc('created_at') as $index => $history)
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
                        @endphp
                        <div class="relative z-10 pl-2">
                            <div class="absolute -left-[20px] top-1 w-4 h-4 rounded-full bg-base-100 border-[3px] {{ $borderColor }} flex items-center justify-center"></div>
                            <div class="flex flex-col gap-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-extrabold text-[12px] {{ $timelineColor }} uppercase">{{ $history->status_baru }}</span>
                                    <span class="text-[10px] font-semibold text-base-content/40">{{ $history->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-[11px] font-medium text-base-content/70">Oleh: {{ $history->user?->name ?? 'User Terhapus' }} ({{ $history->user ? ucfirst($history->user->role) : 'N/A' }})</div>
                                @if($history->keterangan_admin)
                                <p class="text-[11px] leading-relaxed italic text-base-content/60 bg-base-200/50 p-2 rounded-lg mt-1 break-words overflow-hidden">"{{ $history->keterangan_admin }}"</p>
                                @endif
                                @if($history->foto_bukti)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($history->foto_bukti) }}" class="h-20 w-auto rounded-lg border border-base-200" onclick="window.open(this.src, '_blank')">
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6 text-base-content/50">
                        <x-icon name="o-clock" class="w-8 h-8 opacity-20 mx-auto mb-2" />
                        <p class="text-xs font-semibold">Belum ada riwayat update.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <x-modal wire:model="updateModal" title="Perbarui Status Laporan" subtitle="Tambahkan catatan dan foto dokumentasi (opsional) untuk update ini.">
        <x-form wire:submit="saveStatusUpdate">
            <x-file label="Foto Dokumentasi (Opsional)" wire:model="update_foto" accept="image/*" :required="$update_status === 'selesai'" :hint="$update_status === 'selesai' ? 'Wajib menyertakan foto hasil pekerjaan untuk status Selesai.' : 'Lampirkan foto pendukung bila ada.'" />
            @if ($update_foto)
            <div class="mt-2 text-center border border-dashed rounded-lg p-2 bg-base-100">
                <img src="{{ $update_foto->temporaryUrl() }}" class="rounded shadow w-48 mx-auto mt-1 border border-base-300">
            </div>
            @endif
            <x-textarea label="Catatan / Tindak Lanjut" wire:model="update_keterangan" placeholder="Catat detail aktivitas / alasan perubahan status..." rows="3" :required="in_array($update_status, ['selesai', 'ditolak'])" />
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.updateModal = false" class="btn-ghost" />
                <x-button label="Simpan Pembaruan" type="submit" icon="o-check-circle" class="btn-primary text-white" spinner="saveStatusUpdate" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Modal Rujuk Laporan Selesai (Duplicate Handling) -->
    <x-modal wire:model="linkModal" title="Rujuk ke Laporan Selesai" subtitle="Gunakan fitur ini jika masalah ini merupakan duplikat dan sudah diselesaikan pada laporan lain.">
        <div class="space-y-4">
            <x-input wire:model.live.debounce.300ms="searchLinkedQuery" placeholder="Cari Kode Tracking atau Judul..." icon="o-magnifying-glass" hint="Ketik minimal 3 karakter untuk mencari laporan yang sudah berstatus 'Selesai'." />
            
            <div class="mt-4">
                @if(strlen($searchLinkedQuery) >= 3)
                    @if(count($linkedReports) > 0)
                        <div class="space-y-2">
                            @foreach($linkedReports as $lr)
                            <div class="flex items-center justify-between p-3 border border-base-300 rounded-xl bg-base-200/50 hover:border-primary transition-colors">
                                <div class="overflow-hidden">
                                    <div class="font-mono text-[10px] text-base-content/50">{{ $lr['kode_tracking'] }}</div>
                                    <div class="font-bold text-xs truncate">{{ $lr['judul'] }}</div>
                                </div>
                                <x-button label="Pilih & Selesaikan" wire:click="linkToReport({{ $lr['id'] }})" wire:confirm="Yakin ingin merujuk laporan ini? Status otomatis menjadi Selesai." class="btn-sm btn-primary text-white" />
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-xs text-base-content/50 border border-dashed rounded-xl">
                            Tidak ditemukan laporan selesai yang cocok.
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.linkModal = false" class="btn-ghost" />
        </x-slot:actions>
    </x-modal>
</div>