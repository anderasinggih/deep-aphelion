<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Dashboard Admin</h1>
            <p class="mt-1 text-sm text-base-content/70">Pusat Monitoring Pengaduan Kecamatan Kembaran</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button label="Kelola Laporan" icon="o-document" class="shadow-sm btn-outline btn-primary rounded-xl"
                link="/admin/pengaduan" />
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-8 lg:grid-cols-4 sm:gap-6">

        <div
            class="flex flex-col items-center justify-center p-6 text-center transition-all border shadow-sm sm:p-8 bg-base-100 rounded-3xl border-base-200 hover:border-primary/50 hover:shadow-md">
            <div class="p-3 mb-4 rounded-full bg-primary/10 text-primary">
                <x-icon name="o-users" class="w-7 h-7" />
            </div>
            <h2 class="mb-1 text-4xl font-black text-base-content">{{ $stats['total_warga'] ?? 0 }}</h2>
            <h3 class="mb-1 text-base font-bold text-base-content/80">Akun Terdaftar</h3>
            <p class="text-xs text-base-content/50">Jumlah warga di sistem</p>
        </div>

        <div
            class="flex flex-col items-center justify-center p-6 text-center transition-all border shadow-sm sm:p-8 bg-base-100 rounded-3xl border-base-200 hover:border-info/50 hover:shadow-md">
            <div class="p-3 mb-4 rounded-full bg-info/10 text-info">
                <x-icon name="o-document-text" class="w-7 h-7" />
            </div>
            <h2 class="mb-1 text-4xl font-black text-base-content">{{ $stats['total_laporan'] ?? 0 }}</h2>
            <h3 class="mb-1 text-base font-bold text-base-content/80">Total Pengaduan</h3>
            <p class="text-xs text-base-content/50">Seluruh aduan masuk</p>
        </div>

        <div
            class="flex flex-col items-center justify-center p-6 text-center transition-all border shadow-sm sm:p-8 bg-base-100 rounded-3xl border-base-200 hover:border-warning/50 hover:shadow-md">
            <div class="p-3 mb-4 rounded-full bg-warning/10 text-warning">
                <x-icon name="o-bolt" class="w-7 h-7" />
            </div>
            <h2 class="mb-1 text-4xl font-black text-base-content">{{ $stats['diproses'] ?? 0 }}</h2>
            <h3 class="mb-1 text-base font-bold text-base-content/80">Aduan Diproses</h3>
            <p class="text-xs text-base-content/50">Sedang ditangani</p>
        </div>

        <div
            class="flex flex-col items-center justify-center p-6 text-center transition-all border shadow-sm sm:p-8 bg-base-100 rounded-3xl border-base-200 hover:border-success/50 hover:shadow-md">
            <div class="p-3 mb-4 rounded-full bg-success/10 text-success">
                <x-icon name="o-check-badge" class="w-7 h-7" />
            </div>
            <h2 class="mb-1 text-4xl font-black text-base-content">{{ $stats['selesai'] ?? 0 }}</h2>
            <h3 class="mb-1 text-base font-bold text-base-content/80">Aduan Selesai</h3>
            <p class="text-xs text-base-content/50">Laporan yang sudah beres</p>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-6 lg:gap-8 lg:grid-cols-3">

        <div class="flex flex-col h-full lg:col-span-2">
            <div class="flex flex-col flex-1 overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <div class="p-5 border-b sm:p-6 border-base-200 bg-base-100/50">
                    <h2 class="text-lg font-bold text-base-content">Peta Sebaran Laporan</h2>
                    <p class="mt-1 text-sm text-base-content/60">Pemetaan titik koordinat kejadian dari aduan masyarakat
                    </p>
                </div>
                <div wire:ignore id="admin-map" class="w-full h-[350px] sm:h-[450px] lg:h-[500px] z-0"></div>
            </div>
        </div>

        <div class="flex flex-col h-full lg:col-span-1">
            <div class="flex flex-col flex-1 overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <div class="p-5 border-b sm:p-6 border-base-200 bg-base-100/50">
                    <h2 class="text-lg font-bold text-base-content">5 Laporan Terbaru</h2>
                    <p class="mt-1 text-sm text-base-content/60">Feed aduan yang baru saja masuk ke sistem</p>
                </div>

                <div class="flex-1 overflow-y-auto max-h-[400px] lg:max-h-[420px]">
                    <ul class="flex flex-col divide-y divide-base-200">
                        @forelse($laporanTerbaru ?? [] as $lap)
                        <li class="p-4 transition-colors sm:p-5 hover:bg-base-200/30">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold leading-tight truncate text-base-content"
                                        title="{{ $lap->judul }}">
                                        {{ $lap->judul }}
                                    </h4>
                                    <div
                                        class="flex items-center gap-1.5 mt-2 text-xs font-medium text-base-content/60">
                                        <x-icon name="o-user" class="w-3.5 h-3.5 shrink-0" />
                                        <span class="truncate">{{ $lap->user->name ?? 'Anonim' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-1 text-[11px] text-base-content/50">
                                        <x-icon name="o-clock" class="w-3.5 h-3.5 shrink-0" />
                                        <span>{{ $lap->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 pt-0.5">
                                    @if($lap->status == 'menunggu')
                                    <div
                                        class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full bg-warning/20 text-warning">
                                        Menunggu</div>
                                    @elseif($lap->status == 'diproses')
                                    <div
                                        class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full bg-info/20 text-info">
                                        Diproses</div>
                                    @elseif($lap->status == 'selesai')
                                    <div
                                        class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full bg-success/20 text-success">
                                        Selesai</div>
                                    @elseif($lap->status == 'ditolak')
                                    <div
                                        class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full bg-error/20 text-error">
                                        Ditolak</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="flex flex-col items-center justify-center p-10 text-center text-base-content/40">
                            <div class="p-4 mb-3 rounded-full bg-base-200/50">
                                <x-icon name="o-inbox" class="w-8 h-8" />
                            </div>
                            <span class="text-sm font-medium">Belum ada laporan masuk.</span>
                        </li>
                        @endforelse
                    </ul>
                </div>

                <div class="p-4 border-t bg-base-100/50 border-base-200">
                    <x-button label="Lihat Semua Laporan" link="/admin/pengaduan" icon-right="o-arrow-right"
                        class="w-full shadow-sm btn-outline rounded-xl" />
                </div>
            </div>
        </div>

    </div>

    @assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endassets

    @script
    <script>
        // Sedikit delay untuk memastikan DOM peta siap sebelum Leaflet diinisialisasi
        setTimeout(() => {
            const mapData = @json($markers ?? []);

            // Koordinat default (Area Kembaran/Purwokerto)
            const map = L.map('admin-map').setView([-7.4245, 109.2882], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Mapping warna ke status (Untuk referensi jika nanti ingin membuat custom icon)
            const statusColors = {
                'menunggu': 'orange',
                'diproses': 'blue',
                'selesai': 'green',
                'ditolak': 'red'
            };

            // Looping data marker dari backend
            if (mapData && mapData.length > 0) {
                mapData.forEach(function (item) {
                    if (item.latitude && item.longitude) {
                        // Membuat tulisan status menjadi huruf kapital depannya
                        const statusLabel = item.status.charAt(0).toUpperCase() + item.status.slice(1);

                        L.marker([item.latitude, item.longitude])
                            .addTo(map)
                            .bindPopup(`
                                <div class="font-sans">
                                    <b class="text-sm block mb-1">${item.judul}</b>
                                    <span class="text-xs text-gray-600">Status: <strong>${statusLabel}</strong></span>
                                </div>
                            `);
                    }
                });
            }
        }, 100);
    </script>
    @endscript
</div>