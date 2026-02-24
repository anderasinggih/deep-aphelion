<div class="max-w-7xl mx-auto py-8 text-black px-4">
    <x-header title="Tugas Disposisi" subtitle="Daftar laporan warga yang ditugaskan kepada Anda" size="text-2xl"
        class="mb-5" />

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="alert-success mb-5">
        {{ session('success') }}
    </x-alert>
    @endif

    <x-card class="shadow-sm">

        <!-- Filter Bar -->
        <div class="mb-6 max-w-md">
            <x-input wire:model.live.debounce="search" icon="o-magnifying-glass"
                placeholder="Cari Judul / Lokasi Tugas..." />
        </div>

        <!-- Data Laporan assigned to Petugas -->
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Tgl Lapor</th>
                        <th>Kategori & Lokasi</th>
                        <th>Judul Laporan & Foto Detail</th>
                        <th>Status</th>
                        <th class="text-right">Aksi Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $pengaduan)
                    <tr class="hover">
                        <td class="whitespace-nowrap">{{ $pengaduan->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="font-bold">{{ $pengaduan->kategori->nama }}</div>
                            <div class="text-xs text-gray-500 max-w-[150px] truncate"
                                title="{{ $pengaduan->lokasi_kejadian }}">
                                <x-icon name="o-map-pin" class="w-3" /> {{ $pengaduan->lokasi_kejadian }}
                            </div>
                        </td>
                        <td>
                            <div class="font-bold line-clamp-1" title="{{ $pengaduan->judul }}">{{ $pengaduan->judul }}
                            </div>
                            @if($pengaduan->foto_bukti)
                            <div class="mt-1 text-xs text-primary flex items-center gap-1 cursor-pointer">
                                <x-icon name="o-photo" class="w-4 h-4" /> Lihat Foto Before Laporan
                            </div>
                            @else
                            <div class="mt-1 text-xs text-gray-400 italic">Tidak ada foto attachment dari warga</div>
                            @endif
                        </td>
                        <td>
                            @if($pengaduan->status == 'menunggu')
                            <x-badge value="Menunggu Anda" class="badge-warning badge-sm" />
                            @elseif($pengaduan->status == 'diproses')
                            <x-badge value="Sedang Anda Proses" class="badge-info badge-sm" />
                            @elseif($pengaduan->status == 'selesai')
                            <x-badge value="Telah Diselesaikan" class="badge-success badge-sm" />
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            @if($pengaduan->status == 'menunggu')
                            <x-button label="Mulai Proses" icon="o-play" class="btn-sm btn-info"
                                wire:click="processReport({{ $pengaduan->id }})" />
                            @elseif($pengaduan->status == 'diproses')
                            <x-button label="Selesaikan Tugas" icon="o-check-badge"
                                class="btn-sm btn-success text-white"
                                wire:click="openSelesaiModal({{ $pengaduan->id }})" />
                            @elseif($pengaduan->status == 'selesai')
                            <x-button label="Ditutup" class="btn-sm btn-disabled" />
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            <x-icon name="o-face-smile" class="w-10 h-10 mx-auto mb-2 text-gray-300" />
                            Yeay! Belum ada tugas disposisi baru untuk Anda.
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