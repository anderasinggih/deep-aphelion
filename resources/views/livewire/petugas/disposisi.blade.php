<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">

    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Tugas Disposisi</h1>
            <p class="mt-1 text-sm text-base-content/70">Daftar laporan warga yang ditugaskan kepada Anda</p>
        </div>
    </div>

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
        {{ session('success') }}
    </x-alert>
    @endif

    {{-- Header Filter --}}
    <div class="flex flex-row items-center gap-2 p-1 mb-5 border-b sm:gap-4 sm:p-1 border-base-200 bg-base-100/50">
        <div class="flex-1 min-w-0">
            <x-input wire:model.live.debounce="search" icon="o-magnifying-glass"
                placeholder="Cari Judul / Lokasi Tugas..." class="w-full input-sm sm:input-md" />
        </div>
    </div>

    <div class="pb-4 border shadow-sm bg-base-100 rounded-2xl border-base-200">
        <div class="overflow-x-auto min-h-[350px]">
            <table class="table w-full table-xs sm:table-sm md:table-md">
                <thead class="bg-base-200/50 text-base-content/60">
                    <tr>
                        <th class="px-3 py-3">Laporan & Lokasi</th>
                        <th class="hidden sm:table-cell">Kategori</th>
                        <th class="hidden md:table-cell">Tgl Lapor</th>
                        <th class="text-center">Status Laporan</th>
                        <th class="px-3 text-right">Aksi Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($pengaduans as $pengaduan)
                    <tr class="transition-colors hover:bg-base-200/30">
                        {{-- Info Utama --}}
                        <td class="px-3 py-2">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold leading-tight text-base-content line-clamp-1"
                                    title="{{ $pengaduan->judul }}">
                                    {{ $pengaduan->judul }}
                                </span>
                                {{-- Mobile Details --}}
                                <div class="flex flex-col gap-0.5 mt-1 sm:hidden">
                                    <span class="text-[11px] font-medium text-base-content/80 flex items-center gap-1">
                                        <x-icon name="o-map-pin" class="w-3 h-3" /> <span
                                            class="truncate max-w-[150px]">{{ $pengaduan->lokasi_kejadian }}</span>
                                    </span>
                                    <span class="text-[10px] text-base-content/50 italic flex items-center gap-1">
                                        <x-icon name="o-clock" class="w-3 h-3" /> {{
                                        $pengaduan->created_at->format('d/m/y') }} • {{ $pengaduan->kategori->nama }}
                                    </span>
                                </div>
                                {{-- Desktop Description/Location --}}
                                <div class="hidden sm:flex flex-col gap-0.5 mt-0.5">
                                    <span
                                        class="text-[11px] text-base-content/50 flex items-center gap-1 truncate max-w-[250px]"
                                        title="{{ $pengaduan->lokasi_kejadian }}">
                                        <x-icon name="o-map-pin" class="w-3 h-3" /> {{ $pengaduan->lokasi_kejadian }}
                                    </span>
                                </div>

                                {{-- Lihat Foto Before --}}
                                @if($pengaduan->foto_bukti)
                                <a href="{{ Storage::url($pengaduan->foto_bukti) }}" target="_blank"
                                    class="mt-1.5 text-[10px] sm:text-[11px] font-bold text-primary hover:text-primary-focus flex items-center gap-1 w-fit transition-colors">
                                    <x-icon name="o-photo" class="w-3.5 h-3.5" /> Lihat Foto Keluhan
                                </a>
                                @else
                                <div
                                    class="mt-1.5 text-[10px] sm:text-[11px] text-base-content/40 italic flex items-center gap-1">
                                    <x-icon name="o-no-symbol" class="w-3.5 h-3.5" /> Tanpa Lampiran
                                </div>
                                @endif
                            </div>
                        </td>

                        {{-- Kategori (Tablet/Desktop) --}}
                        <td class="hidden sm:table-cell text-[12px] text-base-content/70 py-2 font-semibold">
                            {{ $pengaduan->kategori->nama }}
                        </td>

                        {{-- Tgl Lapor (Desktop Large) --}}
                        <td class="hidden md:table-cell whitespace-nowrap text-[12px] text-base-content/70 py-2">
                            {{ $pengaduan->created_at->format('d M Y') }}
                        </td>

                        {{-- Status Laporan --}}
                        <td class="py-2 text-center">
                            @php
                            $statusMap = [
                            'menunggu' => ['label' => 'Menunggu Anda', 'class' => 'badge-warning'],
                            'diproses' => ['label' => 'Sedang Diproses', 'class' => 'badge-info'],
                            'selesai' => ['label' => 'Telah Selesai', 'class' => 'badge-success'],
                            ];
                            $curr = $statusMap[$pengaduan->status] ?? ['label' => $pengaduan->status, 'class' => ''];
                            @endphp

                            <span
                                class="badge {{ $curr['class'] }} badge-outline font-bold text-[13px] sm:text-[12px] px-1.5 h-4 sm:h-5 whitespace-nowrap">
                                {{ $curr['label'] }}
                            </span>
                        </td>

                        {{-- Aksi (Menggunakan Mary UI Component) --}}
                        <td class="px-3 py-2 text-right">
                            @if($pengaduan->status == 'menunggu')
                            <x-button label="Mulai Proses" icon="o-play"
                                class="btn-xs sm:btn-sm btn-info text-white rounded-xl shadow-sm"
                                wire:click="processReport({{ $pengaduan->id }})" />
                            @elseif($pengaduan->status == 'diproses')
                            <x-button label="Selesaikan" icon="o-check-badge"
                                class="btn-xs sm:btn-sm btn-success text-white rounded-xl shadow-sm"
                                wire:click="openSelesaiModal({{ $pengaduan->id }})" />
                            @elseif($pengaduan->status == 'selesai')
                            <x-button label="Ditutup" icon="o-lock-closed"
                                class="btn-xs sm:btn-sm btn-disabled rounded-xl" />
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 italic text-center text-base-content/50">
                            <x-icon name="o-face-smile" class="w-10 h-10 mx-auto mb-2 opacity-30" />
                            Yeay! Belum ada tugas disposisi baru untuk Anda.
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

    <!-- Modal Bukti Selesai (After Photo) -->
    <x-modal wire:model="selesaiModal" title="Selesaikan Tindak Lanjut Laporan"
        subtitle="Unggah foto 'After' penyelesaian sebagai bukti transparan untuk Warga.">
        <x-form wire:submit="markSelesai">

            <x-file label="Foto Bukti Penyelesaian (After / Selesai)" wire:model="foto_bukti_selesai" accept="image/*"
                required hint="Wajib menyertakan foto hasil pekerjaan lapangan." />

            @if ($foto_bukti_selesai)
            <div class="mt-2 text-center border border-dashed rounded-lg p-2 bg-base-100">
                <span class="text-sm font-semibold text-gray-500 block">Preview Foto:</span>
                <img src="{{ $foto_bukti_selesai->temporaryUrl() }}"
                    class="rounded shadow w-48 mx-auto mt-1 border border-base-300">
            </div>
            @endif

            <x-textarea label="Jelaskan Tindak Lanjut" wire:model="keterangan_selesai"
                placeholder="Catat detail apa yang telah diperbaiki..." rows="3" required minlength="10" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.selesaiModal = false" class="btn-ghost" />
                <x-button label="Submit Bukti Selesai" type="submit" icon="o-check-circle"
                    class="btn-success text-white" spinner="markSelesai" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>