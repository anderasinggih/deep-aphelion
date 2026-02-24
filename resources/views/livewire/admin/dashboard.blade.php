<div class="max-w-7xl mx-auto py-8 px-4">
    <x-header title="Dashboard Admin" subtitle="Pusat Monitoring Pengaduan Kecamatan Kembaran" size="text-2xl"
        class="mb-5" />

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <x-stat title="Total Laporan" value="{{ $stats['total_laporan'] }}" icon="o-document-text"
            class="bg-base-100 shadow-sm" />
        <x-stat title="Menunggu" value="{{ $stats['menunggu'] }}" icon="o-clock"
            class="bg-base-100 shadow-sm text-warning" />
        <x-stat title="Diproses" value="{{ $stats['diproses'] }}" icon="o-arrow-path"
            class="bg-base-100 shadow-sm text-info" />
        <x-stat title="Selesai" value="{{ $stats['selesai'] }}" icon="o-check-circle"
            class="bg-base-100 shadow-sm text-success" />
        <x-stat title="Total Warga" value="{{ $stats['total_warga'] }}" icon="o-users" class="bg-base-100 shadow-sm" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Peta Sebaran Laporan -->
        <div class="lg:col-span-2">
            <x-card title="Peta Sebaran Laporan (Heatmap/Marker)" class="shadow-sm h-[500px]">
                <!-- Pastikan wire:ignore jika render ulang Livewire -->
                <div wire:ignore id="admin-map" class="w-full h-[400px] rounded-lg z-0"></div>
            </x-card>
        </div>

        <!-- Laporan Terbaru Masuk -->
        <div class="lg:col-span-1">
            <x-card title="5 Laporan Masuk Terbaru" class="shadow-sm h-full overflow-y-auto">
                <ul class="space-y-4">
                    @forelse($laporanTerbaru as $lap)
                    <li class="border-b border-base-200 pb-3 last:border-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm font-bold truncate pr-3">{{ $lap->judul }}</div>
                                <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <x-icon name="o-user" class="w-3" />
                                    <!-- Admin selalu bisa lihat identitas Asli dari warganya -->
                                    {{ $lap->user->name }} ({{ $lap->user->nik }})
                                </div>
                                <div class="text-xs text-gray-400 mt-1">{{ $lap->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="mt-1">
                                @if($lap->status == 'menunggu')
                                <x-badge class="badge-warning badge-sm" />
                                @elseif($lap->status == 'diproses')
                                <x-badge class="badge-info badge-sm" />
                                @elseif($lap->status == 'selesai')
                                <x-badge class="badge-success badge-sm" />
                                @endif
                            </div>
                        </div>
                    </li>
                    @empty
                    <div class="text-center text-gray-500 py-10">Belum ada laporan masuk.</div>
                    @endforelse
                </ul>

                <x-slot:actions>
                    <x-button label="Lihat Semua Laporan" link="/admin/pengaduan"
                        class="btn-outline w-full mt-2 btn-sm" />
                </x-slot:actions>
            </x-card>
        </div>
    </div>

    <!-- Leaflet Setup -->
    @assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endassets

    @script
    <script>
        document.addEventListener('livewire:initialized', () => {
            const mapData = @json($markers);

            // Lokasi default peta: Kecamatan Kembaran (Contoh latitude & longitude Purwokerto/Kembaran)
            const map = L.map('admin-map').setView([-7.4245, 109.2882], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const iconColors = {
                'menunggu': 'orange',
                'diproses': 'blue',
                'selesai': 'green',
                'ditolak': 'red'
            };

            // Pasang marker dari data aduan
            mapData.forEach(function (item) {
                if (item.latitude && item.longitude) {
                    // Custom pin color based on status bisa dilakukan dengan L.divIcon atau Custom Marker
                    // Sementara pakai default leaflet marker dengan popup status text
                    L.marker([item.latitude, item.longitude])
                        .addTo(map)
                        .bindPopup(`<b>${item.judul}</b><br/>Status: ${item.status.toUpperCase()}`);
                }
            });
        });
    </script>
    @endscript
</div>