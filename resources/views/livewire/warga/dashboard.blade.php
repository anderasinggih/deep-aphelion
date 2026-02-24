<div>
    <x-header title="Dashboard Warga" subtitle="Pantau laporan pengaduan yang telah Anda buat" size="text-2xl"
        class="mb-5">
        <x-slot:actions>
            <x-button label="Buat Laporan" icon="o-plus" class="btn-primary" link="/pengaduan/create" />
        </x-slot:actions>
    </x-header>

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="alert-success mb-5">
        {{ session('success') }}
    </x-alert>
    @endif

    <x-card class="shadow-sm">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Judul Laporan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $pengaduan)
                    @php /** @var \App\Models\Pengaduan $pengaduan */ @endphp
                    <tr class="hover">
                        <td class="whitespace-nowrap">{{ $pengaduan->created_at->format('d M Y') }}</td>
                        <td>{{ $pengaduan->kategori->nama }}</td>
                        <td>
                            <div class="font-bold line-clamp-1">{{ $pengaduan->judul }}</div>
                        </td>
                        <td>
                            @if($pengaduan->status == 'menunggu')
                            <x-badge value="Menunggu" class="badge-warning" />
                            @elseif($pengaduan->status == 'diproses')
                            <x-badge value="Diproses" class="badge-info" />
                            @elseif($pengaduan->status == 'selesai')
                            <x-badge value="Selesai" class="badge-success" />
                            @elseif($pengaduan->status == 'ditolak')
                            <x-badge value="Ditolak" class="badge-error" />
                            @endif
                        </td>
                        <td>
                            <x-button icon="o-eye" class="btn-sm btn-ghost" tooltip="Detail Riwayat" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            <x-icon name="o-folder-open" class="w-10 h-10 mx-auto mb-2 text-gray-300" />
                            Belum ada laporan yang Anda buat.
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
</div>