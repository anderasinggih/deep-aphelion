<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-header title="Kembaran Ngadu" subtitle="Layanan Pengaduan Masyarakat Kecamatan Kembaran" size="text-3xl"
        class="mb-5">
        <x-slot:actions>
            <x-button label="Buat Laporan Baru" icon="o-plus-circle" class="btn-primary" link="/pengaduan/create" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">

        <!-- Sidebar Filter / Sort -->
        <div class="hidden space-y-6 lg:block lg:col-span-1">
            <div class="p-6 border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <h3 class="mb-4 text-xs font-bold tracking-wider uppercase text-base-content/50">Pencarian & Filter</h3>
                <x-form wire:submit="render">
                    <x-input wire:model.live.debounce="search" icon="o-magnifying-glass" placeholder="Cari laporan..."
                        class="mb-4 input-bordered" />

                    <x-select wire:model.live="kategori_id" :options="$kategoris" option-label="nama" option-value="id"
                        placeholder="Semua Kategori" class="mb-4 select-bordered" />

                    <x-radio wire:model.live="sort"
                        :options="[['id' => 'terbaru', 'name' => 'Terbaru'], ['id' => 'terpopuler', 'name' => 'Dukungan Terbanyak']]"
                        option-value="id" option-label="name" class="gap-2 p-0" />
                </x-form>
            </div>

            <div class="p-6 border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <h3 class="mb-4 text-xs font-bold tracking-wider uppercase text-base-content/50">Legenda Status</h3>
                <div class="flex flex-col gap-3 text-sm font-medium text-base-content/80">
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-warning"></span> Menunggu
                        Validasi</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-info"></span> Sedang
                        Diproses</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-success"></span> Selesai
                        Ditangani</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-error"></span> Laporan
                        Ditolak</div>
                </div>
            </div>
        </div>

        <!-- Main Feed -->
        <div class="space-y-6 lg:col-span-3">
            <!-- Mobile Filter Toggle (Visible only on small screens) -->
            <div class="block lg:hidden">
                <div class="p-4 border shadow-sm bg-base-100 rounded-2xl border-base-200">
                    <x-input wire:model.live.debounce="search" icon="o-magnifying-glass" placeholder="Cari laporan..."
                        class="mb-3 input-bordered" />
                    <div class="flex gap-2">
                        <x-select wire:model.live="kategori_id" :options="$kategoris" option-label="nama"
                            option-value="id" placeholder="Semua Kategori" class="flex-1 select-bordered select-sm" />
                        <x-select wire:model.live="sort"
                            :options="[['id' => 'terbaru', 'name' => 'Terbaru'], ['id' => 'terpopuler', 'name' => 'Terapik']]"
                            option-value="id" option-label="name" class="flex-1 select-bordered select-sm" />
                    </div>
                </div>
            </div>
            @forelse($pengaduans as $pengaduan)
            <div
                class="transition-all duration-300 border shadow-sm bg-base-100 rounded-2xl border-base-200 hover:shadow-md hover:border-primary/30">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex-1 pr-4">
                            <div class="flex items-center gap-2 mb-2 text-xs font-medium text-base-content/60">
                                <span class="flex items-center gap-1 px-2 py-1 rounded-md bg-base-200">
                                    <x-icon name="o-folder" class="w-3.5 h-3.5" /> {{ $pengaduan->kategori->nama }}
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">
                                    <x-icon name="o-clock" class="w-3.5 h-3.5" /> {{
                                    $pengaduan->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <h2
                                class="text-lg font-bold leading-tight cursor-pointer sm:text-xl text-base-content hover:text-primary transition-colors">
                                <a href="#">{{ $pengaduan->judul }}</a>
                            </h2>
                        </div>

                        <div class="flex-shrink-0">
                            @if($pengaduan->status == 'menunggu')
                            <div class="font-bold badge badge-warning badge-outline">Menunggu</div>
                            @elseif($pengaduan->status == 'diproses')
                            <div class="font-bold badge badge-info badge-outline">Diproses</div>
                            @elseif($pengaduan->status == 'selesai')
                            <div class="font-bold badge badge-success badge-outline">Selesai</div>
                            @elseif($pengaduan->status == 'ditolak')
                            <div class="font-bold badge badge-error badge-outline">Ditolak</div>
                            @endif
                        </div>
                    </div>

                    <p class="mb-5 text-sm leading-relaxed sm:text-base text-base-content/80 line-clamp-3">
                        {{ $pengaduan->deskripsi }}
                    </p>

                    <div
                        class="flex flex-col gap-4 pt-4 mt-2 border-t sm:flex-row sm:items-center sm:justify-between border-base-200">
                        <div
                            class="flex items-center self-start gap-2 px-3 py-1.5 text-sm font-medium rounded-lg text-base-content/70 bg-base-200/60">
                            <x-icon name="o-user" class="w-4 h-4 text-base-content/50" />
                            {{ $pengaduan->is_anonymous ? 'Dilaporkan Anonim' : $pengaduan->user->name }}
                        </div>

                        <div class="flex items-center self-end gap-2 sm:self-auto">
                            @if(session()->has('success') && session('id') == $pengaduan->id)
                            <span class="mr-2 text-xs font-semibold animate-pulse text-success">{{ session('success')
                                }}</span>
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
                class="flex flex-col items-center justify-center py-16 text-center border-2 border-dashed bg-base-100/50 rounded-2xl border-base-300">
                <div class="p-4 mb-4 rounded-full bg-base-200">
                    <x-icon name="o-inbox" class="w-10 h-10 text-base-content/40" />
                </div>
                <h3 class="text-lg font-bold text-base-content/70">Belum ada laporan pengaduan</h3>
                <p class="max-w-sm mt-2 text-sm text-base-content/50">Jadilah yang pertama melaporkan masalah atau
                    aspirasi di lingkungan Kecamatan Kembaran.</p>
            </div>
            @endforelse

            <div class="mt-6">
                {{ $pengaduans->links() }}
            </div>
        </div>

    </div>
</div>