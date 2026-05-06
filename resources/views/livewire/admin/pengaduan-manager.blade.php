<div class="px-2 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(var(--p), 0.2);
            border-radius: 10px;
        }
    </style>

    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Manajemen Pengaduan</h1>
            <p class="mt-1 text-sm text-base-content/70">Data seluruh laporan warga Kecamatan Kembaran</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 mt-2 sm:mt-0">
            <x-button label="Tambah Pengaduan" icon="o-plus-circle" class="shadow-sm btn-primary rounded-xl"
                link="/pengaduan/create" />
            <a href="{{ route('print.laporan', array_filter(['status' => $statusFilter, 'kategori_id' => $kategoriFilter, 'start_date' => $startDate, 'end_date' => $endDate])) }}" target="_blank" class="btn btn-outline btn-success shadow-sm rounded-xl">
                <x-icon name="o-printer" class="w-4 h-4" /> Cetak PDF
            </a>
            <x-button label="Export CSV" icon="o-document-arrow-down"
                class="shadow-sm btn-outline btn-primary rounded-xl" wire:click="exportCsv" spinner="exportCsv" />
        </div>
    </div>

    @if (session()->has('success'))
        <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session()->has('error'))
        <x-alert icon="o-exclamation-triangle" class="mb-5 shadow-sm alert-error rounded-xl">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Header Filter --}}
    <div class="p-4 mb-6 border shadow-sm bg-base-100 sm:rounded-2xl rounded-xl border-base-200">
        <div class="grid grid-cols-1 md:flex md:flex-row items-center gap-3">
            {{-- Search --}}
            <div class="flex-1 w-full min-w-0">
                <x-input wire:model.live.debounce="search" icon="o-magnifying-glass" placeholder="Cari Judul / Kode Tracking..."
                    class="w-full input-sm sm:input-md" />
            </div>

            <div class="grid grid-cols-2 sm:flex sm:flex-row gap-2 w-full md:w-auto">
                {{-- Date Filters --}}
                <div class="flex items-center gap-1 col-span-2 sm:col-span-1">
                    <x-input type="date" wire:model.live="startDate" class="w-full sm:w-32 input-sm" />
                    <span class="text-xs opacity-50">-</span>
                    <x-input type="date" wire:model.live="endDate" class="w-full sm:w-32 input-sm" />
                </div>

                {{-- Sort --}}
                <x-select wire:model.live="orderBy" :options="[
                    ['id' => 'latest', 'name' => '📅 Terbaru'],
                    ['id' => 'oldest', 'name' => '⏳ Terlama'],
                    ['id' => 'priority', 'name' => '🚩 Prioritas'],
                    ['id' => 'upvotes', 'name' => '🔥 Dukungan'],
                ]" option-value="id" option-label="name"
                    class="select-sm w-full" />

                {{-- Dropdown Kategori --}}
                <x-select wire:model.live="kategoriFilter" :options="$kategoris" option-value="id" option-label="nama"
                    placeholder="Kategori" class="select-sm w-full" />

                {{-- Dropdown Status --}}
                <x-select wire:model.live="statusFilter" :options="[
                    ['id' => '', 'name' => 'Status'],
                    ['id' => 'menunggu', 'name' => 'Menunggu'],
                    ['id' => 'diproses', 'name' => 'Diproses'],
                    ['id' => 'selesai', 'name' => 'Selesai'],
                    ['id' => 'ditolak', 'name' => 'Ditolak'],
                ]" option-value="id" option-label="name"
                    class="select-sm w-full" />
            </div>
        </div>
    </div>

    <div class="pb-4 border shadow-sm bg-base-100 rounded-2xl border-base-200">

        {{-- Wrapper Tabel --}}
        <div class="overflow-x-auto min-h-[350px] custom-scrollbar">
            <table class="table w-full table-xs sm:table-sm md:table-md">
                <thead class="bg-base-200/50 text-base-content/60">
                    <tr>
                        <th class="px-3 py-3">Laporan</th>
                        <th class="hidden sm:table-cell">Pelapor</th>
                        <th class="hidden lg:table-cell">Kategori</th>
                        <th class="hidden md:table-cell">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($pengaduans as $pengaduan)
                                    <tr class="transition-colors cursor-pointer hover:bg-base-200/50 group" 
                                        wire:click="goToDetail('{{ $pengaduan->kode_tracking }}')">
                                        {{-- Info Utama --}}
                                        <td class="px-3 py-2">
                                            <div class="flex flex-col">
                                                <span class="text-[13px] font-bold leading-tight text-base-content line-clamp-1 group-hover:text-primary transition-colors"
                                                    title="{{ $pengaduan->judul }}">
                                                    {{ $pengaduan->judul }}
                                                </span>
                                                <div class="flex flex-col gap-0.5 mt-1 sm:hidden">
                                                    <span class="text-[11px] font-medium text-base-content/80 flex items-center gap-1">
                                                        <x-icon name="o-user" class="w-3 h-3" /> {{ $pengaduan->user?->name ?? 'User Terhapus' }}
                                                    </span>
                                                    <span class="text-[10px] text-base-content/50 italic flex items-center gap-1">
                                                        <x-icon name="o-clock" class="w-3 h-3" /> {{
                                                        $pengaduan->created_at->format('d/m/y') }} • {{ $pengaduan->kategori?->nama ?? 'Kategori Terhapus' }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="hidden sm:block text-[11px] text-base-content/50 max-w-[200px] truncate mt-0.5">
                                                    {{ $pengaduan->deskripsi }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Pelapor --}}
                                        <td class="hidden sm:table-cell py-2">
                                            <div class="text-[12px] font-bold">{{ $pengaduan->user?->name ?? 'User Terhapus' }}</div>
                                            <div class="text-[11px] text-base-content/50">{{ $pengaduan->user?->nik ?? '-' }}</div>
                                        </td>

                                        {{-- Kategori --}}
                                        <td class="hidden lg:table-cell text-[12px] text-base-content/70 py-2">
                                            {{ $pengaduan->kategori?->nama ?? 'N/A' }}
                                        </td>

                                        {{-- Tanggal --}}
                                        <td class="hidden md:table-cell whitespace-nowrap text-[12px] text-base-content/70 py-2">
                                            {{ $pengaduan->created_at->isoFormat('D MMM YYYY') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-2 text-center">
                                            @php
                                                $statusMap = [
                                                    'menunggu' => ['label' => 'Menunggu', 'class' => 'badge-warning'],
                                                    'diproses' => ['label' => 'Diproses', 'class' => 'badge-info'],
                                                    'selesai' => ['label' => 'Selesai', 'class' => 'badge-success'],
                                                    'ditolak' => ['label' => 'Ditolak', 'class' => 'badge-error'],
                                                ];
                                                $curr = $statusMap[$pengaduan->status] ?? ['label' => $pengaduan->status, 'class' => ''];
                                            @endphp

                                            <span class="badge {{ $curr['class'] }} font-bold text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0">
                                                {{ $curr['label'] }}
                                            </span>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-3 py-2 text-right" wire:click.stop>
                                            <x-dropdown class="dropdown-end sm:dropdown-left">
                                                <x-slot:trigger>
                                                    <x-button icon="o-ellipsis-horizontal"
                                                        class="text-white rounded-full shadow-sm btn-primary btn-xs hover:scale-105"
                                                        tooltip="Aksi Laporan"
                                                        onclick="document.querySelectorAll('details').forEach(d => { if(d !== this.closest('details')) d.removeAttribute('open') })" />
                                                </x-slot:trigger>

                                                <div class="my-1 opacity-50 divider mt-0"><span class="text-[10px] font-bold">Update Progres</span></div>
                                                
                                                @if($pengaduan->status === 'menunggu')
                                                    <x-menu-item title="Mulai Proses" icon="o-arrow-path" class="font-bold text-info"
                                                        wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'diproses')" />
                                                    <x-menu-item title="Tolak Laporan" icon="o-x-circle" class="text-error"
                                                        wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'ditolak')" />
                                                @endif

                                                @if($pengaduan->status !== 'selesai' && $pengaduan->status !== 'ditolak')
                                                    <div class="my-1 opacity-50 divider mt-0"></div>
                                                    <x-menu-item title="Rujuk Laporan (Duplikat)" icon="o-document-duplicate" class="font-bold text-primary"
                                                        wire:click="openLinkModal({{ $pengaduan->id }})" />
                                                @endif

                                                @if($pengaduan->status === 'diproses')
                                                    <div class="my-1 opacity-50 divider mt-0"></div>
                                                    <x-menu-item title="Selesaikan" icon="o-check-circle" class="font-bold text-success"
                                                        wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'selesai')" />
                                                    <x-menu-item title="Batalkan (Ke Menunggu)" icon="o-clock"
                                                        wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'menunggu')" />
                                                @endif

                                                @if($pengaduan->status === 'selesai')
                                                    <x-menu-item title="Buka Kembali (Ke Proses)" icon="o-arrow-path"
                                                        wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'diproses')" />
                                                @endif

                                                @if($pengaduan->status === 'ditolak')
                                                    <x-menu-item title="Pulihkan (Ke Menunggu)" icon="o-clock"
                                                        wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'menunggu')" />
                                                @endif
                                            </x-dropdown>
                                        </td>
                                    </tr>
                    @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-14 h-14 rounded-2xl bg-base-200 flex items-center justify-center mb-1">
                                            <x-icon name="{{ $search || $statusFilter ? 'o-magnifying-glass' : 'o-inbox' }}" class="w-7 h-7 text-base-content/30" />
                                        </div>
                                        <p class="text-sm font-bold text-base-content/50">
                                            {{ $search || $statusFilter ? 'Tidak ada hasil ditemukan' : 'Belum ada laporan masuk' }}
                                        </p>
                                        <p class="text-xs text-base-content/30">
                                            {{ $search || $statusFilter ? 'Coba ubah kata kunci atau filter status.' : 'Laporan dari warga akan muncul di sini.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-base-200 bg-base-100/50">
            {{ $pengaduans->links() }}
        </div>
    </div>

    <!-- Modal Update Status -->
    <x-modal wire:model="updateModal" title="Perbarui Status Laporan"
        subtitle="Tambahkan catatan dan foto dokumentasi (opsional) untuk update ini.">
        <x-form wire:submit="saveStatusUpdate">

            <x-file label="Foto Dokumentasi (Opsional)" wire:model="update_foto" accept="image/*"
                :required="$update_status === 'selesai'" :hint="$update_status === 'selesai' ? 'Wajib menyertakan foto hasil pekerjaan untuk status Selesai.' : 'Lampirkan foto pendukung bila ada.'" />

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