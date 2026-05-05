<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                            <span class="badge {{ $curr['class'] }} font-bold text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0">
                                {{ $curr['label'] }}
                            </span>
                        </td>

                        {{-- Aksi (Icon saja di mobile) --}}
                        <td class="text-right py-2 px-3">
                            <div class="flex items-center justify-end gap-0.5 sm:gap-1">
                                <x-button icon="o-eye" class="btn-ghost btn-xs text-info hover:bg-info/10"
                                    link="{{ route('pengaduan.feed-detail', $pengaduan->kode_tracking) }}" />
                                    
                                <a href="{{ route('print.resi', $pengaduan->id) }}" target="_blank" class="btn btn-ghost btn-xs text-primary hover:bg-primary/10" title="Cetak Tanda Terima">
                                    <x-icon name="o-printer" class="w-4 h-4" />
                                </a>

                                @if($pengaduan->status === 'menunggu')
                                <x-button icon="o-pencil-square"
                                    class="btn-ghost btn-xs text-warning hover:bg-warning/10"
                                    link="{{ route('pengaduan.edit', $pengaduan->kode_tracking) }}" />

                                <x-button icon="o-trash" class="btn-ghost btn-xs text-error hover:bg-error/10"
                                    wire:click="deletePengaduan({{ $pengaduan->id }})"
                                    wire:confirm="Hapus laporan ini?" />
                                @endif

                                @if($pengaduan->status === 'selesai' && !$pengaduan->rating)
                                <x-button icon="o-star" class="btn-ghost btn-xs text-warning hover:bg-warning/10"
                                    wire:click="openRatingModal({{ $pengaduan->id }})" tooltip="Beri Penilaian" />
                                @endif
                                
                                @if($pengaduan->status === 'selesai' && $pengaduan->rating)
                                <div class="px-2 py-0.5 rounded bg-warning/10 border border-warning/20" title="Penilaian Anda">
                                    <span class="flex items-center gap-0.5 text-[10px] font-bold text-warning">
                                        <x-icon name="s-star" class="w-3 h-3" /> {{ $pengaduan->rating }}
                                    </span>
                                </div>
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

    {{-- Modal Rating IKM --}}
    <x-modal wire:model="ratingModal" title="Penilaian Layanan" separator>
        <div class="flex flex-col gap-4">
            <p class="text-sm text-base-content/70">Seberapa puas Anda dengan penanganan laporan ini?</p>
            
            <div class="flex justify-center gap-2 my-2">
                @foreach(range(1, 5) as $i)
                <button type="button" wire:click="$set('rating_value', {{ $i }})" 
                    class="transition-transform transform hover:scale-110">
                    <x-icon name="s-star" class="w-10 h-10 {{ $rating_value >= $i ? 'text-warning' : 'text-base-300' }}" />
                </button>
                @endforeach
            </div>
            <div class="text-center text-xs font-bold text-warning mb-2">
                {{ $rating_value }} Bintang
            </div>

            <x-textarea wire:model="rating_komentar" label="Ulasan (Opsional)" 
                placeholder="Bagaimana pelayanan dari kecamatan?" rows="3" />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.ratingModal = false" />
            <x-button label="Kirim Penilaian" wire:click="saveRating" class="btn-primary" />
        </x-slot:actions>
    </x-modal>