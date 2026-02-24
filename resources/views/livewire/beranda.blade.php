<div class="w-full px-0.1 py-6 mx-auto lg:px-4">
    <x-header title="Kembaran Ngadu" subtitle="Layanan Pengaduan Masyarakat Kecamatan Kembaran" size="text-3xl"
        class="mb-6">
        <x-slot:actions>
            <x-button label="Mulai Ngadu" class="btn-primary" link="/pengaduan/create" />
        </x-slot:actions>
    </x-header>

    @php
    $sortOptions = [
    ['id' => 'terbaru', 'nama' => 'Terbaru'],
    ['id' => 'terpopuler', 'nama' => 'Populer'], // Disingkat sedikit agar aman di layar HP kecil
    ];
    @endphp


    <form wire:submit.prevent class="p-0.1 mb-7 lg:p-4 shadow-sm rounded-2xl">

        <div class="flex flex-row items-center w-full gap-1.5 lg:gap-4">

            <div class="flex-1">
                <x-input placeholder="Cari..." wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    clearable />
            </div>

            <div class="w-28 sm:w-40 lg:w-48 shrink-0">
                <x-select wire:model.live="kategori_id" :options="$kategoris" option-value="id" option-label="nama"
                    placeholder="Kategori" icon="o-tag" />
            </div>

            <div class="w-24 sm:w-36 lg:w-40 shrink-0">
                <x-select wire:model.live="sort" :options="$sortOptions" option-value="id" option-label="nama"
                    icon="o-arrows-up-down" />
            </div>

        </div>
    </form>


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-3">
        @forelse($pengaduans as $pengaduan)
        <div
            class="flex flex-col overflow-hidden transition-all duration-300 transform border shadow-sm bg-base-100 rounded-2xl border-base-200 hover:shadow-lg hover:border-primary/50 hover:-translate-y-1 group">

            @if($pengaduan->foto_bukti)
            <div class="relative w-full overflow-hidden bg-base-200 aspect-video">
                <img src="{{ Storage::url($pengaduan->foto_bukti) }}" alt="Bukti {{ $pengaduan->judul }}"
                    class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 cursor-zoom-in group-hover:scale-110"
                    onclick="window.open(this.src, '_blank')" />

                <div class="absolute top-3 right-3 shadow-sm">
                    @if($pengaduan->status == 'menunggu')
                    <span
                        class="px-3 py-1 text-xs font-bold rounded-full bg-warning text-warning-content shadow-sm">Menunggu</span>
                    @elseif($pengaduan->status == 'diproses')
                    <span
                        class="px-3 py-1 text-xs font-bold rounded-full bg-info text-info-content shadow-sm">Diproses</span>
                    @elseif($pengaduan->status == 'selesai')
                    <span
                        class="px-3 py-1 text-xs font-bold rounded-full bg-success text-success-content shadow-sm">Selesai</span>
                    @elseif($pengaduan->status == 'ditolak')
                    <span
                        class="px-3 py-1 text-xs font-bold rounded-full bg-error text-error-content shadow-sm">Ditolak</span>
                    @endif
                </div>
            </div>
            @endif

            <div class="flex flex-col flex-1 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3 text-xs font-medium text-base-content/60">
                    <span
                        class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-base-200 text-primary cursor-pointer transition-colors hover:bg-primary/20">
                        <x-icon name="{{ $pengaduan->kategori->icon ?? 'o-tag' }}" class="w-3.5 h-3.5" />
                        {{ $pengaduan->kategori->nama }}
                    </span>
                    <span class="flex items-center gap-1" title="{{ $pengaduan->created_at->format('d M Y, H:i') }}">
                        <x-icon name="o-clock" class="w-3.5 h-3.5" /> {{ $pengaduan->created_at->diffForHumans() }}
                    </span>
                </div>

                <h2
                    class="mb-1 text-base font-bold leading-tight transition-colors sm:text-lg cursor-pointer text-base-content hover:text-primary line-clamp-2">
                    <a href="#">{{ $pengaduan->judul }}</a>
                </h2>
                <div class="flex items-start gap-1 mb-3 text-xs text-base-content/50">
                    <x-icon name="o-map-pin" class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-error/80" />
                    <span class="line-clamp-1 cursor-default hover:text-base-content/80 transition-colors">{{
                        $pengaduan->lokasi_kejadian ?? 'Lokasi via koordinat peta' }}</span>
                </div>

                <p class="flex-1 mb-4 text-sm leading-relaxed text-base-content/70 line-clamp-3">
                    {{ $pengaduan->deskripsi }}
                </p>

                <div class="flex items-center justify-between pt-4 mt-auto border-t border-base-200">
                    <div class="flex items-center gap-2 text-sm font-medium text-base-content/80">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-base-300">
                            <x-icon name="o-user" class="w-4 h-4 opacity-70" />
                        </div>
                        <span class="line-clamp-1 max-w-[100px] sm:max-w-[120px] cursor-default">
                            {{ $pengaduan->is_anonymous ? 'Anonim' : $pengaduan->user->name }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        @if(session()->has('success') && session('id') == $pengaduan->id)
                        <span class="mr-2 text-[10px] font-semibold animate-pulse text-success">{{ session('success')
                            }}</span>
                        @endif

                        <x-button label="{{ $pengaduan->dukungans_count }}" icon="o-hand-thumb-up"
                            class="rounded-lg btn-sm btn-ghost text-primary hover:bg-primary hover:text-primary-content transition-all duration-200 hover:scale-105"
                            tooltip="Dukung Laporan" wire:click="upvote({{ $pengaduan->id }})" />

                        @php
                        $waText = urlencode("Bantu dukung dan kawal laporan ini di Kembaran Ngadu: *" .
                        $pengaduan->judul . "*. Klik tautan ini untuk membaca selengkapnya: " . url('/'));
                        @endphp

                        <a href="https://wa.me/?text={{ $waText }}" target="_blank"
                            class="flex items-center justify-center w-8 h-8 rounded-lg btn-ghost text-base-content/70 hover:bg-success hover:text-success-content transition-all duration-200 hover:scale-105"
                            title="Bagikan ke WhatsApp">
                            <x-icon name="o-share" class="w-5 h-5" />
                        </a>
                    </div>
                </div>
            </div>

            @if(!$pengaduan->foto_bukti)
            <div class="px-4 pb-4 sm:px-5">
                @if($pengaduan->status == 'menunggu')
                <div
                    class="w-full py-1.5 text-xs text-center font-bold rounded-lg bg-warning/20 text-warning transition-colors cursor-default hover:bg-warning/30">
                    Menunggu</div>
                @elseif($pengaduan->status == 'diproses')
                <div
                    class="w-full py-1.5 text-xs text-center font-bold rounded-lg bg-info/20 text-info transition-colors cursor-default hover:bg-info/30">
                    Diproses</div>
                @elseif($pengaduan->status == 'selesai')
                <div
                    class="w-full py-1.5 text-xs text-center font-bold rounded-lg bg-success/20 text-success transition-colors cursor-default hover:bg-success/30">
                    Selesai</div>
                @elseif($pengaduan->status == 'ditolak')
                <div
                    class="w-full py-1.5 text-xs text-center font-bold rounded-lg bg-error/20 text-error transition-colors cursor-default hover:bg-error/30">
                    Ditolak</div>
                @endif
            </div>
            @endif

        </div>
        @empty
        <div
            class="flex flex-col items-center justify-center py-20 text-center border-2 border-dashed lg:col-span-3 xl:col-span-4 2xl:col-span-3 bg-base-200/50 rounded-[1.5rem] border-base-300">
            <div class="p-4 mb-4 rounded-full bg-base-100 shadow-sm">
                <x-icon name="o-inbox" class="w-10 h-10 text-base-content/40" />
            </div>
            <h3 class="text-lg font-bold text-base-content/70">Belum ada laporan pengaduan</h3>
            <p class="max-w-sm mt-2 text-sm text-base-content/50">Jadilah yang pertama melaporkan masalah atau aspirasi
                di lingkungan Kecamatan Kembaran.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $pengaduans->links() }}
    </div>
</div>