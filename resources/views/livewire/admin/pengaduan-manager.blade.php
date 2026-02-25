<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">

    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Manajemen Pengaduan</h1>
            <p class="mt-1 text-sm text-base-content/70">Data seluruh laporan warga Kecamatan Kembaran</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button label="Export Data" icon="o-document-arrow-down"
                class="shadow-sm btn-outline btn-primary rounded-xl" link="/admin/pengaduan" />
        </div>
    </div>


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

        {{-- PERBAIKAN: Cukup pakai overflow-x-auto dengan min-h-[400px] --}}
        <div class="overflow-x-auto min-h-[400px]">
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
                            <div class="font-bold line-clamp-1" title="{{ $pengaduan->judul }}">{{
                                $pengaduan->judul }}
                            </div>
                            <div class="text-xs text-gray-400 max-w-[200px] truncate"
                                title="{{ $pengaduan->deskripsi }}">{{ $pengaduan->deskripsi }}</div>
                        </td>
                        <td>
                            <div>
                                @if($pengaduan->status == 'menunggu')
                                <x-badge value="Menunggu" class="badge-warning badge-sm font-bold" />
                                @elseif($pengaduan->status == 'diproses')
                                <x-badge value="Diproses" class="badge-info badge-sm font-bold" />
                                @elseif($pengaduan->status == 'selesai')
                                <x-badge value="Selesai" class="badge-success badge-sm font-bold" />
                                @elseif($pengaduan->status == 'ditolak')
                                <x-badge value="Ditolak" class="badge-error badge-sm font-bold" />
                                @endif
                            </div>
                            @if($pengaduan->petugas)
                            <div class="text-xs mt-1 text-primary flex items-center gap-1 font-medium">
                                <x-icon name="o-user" class="w-3 h-3" />
                                {{ $pengaduan->petugas->name }}
                            </div>
                            @else
                            <div class="text-xs mt-1 text-gray-400 italic">Belum dispo</div>
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            {{-- Dropdown Keren yang Kembali --}}
                            <x-dropdown class="dropdown-left dropdown-end">
                                <x-slot:trigger>
                                    <x-button icon="o-ellipsis-horizontal"
                                        class="btn-primary btn-sm rounded-full shadow-md text-white hover:scale-110 transition-all"
                                        tooltip="Aksi" />
                                </x-slot:trigger>

                                <x-menu-item title="Detail Lengkap" icon="o-eye"
                                    link="{{ route('admin.pengaduan.detail', $pengaduan->id) }}" wire:navigate />

                                <div class="divider my-1 text-[10px] uppercase font-bold opacity-50">Ubah Status</div>

                                @if($pengaduan->status !== 'menunggu')
                                <x-menu-item title="Set Menunggu" icon="o-clock"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'menunggu')" />
                                @endif

                                @if($pengaduan->status !== 'diproses')
                                @if($pengaduan->petugas_id)
                                <x-menu-item title="Set Diproses" icon="o-arrow-path"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'diproses')" />
                                @else
                                <x-menu-item title="Proses (Disposisi)" icon="o-arrow-path" class="text-info font-bold"
                                    wire:click="openDisposisi({{ $pengaduan->id }})" />
                                @endif
                                @endif

                                @if($pengaduan->status !== 'selesai')
                                <x-menu-item title="Selesaikan" icon="o-check-circle" class="text-success font-bold"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'selesai')" />
                                @endif

                                @if($pengaduan->status !== 'ditolak')
                                <x-menu-item title="Tolak Laporan" icon="o-x-circle" class="text-error font-bold"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'ditolak')" />
                                @endif

                                <div class="divider my-1 text-[10px] uppercase font-bold opacity-50">Tugas</div>
                                <x-menu-item title="Atur Disposisi" icon="o-user-plus"
                                    wire:click="openDisposisi({{ $pengaduan->id }})" />
                            </x-dropdown>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500 italic">
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