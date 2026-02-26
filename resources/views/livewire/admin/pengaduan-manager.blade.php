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

    <div class="pb-4 overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
        {{-- Header Filter --}}
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

        {{-- Wrapper Tabel (min-h ditambah agar dropdown tidak tertutup) --}}
        <div class="overflow-x-auto min-h-[100px]">
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
                                class="badge {{ $curr['class'] }} badge-outline font-bold text-[13px] sm:text-[13px]  px-1.5 h-4 sm:h-5">
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

                        {{-- Aksi (Dropdown Native Sistem) --}}
                        <td class="px-3 py-2 text-right">
                            <div class="dropdown dropdown-end sm:dropdown-left">
                                <div tabindex="0" role="button"
                                    class="text-white rounded-full shadow-sm btn btn-primary btn-xs hover:scale-105">
                                    <x-icon name="o-ellipsis-horizontal" class="w-4 h-4" />
                                </div>
                                {{-- Menu Dropdown Bawaan DaisyUI --}}
                                <ul tabindex="0"
                                    class="dropdown-content z-[50] menu p-2 shadow-xl bg-base-100 rounded-2xl w-48 border border-base-200 mt-1 sm:mt-0 sm:mr-1">
                                    <li>
                                        <a href="{{ route('admin.pengaduan.detail', $pengaduan->id) }}" wire:navigate
                                            class="text-xs font-bold py-2">
                                            <x-icon name="o-eye" class="w-4 h-4 opacity-70" /> Detail Lengkap
                                        </a>
                                    </li>

                                    <div class="my-0 opacity-30 divider"></div>
                                    <li class="menu-title text-[9px] py-1 opacity-70">Ubah Status</li>

                                    @if($pengaduan->status !== 'menunggu')
                                    <li><a wire:click="setStatus({{ $pengaduan->id }}, 'menunggu')"
                                            class="py-2 text-xs"><x-icon name="o-clock" class="w-4 h-4 opacity-70" />
                                            Set Menunggu</a></li>
                                    @endif

                                    @if($pengaduan->status !== 'diproses')
                                    @if($pengaduan->petugas_id)
                                    <li><a wire:click="setStatus({{ $pengaduan->id }}, 'diproses')"
                                            class="py-2 text-xs"><x-icon name="o-arrow-path"
                                                class="w-4 h-4 opacity-70" /> Set Diproses</a></li>
                                    @else
                                    <li><a wire:click="openDisposisi({{ $pengaduan->id }})"
                                            class="py-2 text-xs font-bold text-info"><x-icon name="o-arrow-path"
                                                class="w-4 h-4" /> Proses (Dispo)</a></li>
                                    @endif
                                    @endif

                                    @if($pengaduan->status !== 'selesai')
                                    <li><a wire:click="setStatus({{ $pengaduan->id }}, 'selesai')"
                                            class="py-2 text-xs font-bold text-success"><x-icon name="o-check-circle"
                                                class="w-4 h-4" /> Selesaikan</a></li>
                                    @endif

                                    @if($pengaduan->status !== 'ditolak')
                                    <li><a wire:click="setStatus({{ $pengaduan->id }}, 'ditolak')"
                                            class="py-2 text-xs font-bold text-error"><x-icon name="o-x-circle"
                                                class="w-4 h-4" /> Tolak</a></li>
                                    @endif

                                    <div class="my-0 opacity-30 divider"></div>
                                    <li class="menu-title text-[9px] py-1 opacity-70">Tugas</li>
                                    <li><a wire:click="openDisposisi({{ $pengaduan->id }})" class="py-2 text-xs"><x-icon
                                                name="o-user-plus" class="w-4 h-4 opacity-70" /> Atur Disposisi</a></li>
                                </ul>
                            </div>
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