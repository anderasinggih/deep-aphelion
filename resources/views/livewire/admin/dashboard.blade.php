<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Dashboard Admin</h1>
            <p class="mt-1 text-sm text-base-content/70">Pusat Monitoring Pengaduan Kecamatan Kembaran</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button label="Kelola Semua Laporan" icon="o-folder" class="shadow-sm btn-outline btn-primary rounded-xl"
                link="/admin/pengaduan" />
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Pendaftar / Users -->
        <div
            class="p-8 border bg-base-100 rounded-3xl border-base-300/60 flex flex-col items-center justify-center text-center transition-all hover:border-primary/50">
            <div class="p-3 mb-4 rounded-full bg-primary/20 text-primary">
                <x-icon name="o-users" class="w-6 h-6" />
            </div>
            <h2 class="text-4xl font-black text-white mb-2">{{ $stats['total_warga'] ?? 0 }}</h2>
            <h3 class="text-base font-bold text-white mb-1">Akun Terdaftar</h3>
            <p class="text-xs text-base-content/50">Jumlah akun warga terdaftar di sistem</p>
        </div>

        <!-- Total Laporan -->
        <div
            class="p-8 border bg-base-100 rounded-3xl border-base-300/60 flex flex-col items-center justify-center text-center transition-all hover:border-success/50">
            <div class="p-3 mb-4 rounded-full bg-success/20 text-success">
                <x-icon name="o-document-check" class="w-6 h-6" />
            </div>
            <h2 class="text-4xl font-black text-white mb-2">{{ $stats['total_laporan'] ?? 0 }}</h2>
            <h3 class="text-base font-bold text-white mb-1">Total Pengaduan</h3>
            <p class="text-xs text-base-content/50">Seluruh aduan masuk se-Kecamatan</p>
        </div>

        <!-- Laporan Diproses/Berlangsung -->
        <div
            class="p-8 border bg-base-100 rounded-3xl border-base-300/60 flex flex-col items-center justify-center text-center transition-all hover:border-warning/50">
            <div class="p-3 mb-4 rounded-full bg-warning/20 text-warning">
                <x-icon name="o-bolt" class="w-6 h-6" />
            </div>
            <h2 class="text-4xl font-black text-white mb-2">{{ $stats['diproses'] ?? 0 }}</h2>
            <h3 class="text-base font-bold text-white mb-1">Aduan Berlangsung</h3>
            <p class="text-xs text-base-content/50">Laporan yang sedang ditangani saat ini</p>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        <!-- Peta Sebaran Laporan -->
        <div class="lg:col-span-2">
            <div class="h-full border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <div class="p-6 border-b border-base-200">
                    <h2 class="text-lg font-bold text-base-content">Peta Sebaran Laporan</h2>
                    <p class="text-sm text-base-content/60">Pemetaan kordinat kejadian dari aduan masyarakat</p>
                </div>
                <!-- Pastikan wire:ignore jika render ulang Livewire -->
                <div wire:ignore id="admin-map" class="w-full h-[400px] sm:h-[450px] rounded-b-2xl z-0"></div>
            </div>
        </div>

        <!-- Laporan Terbaru Masuk -->
        <div class="lg:col-span-1">
            <div class="flex flex-col h-full border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <div class="p-6 border-b border-base-200">
                    <h2 class="text-lg font-bold text-base-content">5 Tiket Terbaru Masuk</h2>
                    <p class="text-sm text-base-content/60">Feed aduan masyarakat di sistem</p>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <ul class="flex flex-col divide-y divide-base-200">
                        @forelse($laporanTerbaru as $lap)
                        <li class="p-5 transition-colors hover:bg-base-200/30">
                            <div class="flex items-start justify-between">
                                <div class="pr-2">
                                    <h4 class="text-sm font-bold leading-tight line-clamp-2 text-base-content">{{
                                        $lap->judul }}</h4>
                                    <div class="flex items-center gap-2 mt-2 text-xs font-medium text-base-content/60">
                                        <x-icon name="o-user" class="w-3.5 h-3.5" />
                                        <span>{{ $lap->user->name }}</span>
                                    </div>
                                    <div class="mt-1 text-xs text-base-content/50">{{ $lap->created_at->diffForHumans()
                                        }}</div>
                                </div>
                                <div class="mt-1">
                                    @if($lap->status == 'menunggu')
                                    <x-badge class="font-bold badge-warning badge-sm badge-outline" />
                                    @elseif($lap->status == 'diproses')
                                    <x-badge class="font-bold badge-info badge-sm badge-outline" />
                                    @elseif($lap->status == 'selesai')
                                    <x-badge class="font-bold badge-success badge-sm badge-outline" />
                                    @elseif($lap->status == 'ditolak')
                                    <x-badge class="font-bold badge-error badge-sm badge-outline" />
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="p-8 text-center text-base-content/50">
                            <x-icon name="o-document-check" class="w-8 h-8 mx-auto mb-2 opacity-50" />
                            <span class="text-sm">Belum ada laporan masuk.</span>
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="p-4 border-t border-base-200">
                    <x-button label="Lihat Semua Laporan" link="/admin/pengaduan"
                        class="w-full shadow-sm btn-outline rounded-xl" />
                </div>
            </div>
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
                        .bindPopup(`<b>${item.judul}</b><br/>Status: ${item.status.toUpperCase(;
                }
            });
        });
    </script>
    @endscript
</div>