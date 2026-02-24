<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-header title="Kembaran Ngadu" subtitle="Layanan Pengaduan Masyarakat Kecamatan Kembaran" size="text-3xl"
        class="mb-5">
        <x-slot:actions>
            <x-button label="Buat Laporan Baru" icon="o-plus-circle" class="btn-primary" link="/pengaduan/create" />
        </x-slot:actions>
    </x-header>

    <!-- Unified Filter Bar Container -->
    <div class="p-4 mb-8 border lg:p-6 shadow-sm bg-base-200 rounded-[1.5rem] border-base-300">
        <x-form wire:submit="render">
            <div class="flex flex-col gap-4 md:flex-row md:items-center">

                <!-- Search Box -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <x-icon name="o-magnifying-glass" class="w-5 h-5 opacity-50 text-base-content/50" />
                    </div>
                    <input type="text" wire:model.live.debounce="search" placeholder="Cari laporan..."
                        class="w-full h-12 pl-12 pr-4 text-sm transition-colors border shadow-sm outline-none bg-base-100 border-base-content/20 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary text-base-content" />
                </div>

                <!-- Selects Row -->
                <div class="flex flex-col gap-4 sm:flex-row md:w-auto">
                    <!-- Kategori Dropdown -->
                    <div class="relative w-full sm:w-56 md:w-64">
                        <select wire:model.live="kategori_id"
                            class="w-full h-12 px-4 pr-10 text-sm transition-colors border shadow-sm appearance-none outline-none cursor-pointer bg-base-100 border-base-content/20 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary text-base-content">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-icon name="o-chevron-down" class="w-4 h-4 opacity-50 text-base-content/50" />
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="relative w-full sm:w-40 md:w-48">
                        <select wire:model.live="sort"
                            class="w-full h-12 px-4 pr-10 text-sm font-medium transition-colors border shadow-sm appearance-none outline-none cursor-pointer bg-base-100 border-base-content/20 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary text-base-content">
                            <option value="terbaru">Terbaru</option>
                            <option value="terpopuler">Terpopuler</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <x-icon name="o-chevron-down" class="w-4 h-4 opacity-50 text-base-content/50" />
                        </div>
                    </div>
                </div>

            </div>
        </x-form>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
        <!-- Main Feed -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:col-span-4">
            @forelse($pengaduans as $pengaduan)
            <div
                class="transition-all duration-300 border shadow-sm bg-base-200 rounded-[1.5rem] border-base-300 hover:shadow-md hover:border-primary/30">
                <div class="p-3 sm:p-4">
                    <!-- Category & Time -->
                    <div
                        class="flex flex-wrap items-center gap-1.5 mb-2 text-[11px] sm:text-xs font-medium text-base-content/60">
                        <span class="flex items-center gap-1">
                            <x-icon name="o-folder" class="w-3.5 h-3.5" /> {{ $pengaduan->kategori->nama }}
                        </span>
                        <span>&bull;</span>
                        <span class="flex items-center gap-1">
                            <x-icon name="o-clock" class="w-3.5 h-3.5" /> {{ $pengaduan->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Image Content -->
                    @if($pengaduan->foto_bukti)
                    <div
                        class="mb-3 overflow-hidden border shadow-sm rounded-xl sm:rounded-2xl border-base-300 aspect-square relative">
                        <img src="{{ Storage::url($pengaduan->foto_bukti) }}" alt="Bukti {{ $pengaduan->judul }}"
                            class="absolute inset-0 object-cover w-full h-full hover:scale-105 transition-transform duration-500 cursor-zoom-in"
                            onclick="window.open(this.src, '_blank')" />
                    </div>
                    @endif

                    <!-- Title & Location -->
                    <h2
                        class="text-base sm:text-lg font-bold leading-tight cursor-pointer text-base-content hover:text-primary transition-colors mb-0.5">
                        <a href="#">{{ $pengaduan->judul }}</a>
                    </h2>
                    <div class="flex items-start gap-1 text-xs text-base-content/60 mb-2">
                        <x-icon name="o-map-pin" class="w-3 h-3 mt-0.5 flex-shrink-0 text-error/80" />
                        <span class="line-clamp-1 sm:line-clamp-2">{{ $pengaduan->lokasi_kejadian ?? 'Lokasi tidak
                            spesifik (Data koordinat terlampir)' }}</span>
                    </div>

                    <!-- Description -->
                    <p class="mb-3 text-sm leading-relaxed text-base-content/80 line-clamp-3">
                        {{ $pengaduan->deskripsi }}
                    </p>

                    <!-- Footer Interactions -->
                    <div class="flex flex-col xl:flex-row gap-2 mt-2 xl:items-center xl:justify-between">
                        <!-- User & Status (Left Side / Top on mobile) -->
                        <div class="flex items-center justify-between w-full xl:w-auto xl:justify-start gap-4">
                            <div class="flex items-center gap-1.5 text-xs sm:text-sm font-medium text-base-content/70">
                                <x-icon name="o-user" class="w-4 h-4 text-base-content/50" />
                                {{ $pengaduan->is_anonymous ? 'Anonim' : $pengaduan->user->name }}
                            </div>
                            <!-- Status Badge -->
                            <div class="flex-shrink-0">
                                @if($pengaduan->status == 'menunggu')
                                <div
                                    class="px-4 py-1.5 text-xs sm:text-sm font-bold rounded-full bg-warning/20 text-warning">
                                    Menunggu</div>
                                @elseif($pengaduan->status == 'diproses')
                                <div class="px-4 py-1.5 text-xs sm:text-sm font-bold rounded-full bg-info/20 text-info">
                                    Diproses</div>
                                @elseif($pengaduan->status == 'selesai')
                                <div
                                    class="px-4 py-1.5 text-xs sm:text-sm font-bold rounded-full bg-success/20 text-success">
                                    Selesai</div>
                                @elseif($pengaduan->status == 'ditolak')
                                <div
                                    class="px-4 py-1.5 text-xs sm:text-sm font-bold rounded-full bg-error/20 text-error">
                                    Ditolak</div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions (Right Side / Bottom on mobile) -->
                        <div class="flex items-center gap-1 self-end xl:self-auto">
                            @if(session()->has('success') && session('id') == $pengaduan->id)
                            <span class="mr-2 text-[10px] sm:text-xs font-semibold animate-pulse text-success">{{
                                session('success') }}</span>
                            @endif

                            <x-button label="{{ $pengaduan->dukungans_count }}" icon="o-hand-thumb-up"
                                class="rounded-lg btn-sm btn-ghost text-primary hover:bg-primary/10"
                                tooltip="Dukung Laporan" wire:click="upvote({{ $pengaduan->id }})" />

                            @php
                            $waText = urlencode("Bantu dukung dan kawal laporan ini di Kembaran Ngadu: *" .
                            $pengaduan->judul . "*. Klik tautan ini untuk mambaca selengkapnya: " . url('/'));
                            @endphp
                            <a href="https://wa.me/?text={{ $waText }}" target="_blank"
                                class="rounded-lg btn btn-sm btn-square btn-ghost hover:bg-success/10 hover:text-success"
                                title="Bagikan ke WhatsApp">
                                <x-icon name="o-share" class="w-4 h-4 sm:w-5 sm:h-5" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div
                class="flex flex-col items-center justify-center py-20 text-center border-2 border-dashed bg-base-200/50 rounded-[1.5rem] border-base-300">
                <div class="p-4 mb-4 rounded-full bg-base-200">
                    <x-icon name="o-inbox" class="w-10 h-10 text-base-content/40" />
                </div>
                <h3 class="text-lg font-bold text-base-content/70">Belum ada laporan pengaduan</h3>
                <p class="max-w-sm mt-2 text-sm text-base-content/50">Jadilah yang pertama melaporkan masalah atau
                    aspirasi di lingkungan Kecamatan Kembaran.</p>
            </div>
            @endforelse

            <div class="mt-6">