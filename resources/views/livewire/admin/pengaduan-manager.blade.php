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
    <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
        {{ session('success') }}
    </x-alert>
    @endif

    @if (session()->has('error'))
    <x-alert icon="o-exclamation-triangle" class="mb-5 shadow-sm alert-error rounded-xl">
        {{ session('error') }}
    </x-alert>
    @endif

    <div class="pb-4 border shadow-sm bg-base-100 rounded-2xl border-base-200">

        {{-- Header Filter (Sebaris di Mobile & Desktop) --}}
        <div class="flex flex-row items-center gap-2 p-3 mb-2 border-b sm:gap-4 sm:p-4 border-base-200 bg-base-100/50">
            {{-- Search (Ambil sisa ruang) --}}
            <div class="flex-1 min-w-0">
                <x-input wire:model.live.debounce="search" icon="o-magnifying-glass" placeholder="Cari Judul / Nama..."
                    class="w-full input-sm sm:input-md" />
            </div>

            {{-- Dropdown Status (Lebar fix di mobile, agak besar di desktop) --}}
            <div class="w-32 sm:w-48 shrink-0">
                <x-select wire:model.live="statusFilter" :options="[
                    ['id' => '', 'name' => 'Semua Status'],
                    ['id' => 'menunggu', 'name' => 'Menunggu'],
                    ['id' => 'diproses', 'name' => 'Diproses'],
                    ['id' => 'selesai', 'name' => 'Selesai'],
                    ['id' => 'ditolak', 'name' => 'Ditolak'],
                ]" option-value="id" option-label="name" class="w-full select-sm sm:select-md" />
            </div>
        </div>

        {{-- Wrapper Tabel (min-h-[350px] agar dropdown MaryUI tidak kepotong) --}}
        <div class="overflow-x-auto min-h-[350px]">
            <table class="table w-full table-xs sm:table-sm md:table-md">
                <thead class="bg-base-200/50 text-base-content/60">
                    <tr>
                        <th class="px-3 py-3">Laporan</th>
                        <th class="hidden sm:table-cell">Pelapor</th>
                        <th class="hidden lg:table-cell">Kategori</th>
                        <th class="hidden md:table-cell">Tanggal</th>
                        <th class="text-center">Status & Petugas</th>
                        <th class="px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($pengaduans as $pengaduan)
                    <tr class="transition-colors hover:bg-base-200/30">
                        {{-- Info Utama (Mobile: Laporan + User + Tanggal gabung) --}}
                        <td class="px-3 py-2">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold leading-tight text-base-content line-clamp-1"
                                    title="{{ $pengaduan->judul }}">
                                    {{ $pengaduan->judul }}
                                </span>
                                {{-- Muncul hanya di Mobile --}}
                                <div class="flex flex-col gap-0.5 mt-1 sm:hidden">
                                    <span class="text-[11px] font-medium text-base-content/80 flex items-center gap-1">
                                        <x-icon name="o-user" class="w-3 h-3" /> {{ $pengaduan->user->name }}
                                    </span>
                                    <span class="text-[10px] text-base-content/50 italic flex items-center gap-1">
                                        <x-icon name="o-clock" class="w-3 h-3" /> {{
                                        $pengaduan->created_at->format('d/m/y') }} • {{ $pengaduan->kategori->nama }}
                                    </span>
                                </div>
                                {{-- Deskripsi muncul di Desktop --}}
                                <div
                                    class="hidden sm:block text-[11px] text-base-content/50 max-w-[200px] truncate mt-0.5">
                                    {{ $pengaduan->deskripsi }}
                                </div>
                            </div>
                        </td>

                        {{-- Pelapor (Desktop Only) --}}
                        <td class="hidden sm:table-cell py-2">
                            <div class="text-[12px] font-bold">{{ $pengaduan->user->name }}</div>
                            <div class="text-[11px] text-base-content/50">{{ $pengaduan->user->nik }}</div>
                        </td>

                        {{-- Kategori (Desktop Large Only) --}}
                        <td class="hidden lg:table-cell text-[12px] text-base-content/70 py-2">
                            {{ $pengaduan->kategori->nama }}
                        </td>

                        {{-- Tanggal (Desktop Only) --}}
                        <td class="hidden md:table-cell whitespace-nowrap text-[12px] text-base-content/70 py-2">
                            {{ $pengaduan->created_at->format('d M Y') }}
                        </td>

                        {{-- Status & Petugas --}}
                        <td class="py-2 text-center">
                            @php
                            $statusMap = [
                            'menunggu' => ['label' => 'Menunggu', 'class' => 'badge-warning'],
                            'diproses' => ['label' => 'Proses', 'class' => 'badge-info'],
                            'selesai' => ['label' => 'Selesai', 'class' => 'badge-success'],
                            'ditolak' => ['label' => 'Ditolak', 'class' => 'badge-error'],
                            ];
                            $curr = $statusMap[$pengaduan->status] ?? ['label' => $pengaduan->status, 'class' => ''];
                            @endphp

                            <span
                                class="badge {{ $curr['class'] }} badge-outline font-bold text-[13px] px-1.5 h-4 sm:h-5">
                                {{ $curr['label'] }}
                            </span>

                            @if($pengaduan->petugas)
                            <div
                                class="text-[9px] sm:text-[10px] mt-1 text-primary flex items-center justify-center gap-0.5 font-medium">
                                <x-icon name="o-user" class="w-3 h-3 shrink-0" />
                                <span class="truncate max-w-[70px]">{{ $pengaduan->petugas->name }}</span>
                            </div>
                            @else
                            <div class="text-[9px] sm:text-[10px] mt-1 text-base-content/40 italic">Belum dispo</div>
                            @endif
                        </td>

                        {{-- Aksi (Menggunakan Mary UI Component) --}}
                        <td class="px-3 py-2 text-right">
                            <x-dropdown class="dropdown-end sm:dropdown-left">
                                <x-slot:trigger>
                                    <x-button icon="o-ellipsis-horizontal"
                                        class="text-white rounded-full shadow-sm btn-primary btn-xs hover:scale-105"
                                        tooltip="Aksi Laporan" />
                                </x-slot:trigger>

                                {{-- Daftar Menu --}}
                                <x-menu-item title="Detail Lengkap" icon="o-eye"
                                    link="{{ route('admin.pengaduan.detail', $pengaduan->id) }}" wire:navigate />

                                <div class="my-1 opacity-50 divider"><span class="text-[10px] font-bold">UBAH
                                        STATUS</span></div>

                                @if($pengaduan->status !== 'menunggu')
                                <x-menu-item title="Set Menunggu" icon="o-clock"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'menunggu')" />
                                @endif

                                @if($pengaduan->status !== 'diproses')
                                @if($pengaduan->petugas_id)
                                <x-menu-item title="Set Diproses" icon="o-arrow-path"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'diproses')" />
                                @else
                                <x-menu-item title="Proses (Dispo)" icon="o-arrow-path" class="font-bold text-info"
                                    wire:click="openDisposisi({{ $pengaduan->id }})" />
                                @endif
                                @endif

                                @if($pengaduan->status !== 'selesai')
                                <x-menu-item title="Selesaikan" icon="o-check-circle" class="font-bold text-success"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'selesai')" />
                                @endif

                                @if($pengaduan->status !== 'ditolak')
                                <x-menu-item title="Tolak Laporan" icon="o-x-circle" class="font-bold text-error"
                                    wire:click="setStatus({{ $pengaduan->id }}, 'ditolak')" />
                                @endif

                                <div class="my-1 opacity-50 divider"><span class="text-[10px] font-bold">TUGAS</span>
                                </div>
                                <x-menu-item title="Atur Disposisi" icon="o-user-plus"
                                    wire:click="openDisposisi({{ $pengaduan->id }})" />
                            </x-dropdown>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 italic text-center text-base-content/50">
                            Tidak ada data pengaduan yang sesuai.
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

    {{-- Modal Disposisi Tetap Sama --}}
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