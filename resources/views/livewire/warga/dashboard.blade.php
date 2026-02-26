<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Dashboard Warga</h1>
            <p class="mt-1 text-sm text-base-content/70">Pantau laporan pengaduan yang telah Anda buat</p>
        </div>
        <div>
            <x-button label="Buat Pengaduan" icon="o-plus" class="shadow-sm btn-primary rounded-xl"
                link="{{ route('pengaduan.create') }}" />
        </div>
    </div>

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
        {{ session('success') }}
    </x-alert>
    @endif

    <div class="overflow-hidden border shadow-sm bg-base-100 pb-4 rounded-2xl border-base-200">
        <div class="overflow-x-auto">
            {{-- Pakai table-xs untuk padding super tipis di mobile --}}
            <table class="table w-full table-xs sm:table-sm md:table-md">
                <thead class="bg-base-200/50 text-base-content/60">
                    <tr>
                        <th class="py-3 px-3">Laporan</th>
                        <th class="hidden sm:table-cell">Kategori</th>
                        <th class="hidden md:table-cell">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-right px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($pengaduans as $pengaduan)
                    <tr class="transition-colors hover:bg-base-200/30">
                        {{-- Info Utama (Mobile: Muncul Judul + Sub-info) --}}
                        <td class="py-2 px-3">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold leading-tight text-base-content line-clamp-1">
                                    {{ $pengaduan->judul }}
                                </span>
                                <div
                                    class="flex items-center gap-1.5 mt-0.5 text-[10px] sm:hidden text-base-content/50 italic">
                                    <span>{{ $pengaduan->created_at->format('d/m/y') }}</span>
                                    <span>•</span>
                                    <span class="truncate max-w-[70px]">{{ $pengaduan->kategori->nama }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori (Desktop Only) --}}
                        <td class="hidden sm:table-cell text-[12px] text-base-content/70">
                            {{ $pengaduan->kategori->nama }}
                        </td>

                        {{-- Tanggal (Desktop Only) --}}
                        <td class="hidden md:table-cell text-[12px] whitespace-nowrap text-base-content/70">
                            {{ $pengaduan->created_at->format('d M Y') }}
                        </td>

                        {{-- Status (Dikecilkan ukurannya) --}}
                        <td class="text-center py-2">
                            @php
                            $statusMap = [
                            'menunggu' => ['label' => 'Menunggu', 'class' => 'badge-warning'],
                            'diproses' => ['label' => 'Proses', 'class' => 'badge-info'],
                            'selesai' => ['label' => 'Selesai', 'class' => 'badge-success'],
                            'ditolak' => ['label' => 'Ditolak', 'class' => 'badge-error'],
                            ];
                            $curr = $statusMap[$pengaduan->status] ?? ['label' => $pengaduan->status, 'class' => ''];
                            @endphp
                            <span class="badge {{ $curr['class'] }} badge-outline font-bold text-[13px]  px-1.5 h-4">
                                {{ $curr['label'] }}
                            </span>
                        </td>

                        {{-- Aksi (Icon saja di mobile) --}}
                        <td class="text-right py-2 px-3">
                            <div class="flex items-center justify-end gap-0.5 sm:gap-1">
                                <x-button icon="o-eye" class="btn-ghost btn-xs text-primary hover:bg-primary/10"
                                    link="{{ route('pengaduan.feed-detail', $pengaduan->id) }}" />

                                @if($pengaduan->status === 'menunggu')
                                <x-button icon="o-pencil-square"
                                    class="btn-ghost btn-xs text-warning hover:bg-warning/10"
                                    link="{{ route('pengaduan.edit', $pengaduan->id) }}" />

                                <x-button icon="o-trash" class="btn-ghost btn-xs text-error hover:bg-error/10"
                                    wire:click="deletePengaduan({{ $pengaduan->id }})"
                                    wire:confirm="Hapus laporan ini?" />
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-base-content/50">
                            <span class="text-xs italic">Belum ada laporan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-4 border-t border-base-200">
        {{ $pengaduans->links() }}
    </div>