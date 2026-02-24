<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Dashboard Warga</h1>
            <p class="mt-1 text-sm text-base-content/70">Pantau laporan pengaduan yang telah Anda buat</p>
        </div>
        <div>
            <x-button label="Buat Laporan Baru" icon="o-plus" class="shadow-sm btn-primary rounded-xl"
                link="/pengaduan/create" />
        </div>
    </div>

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
        {{ session('success') }}
    </x-alert>
    @endif

    <div class="border shadow-sm bg-base-100 rounded-2xl border-base-200">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <!-- head -->
                <thead class="bg-base-200/50 text-base-content/60">
                    <tr>
                        <th class="rounded-tl-lg">Tanggal</th>
                        <th>Kategori</th>
                        <th>Judul Laporan</th>
                        <th>Status</th>
                        <th class="text-right rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($pengaduans as $pengaduan)
                    @php /** @var \App\Models\Pengaduan $pengaduan */ @endphp
                    <tr class="transition-colors hover:bg-base-200/30">
                        <td class="text-sm whitespace-nowrap text-base-content/80">{{ $pengaduan->created_at->format('d
                            M Y') }}</td>
                        <td class="text-sm text-base-content/80">
                            <span class="flex items-center gap-1">
                                <x-icon name="o-folder" class="w-3.5 h-3.5" />
                                {{ $pengaduan->kategori->nama }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm font-bold leading-tight line-clamp-1 text-base-content">{{
                                $pengaduan->judul }}</div>
                        </td>
                        <td>
                            @if($pengaduan->status == 'menunggu')
                            <x-badge value="Menunggu" class="font-bold badge-warning badge-outline" />
                            @elseif($pengaduan->status == 'diproses')
                            <x-badge value="Diproses" class="font-bold badge-info badge-outline" />
                            @elseif($pengaduan->status == 'selesai')
                            <x-badge value="Selesai" class="font-bold badge-success badge-outline" />
                            @elseif($pengaduan->status == 'ditolak')
                            <x-badge value="Ditolak" class="font-bold badge-error badge-outline" />
                            @endif
                        </td>
                        <td class="text-right">
                            <x-button icon="o-eye" class="rounded-lg btn-sm btn-ghost text-primary hover:bg-primary/10"
                                tooltip="Lihat Detail Riwayat" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center border-b border-base-200 text-base-content/50">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-3 mb-3 rounded-full bg-base-200">
                                    <x-icon name="o-folder-open" class="w-8 h-8 opacity-50 text-base-content/50" />
                                </div>
                                <span class="text-sm font-medium">Belum ada laporan yang Anda buat.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-base-200">
            {{ $pengaduans->links() }}
        </div>
    </div>
</div>