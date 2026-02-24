<div class="max-w-7xl mx-auto py-8 text-black px-4">
    <x-header title="Manajemen Pengaduan" subtitle="Data seluruh laporan warga Kecamatan Kembaran" size="text-2xl"
        class="mb-5">
        <x-slot:actions>
            <x-button label="Export Data" icon="o-document-arrow-down" class="btn-outline" />
        </x-slot:actions>
    </x-header>

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="alert-success mb-5">
        {{ session('success') }}
    </x-alert>
    @endif
    @if (session()->has('error'))
    <x-alert icon="o-exclamation-triangle" class="alert-error mb-5">
        {{ session('error') }}
    </x-alert>
    @endif

    <x-card class="shadow-sm">

        <!-- Filter Bar -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <x-input wire:model.live.debounce="search" icon="o-magnifying-glass"
                placeholder="Cari Judul / Nama Warga..." class="flex-1" />

            <x-select wire:model.live="statusFilter" :options="[
                ['id' => '', 'name' => 'Semua Status'],
                ['id' => 'menunggu', 'name' => 'Menunggu'],
                ['id' => 'diproses', 'name' => 'Diproses'],
                ['id' => 'selesai', 'name' => 'Selesai'],
                ['id' => 'ditolak', 'name' => 'Ditolak'],
            ]" option-value="id" option-label="name" class="w-full md:w-48" />
        </div>

        <!-- Data Laporan -->
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Tgl Masuk</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Laporan</th>
                        <th>Status & Petugas</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $pengaduan)
                    <tr class="hover">
                        <td class="whitespace-nowrap">{{ $pengaduan->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="font-bold">{{ $pengaduan->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $pengaduan->user->nik }}</div>
                        </td>
                        <td>{{ $pengaduan->kategori->nama }}</td>
                        <td>
                            <div class="font-bold line-clamp-1" title="{{ $pengaduan->judul }}">{{ $pengaduan->judul }}
                            </div>
                            <div class="text-xs text-gray-400 max-w-[200px] truncate"
                                title="{{ $pengaduan->deskripsi }}">{{ $pengaduan->deskripsi }}</div>
                        </td>
                        <td>
                            <div>
                                @if($pengaduan->status == 'menunggu')
                                <x-badge value="Menunggu" class="badge-warning badge-sm" />
                                @elseif($pengaduan->status == 'diproses')
                                <x-badge value="Diproses" class="badge-info badge-sm" />
                                @elseif($pengaduan->status == 'selesai')
                                <x-badge value="Selesai" class="badge-success badge-sm" />
                                @elseif($pengaduan->status == 'ditolak')
                                <x-badge value="Ditolak" class="badge-error badge-sm" />
                                @endif
                            </div>
                            @if($pengaduan->petugas)
                            <div class="text-xs mt-1 text-primary flex items-center gap-1">
                                <x-icon name="o-user" class="w-3 h-3" />
                                {{ $pengaduan->petugas->name }}
                            </div>
                            @else
                            <div class="text-xs mt-1 text-gray-400 italic">Belum dispo</div>
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <x-dropdown icon="o-ellipsis-vertical" class="btn-sm btn-ghost">
                                <x-menu-item title="Detail Lengkap" icon="o-eye" />

                                <div class="divider my-1">Ubah Status</div>
                                @if($pengaduan->status !== 'menunggu')
                                <x-menu-item title="Menunggu" icon="o-clock"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'menunggu')" />
                                @endif

                                @if($pengaduan->status !== 'diproses')
                                <!-- Diproses trigger disposisi modal if no petugas -->
                                @if($pengaduan->petugas_id)
                                <x-menu-item title="Diproses" icon="o-arrow-path"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'diproses')" />
                                @else
                                <x-menu-item title="Diproses (Butuh Disposisi)" icon="o-arrow-path" class="text-info"
                                    wire:click="openDisposisi({{ $pengaduan->id }})" />
                                @endif
                                @endif

                                @if($pengaduan->status !== 'selesai')
                                <x-menu-item title="Selesai" icon="o-check" class="text-success"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'selesai')" />
                                @endif

                                @if($pengaduan->status !== 'ditolak')
                                <x-menu-item title="Tolak Laporan" icon="o-x-mark" class="text-error"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'ditolak')" />
                                @endif

                                <div class="divider my-1">Tugas</div>
                                <x-menu-item title="Disposisi Petugas" icon="o-user-plus"
                                    wire:click="openDisposisi({{ $pengaduan->id }})" />
                            </x-dropdown>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Tidak ada data pengaduan yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pengaduans->links() }}
        </div>
    </x-card>

    <!-- Modal Disposisi -->
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