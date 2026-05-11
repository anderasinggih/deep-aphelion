<div class="px-2 py-4 sm:py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">
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
        /* Fix pagination spacing */
        nav[role="navigation"] > div:last-child {
            gap: 1rem;
        }
        /* Force search input alignment */
        input[type="text"] {
            text-align: left !important;
        }
        /* Fix transparent dropdowns - Ultra Specific Override */
        .mary-select-menu, 
        .dropdown-content, 
        .popover-content,
        [role="listbox"],
        .select-content,
        .choices__list--dropdown,
        .slim-select .ss-content,
        .ss-content,
        .mary-choices-menu {
            background-color: white !important;
            background: white !important;
            opacity: 1 !important;
            visibility: visible !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            z-index: 9999 !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        
        /* Ensure the items themselves don't have transparency */
        .mary-select-menu *, 
        .choices__list--dropdown *,
        .mary-choices-menu * {
            --tw-bg-opacity: 1 !important;
        }
    </style>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-3xl font-black text-primary tracking-tight">Manajemen Laporan</h1>
            <p class="text-[10px] sm:text-sm text-base-content/60">Kelola aduan warga Kembaran</p>
        </div>
        <div class="flex items-center gap-1.5 flex-wrap sm:flex-nowrap">
            <x-button label="Tambah" icon="o-plus"
                class="btn-primary rounded-xl btn-xs sm:btn-md flex-1 sm:flex-none shadow-md text-white" 
                link="/pengaduan/create" />
            <x-button label="{{ $bulkMode ? 'Selesai' : 'Pilih' }}" 
                icon="{{ $bulkMode ? 'o-check' : 'o-cursor-arrow-rays' }}"
                class="btn-outline btn-primary rounded-xl btn-xs sm:btn-md shadow-sm {{ $bulkMode ? '!bg-success !text-white !border-success' : '' }} flex-1 sm:flex-none" 
                wire:click="$set('bulkMode', {{ !$bulkMode ? 'true' : 'false' }}); if(!$bulkMode) $set('selectedIds', []);" />
            
            <a href="{{ route('print.laporan', array_filter(['status' => $statusFilter, 'kategori_id' => $kategoriFilter, 'start_date' => $startDate, 'end_date' => $endDate])) }}" 
                target="_blank" class="btn btn-outline btn-primary rounded-xl btn-xs sm:btn-md shadow-sm px-2 flex-1 sm:flex-none">
                <x-icon name="o-printer" class="w-3.5 h-3.5" /> <span class="text-[10px] sm:text-xs font-bold">PDF</span>
            </a>
            <x-button label="Excel" icon="o-document-arrow-down"
                class="btn-outline btn-primary rounded-xl btn-xs sm:btn-md shadow-sm px-2 text-[10px] sm:text-xs font-bold flex-1 sm:flex-none" 
                wire:click="exportExcel" spinner="exportExcel" />
        </div>
    </div>

    @if (session()->has('success'))
        <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl py-2 px-3 text-sm">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session()->has('error'))
        <x-alert icon="o-exclamation-triangle" class="mb-5 shadow-sm alert-error rounded-xl">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- WA Notifikasi Banner --}}
    @if($waLink)
    <div class="mb-5 p-3 rounded-2xl bg-green-50 border border-green-200 flex items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-green-500 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.114 1.52 5.843L.057 23.535a.5.5 0 0 0 .607.607l5.696-1.462A11.935 11.935 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.923 0-3.716-.52-5.253-1.428l-.376-.222-3.904 1.002 1.003-3.776-.244-.389A9.96 9.96 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-green-800 leading-tight">Status Diperbarui: <span class="font-black text-green-900 underline decoration-green-300 underline-offset-2">{{ $updatedKodeTracking }}</span></p>
                <p class="text-[10px] text-green-600 leading-tight">Kirim notifikasi WhatsApp ke pelapor?</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            <a href="{{ $waLink }}" target="_blank"
                class="btn btn-xs bg-green-500 hover:bg-green-600 !text-white border-0 rounded-xl font-bold">
                Kirim
            </a>
            <button wire:click="clearWaLink" class="btn btn-xs btn-ghost rounded-xl">✕</button>
        </div>
    </div>
    @endif

    {{-- Minimalist Search & Filter --}}
    <div class="mb-4 flex flex-col gap-2">
        {{-- Baris Utama: Search + Filter Toggle --}}
        <div class="flex items-center gap-2">
            <div class="flex-1">
                <x-input 
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari laporan..."
                    icon="o-magnifying-glass"
                    clearable
                    class="w-full h-10 sm:h-12 rounded-xl bg-base-100 border-base-300 focus:border-blue-500 focus:ring-blue-500/20 transition-all !text-left text-sm" />
            </div>
            <x-button icon="o-adjustments-horizontal" 
                class="btn-ghost border border-base-300 shadow-sm rounded-xl h-10 w-10 sm:h-12 sm:w-12 hover:bg-base-200 {{ $filtersOpen ? 'bg-base-200' : '' }}"
                wire:click="$toggle('filtersOpen')" />
        </div>

        {{-- Advanced Filters (Collapsible) --}}
        @if($filtersOpen)
        <div id="advanced-filters" class="animate-in fade-in slide-in-from-top-2 duration-200">
            <div class="p-4 bg-base-100 border border-base-200 shadow-xl rounded-2xl">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold px-1 opacity-60 uppercase tracking-wider">Mulai</label>
                        <input type="date" wire:model.live="startDate" class="input input-sm sm:input-md rounded-xl bg-base-100 border border-base-300 w-full" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold px-1 opacity-60 uppercase tracking-wider">Sampai</label>
                        <input type="date" wire:model.live="endDate" class="input input-sm sm:input-md rounded-xl bg-base-100 border border-base-300 w-full" />
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold px-1 opacity-60 uppercase tracking-wider">Kategori</label>
                        <select wire:model.live="kategoriFilter" class="select select-sm sm:select-md rounded-xl bg-base-100 border border-base-300 w-full">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold px-1 opacity-60 uppercase tracking-wider">Status</label>
                        <select wire:model.live="statusFilter" class="select select-sm sm:select-md rounded-xl bg-base-100 border border-base-300 w-full">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold px-1 opacity-60 uppercase tracking-wider">Urutan</label>
                        <select wire:model.live="orderBy" class="select select-sm sm:select-md rounded-xl bg-base-100 border border-base-300 w-full">
                            <option value="smart">🎯 Cerdas (Rekomendasi)</option>
                            <option value="latest">📅 Terbaru</option>
                            <option value="oldest">⏳ Terlama</option>
                            <option value="priority">🚩 Prioritas</option>
                            <option value="upvotes">🔥 Dukungan</option>
                        </select>
                    </div>

                    <div class="flex items-end justify-end md:justify-start pb-1">
                        <x-button label="Reset" icon="o-x-mark" wire:click="resetFilters" 
                            class="btn-ghost btn-sm text-error font-bold rounded-xl hover:bg-error/10" 
                            tooltip="Hapus Semua Filter" />
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="pb-4 border shadow-sm bg-base-100 rounded-2xl border-base-200">

        {{-- Wrapper Tabel --}}
        <div class="overflow-x-auto custom-scrollbar">
            <table class="table w-full table-xs sm:table-sm md:table-md">
                <thead class="bg-base-200/50 text-base-content/60 text-[10px] sm:text-xs">
                    <tr>
                        @if($bulkMode)
                        <th class="w-10 px-2 sm:px-3">
                            <input type="checkbox" wire:click="toggleAll" class="checkbox checkbox-xs" {{ $selectAll ? 'checked' : '' }} />
                        </th>
                        @endif
                        <th class="px-2 sm:px-3 py-3">Laporan</th>
                        <th class="hidden sm:table-cell">Pelapor</th>
                        <th class="hidden lg:table-cell">Kategori</th>
                        <th class="hidden md:table-cell">Tanggal</th>
                        <th class="text-center px-1 sm:px-3">Status</th>
                        <th class="px-2 sm:px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($pengaduans as $pengaduan)
                        <tr class="transition-colors cursor-pointer hover:bg-base-200/50 group {{ in_array($pengaduan->id, $selectedIds) ? 'bg-primary/5' : '' }}" 
                            wire:key="{{ $pengaduan->id }}"
                            wire:click="{{ $bulkMode ? "toggleSelection($pengaduan->id)" : "goToDetail('{$pengaduan->kode_tracking}')" }}">
                            {{-- Checkbox --}}
                            @if($bulkMode)
                            <td class="px-2 sm:px-3" wire:click.stop>
                                <input type="checkbox" wire:model.live="selectedIds" value="{{ $pengaduan->id }}" class="checkbox checkbox-xs" />
                            </td>
                            @endif
                            {{-- Info Utama --}}
                            <td class="px-2 sm:px-3 py-2 text-xs">
                                <div class="flex flex-col">
                                    <span class="text-[11px] sm:text-[13px] font-bold leading-tight text-base-content line-clamp-1 group-hover:text-primary transition-colors"
                                        title="{{ $pengaduan->judul }}">
                                        {{ $pengaduan->judul }}
                                    </span>
                                    <div class="flex flex-col gap-0.5 mt-1 sm:hidden">
                                        <span class="text-[10px] font-medium text-base-content/80 flex items-center gap-1">
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
                                    @if($pengaduan->dukungans_count >= 50)
                                        <div class="mt-1 flex items-center gap-1">
                                            <span class="px-1.5 py-0.5 text-[8px] font-black bg-error text-white rounded shadow-sm uppercase flex items-center gap-1 animate-pulse">
                                                <x-icon name="s-fire" class="w-2.5 h-2.5" /> Mendesak / Suara Rakyat
                                            </span>
                                        </div>
                                    @endif
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
                                <x-dropdown class="dropdown-end sm:dropdown-left {{ $loop->remaining < 3 ? 'dropdown-top' : '' }}">
                                    <x-slot:trigger>
                                        <x-button icon="o-ellipsis-horizontal"
                                            class="text-white rounded-full shadow-sm btn-primary btn-xs hover:scale-105"
                                            tooltip="Aksi Laporan"
                                            onclick="document.querySelectorAll('details').forEach(d => { if(d !== this.closest('details')) d.removeAttribute('open') })" />
                                    </x-slot:trigger>

                                    <div class="my-0.5 opacity-50 divider mt-0 px-2"><span class="text-[9px] font-bold uppercase tracking-tighter">Update Progres</span></div>
                                    
                                    @if($pengaduan->status === 'menunggu')
                                        <x-menu-item title="Mulai Proses" icon="o-arrow-path" class="font-bold text-info !py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'diproses')" />
                                        <x-menu-item title="Tolak Laporan" icon="o-x-circle" class="text-error !py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'ditolak')" />
                                    @endif

                                    @if($pengaduan->status !== 'selesai' && $pengaduan->status !== 'ditolak')
                                        <div class="my-0.5 opacity-30 divider mt-0"></div>
                                        <x-menu-item title="Rujuk (Duplikat)" icon="o-document-duplicate" class="font-bold text-primary !py-1 text-xs"
                                            wire:click="openLinkModal({{ $pengaduan->id }})" />
                                    @endif

                                    @if($pengaduan->status === 'diproses')
                                        <div class="my-0.5 opacity-30 divider mt-0"></div>
                                        <x-menu-item title="Tambah Progres" icon="o-pencil" class="font-bold text-info !py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'diproses')" />
                                        <x-menu-item title="Selesaikan" icon="o-check-circle" class="font-bold text-success !py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'selesai')" />
                                        <x-menu-item title="Batalkan" icon="o-clock" class="!py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'menunggu')" />
                                    @endif

                                    @if($pengaduan->status === 'selesai')
                                        <x-menu-item title="Buka Kembali" icon="o-arrow-path" class="!py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'diproses')" />
                                    @endif

                                    @if($pengaduan->status === 'ditolak')
                                        <x-menu-item title="Pulihkan" icon="o-clock" class="!py-1 text-xs"
                                            wire:click="openUpdateStatusModal({{ $pengaduan->id }}, 'menunggu')" />
                                    @endif

                                    <div class="my-0.5 opacity-30 divider mt-0"></div>
                                    <x-menu-item title="Cetak Resi" icon="o-printer" class="!py-1 text-xs"
                                        link="{{ route('print.resi', $pengaduan->id) }}" external target="_blank" />
                                    
                                    @if($pengaduan->user?->no_wa)
                                    <x-menu-item title="WhatsApp" icon="o-chat-bubble-left-right" class="text-green-600 font-bold !py-1 text-xs"
                                        link="{{ $pengaduan->generateWaLink() }}" external target="_blank" />
                                    @endif

                                    @if(auth()->user()->role === 'superadmin')
                                    <div class="my-0.5 opacity-30 divider mt-0"></div>
                                    <x-menu-item title="Hapus Permanen" icon="o-trash" class="text-error font-black !py-1 text-xs"
                                        wire:click="forceDelete({{ $pengaduan->id }})" wire:confirm="PERINGATAN: Laporan ini akan dihapus permanen beserta seluruh riwayat dan datanya. Tindakan ini tidak dapat dibatalkan. Lanjutkan?" />
                                    @endif
                                </x-dropdown>
                            </td>
                        </tr>
                    @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
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
    </div>

    <div class="mt-6 flex justify-center sm:justify-end">
        <div class="w-full sm:w-auto">
            {{ $pengaduans->links() }}
        </div>
    </div>

    <!-- Modal Update Status -->
    <x-modal wire:model="updateModal" :title="$update_status === 'diproses' && ($selectedPengaduanId ? \App\Models\Pengaduan::find($selectedPengaduanId)->status : '') === 'diproses' ? 'Tambah Update Progres' : 'Perbarui Status Laporan'"
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

    {{-- Bulk Action Floating Bar --}}
    @if(count($selectedIds) > 0)
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 animate-in fade-in slide-in-from-bottom-4 duration-300">
        <div class="bg-base-100 border border-primary/20 shadow-2xl rounded-2xl p-2 pl-4 flex items-center gap-4 backdrop-blur-md bg-opacity-90">
            <div class="flex items-center gap-2">
                <div class="bg-primary text-primary-content text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center">
                    {{ count($selectedIds) }}
                </div>
                <span class="text-xs font-bold text-base-content/80 whitespace-nowrap">Laporan terpilih</span>
            </div>
            <div class="h-6 w-px bg-base-200"></div>
            <div class="flex items-center gap-2 pr-1">
                <x-button label="Rujuk Massal" icon="o-document-duplicate" 
                    class="btn-sm btn-primary text-white rounded-xl font-bold shadow-sm"
                    wire:click="openLinkModal" />
                <x-button label="Kirim WA" icon="o-chat-bubble-left-right" 
                    class="btn-sm bg-green-500 !text-white rounded-xl font-bold shadow-sm border-0 hover:bg-green-600"
                    wire:click="startWaBlast" />
                <x-button icon="o-x-mark" class="btn-sm btn-ghost btn-circle" wire:click="$set('selectedIds', [])" />
            </div>
        </div>
    </div>
    @endif

    {{-- WA Sequential Blast Modal --}}
    <x-modal wire:model="waBlastModal" title="Kirim WhatsApp Massal" subtitle="Proses pengiriman satu per satu agar aman" separator>
        <div class="text-center py-4">
            @if(!empty($waBlastQueue))
                @php $current = $waBlastQueue[$waBlastCurrentIndex]; @endphp
                
                <div class="mb-6">
                    <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-2">PROGRES PENGIRIMAN</div>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-4xl font-black text-base-content leading-none">{{ $waBlastCurrentIndex + 1 }}</span>
                        <span class="text-xl text-base-content/30 font-bold">/ {{ count($waBlastQueue) }}</span>
                    </div>
                </div>

                <div class="p-6 bg-base-200/50 rounded-2xl border border-base-200 mb-8">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                            <x-icon name="o-user" class="w-6 h-6 text-primary" />
                        </div>
                        <div class="text-center">
                            <div class="font-black text-lg text-base-content">{{ $current['nama'] }}</div>
                            <div class="text-[10px] font-black text-base-content/40 uppercase tracking-tighter">{{ $current['kode'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ $current['link'] }}" target="_blank" wire:click="nextWaBlast"
                        class="btn btn-primary bg-green-500 hover:bg-green-600 border-0 !text-white rounded-2xl font-black py-4 h-auto shadow-lg shadow-green-500/20">
                        <x-icon name="o-paper-airplane" class="w-5 h-5 mr-2" />
                        BUKA WHATSAPP & LANJUT
                    </a>
                    
                    <x-button label="Lewati / Selesai" wire:click="nextWaBlast" class="btn-ghost text-base-content/40 font-bold" />
                </div>
            @endif
        </div>
    </x-modal>
</div>