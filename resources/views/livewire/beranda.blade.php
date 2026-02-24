<div>
    <x-header title="Kembaran Ngadu" subtitle="Layanan Pengaduan Masyarakat Kecamatan Kembaran" size="text-3xl"
        class="mb-5">
        <x-slot:actions>
            <x-button label="Buat Laporan Baru" icon="o-plus-circle" class="btn-primary" link="/pengaduan/create" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Sidebar Filter / Sort -->
        <div class="md:col-span-1 border-r border-base-200 pr-4">
            <x-form wire:submit="render">
                <x-input label="Cari Laporan" wire:model.live.debounce="search" icon="o-magnifying-glass"
                    hint="Ketik judul/deskripsi" class="mb-4" />

                <x-select label="Kategori" wire:model.live="kategori_id" :options="$kategoris" option-label="nama"
                    option-value="id" placeholder="Semua Kategori" class="mb-4" />

                <x-radio label="Urutkan" wire:model.live="sort"
                    :options="[['id' => 'terbaru', 'name' => 'Terbaru'], ['id' => 'terpopuler', 'name' => 'Dukungan Terbanyak']]"
                    option-value="id" option-label="name" class="mb-4" />
            </x-form>

            <div class="mt-8">
                <h3 class="font-bold mb-3 text-sm text-gray-500 uppercase">Status Warna</h3>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex items-center gap-2"><x-badge class="badge-warning" /> Menunggu</div>
                    <div class="flex items-center gap-2"><x-badge class="badge-info" /> Diproses</div>
                    <div class="flex items-center gap-2"><x-badge class="badge-success" /> Selesai</div>
                    <div class="flex items-center gap-2"><x-badge class="badge-error" /> Ditolak</div>
                </div>
            </div>
        </div>

        <!-- Main Feed -->
        <div class="md:col-span-3 space-y-6">
            @forelse($pengaduans as $pengaduan)
            <x-card class="shadow-sm border border-base-200 hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <x-icon name="o-folder" class="w-3 h-3" /> {{ $pengaduan->kategori->nama }} &bull;
                            <x-icon name="o-clock" class="w-3 h-3" /> {{ $pengaduan->created_at->diffForHumans() }}
                        </div>
                        <h2 class="text-xl font-bold cursor-pointer hover:text-primary transition-colors">
                            <a href="#">{{ $pengaduan->judul }}</a>
                        </h2>
                    </div>

                    @if($pengaduan->status == 'menunggu')
                    <x-badge value="Menunggu" class="badge-warning font-bold" />
                    @elseif($pengaduan->status == 'diproses')
                    <x-badge value="Diproses" class="badge-info font-bold" />
                    @elseif($pengaduan->status == 'selesai')
                    <x-badge value="Selesai" class="badge-success font-bold" />
                    @elseif($pengaduan->status == 'ditolak')
                    <x-badge value="Ditolak" class="badge-error font-bold" />
                    @endif
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                    {{ $pengaduan->deskripsi }}
                </p>

                <div class="flex justify-between items-center border-t border-base-200 pt-3 mt-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <x-icon name="o-user" class="w-4 h-4" />
                        {{ $pengaduan->is_anonymous ? 'Anonim' : $pengaduan->user->name }}
                    </div>

                    <div class="flex gap-2 text-right items-center">
                        @if(session()->has('success'))
                        <span class="text-xs text-success mr-2 animate-pulse">{{ session('success') }}</span>
                        @endif
                        @if(session()->has('error'))
                        <span class="text-xs text-error mr-2">{{ session('error') }}</span>
                        @endif

                        <x-button label="{{ $pengaduan->dukungans_count }}" icon="o-hand-thumb-up"
                            class="btn-sm btn-ghost text-primary" tooltip="Dukung / Batal Dukung"
                            wire:click="upvote({{ $pengaduan->id }})" />

                        @php
                        $waText = urlencode("Bantu dukung dan kawal laporan ini di Kembaran Ngadu: *" .
                        $pengaduan->judul . "*. Klik tautan ini untuk selengkapnya: " . url('/'));
                        @endphp
                        <a href="https://wa.me/?text={{ $waText }}" target="_blank"
                            class="btn btn-sm btn-circle btn-ghost" title="Share WhatsApp">
                            <x-icon name="o-share" />
                        </a>
                    </div>
                </div>
            </x-card>
            @empty
            <div class="text-center py-10 bg-base-200/50 rounded-xl border border-dashed border-base-300">
                <x-icon name="o-inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                <h3 class="text-lg font-bold text-gray-500">Belum ada laporan</h3>
                <p class="text-gray-400 text-sm mt-1">Jadilah yang pertama melaporkan masalah di Kembaran.</p>
            </div>
            @endforelse

            <div class="mt-6">
                {{ $pengaduans->links() }}
            </div>
        </div>

    </div>
</div>