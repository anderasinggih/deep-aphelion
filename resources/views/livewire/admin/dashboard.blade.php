<div class="px-2 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

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




    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 gap-2 mb-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 sm:gap-4 sm:mb-8">
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-primary/50 hover:shadow-md">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-primary/10 text-primary"><x-icon name="o-users" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ $stats['total_warga'] ?? 0 }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Akun Warga</h3>
        </div>
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-info/50 hover:shadow-md">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-info/10 text-info"><x-icon name="o-document-text" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ $stats['total_laporan'] ?? 0 }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Total Aduan</h3>
        </div>
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-warning/50 hover:shadow-md">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-warning/10 text-warning"><x-icon name="o-clock" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ $stats['menunggu'] ?? 0 }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Menunggu</h3>
        </div>
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-info/50 hover:shadow-md">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-info/10 text-info"><x-icon name="o-bolt" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ $stats['diproses'] ?? 0 }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Diproses</h3>
        </div>
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-success/50 hover:shadow-md">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-success/10 text-success"><x-icon name="o-check-badge" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ $stats['selesai'] ?? 0 }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Selesai</h3>
        </div>
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-accent/50 hover:shadow-md">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-accent/10 text-accent"><x-icon name="o-chart-bar" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ $stats['laporan_bulan_ini'] ?? 0 }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Bulan Ini</h3>
        </div>
        <div class="flex flex-col items-center justify-center p-2 text-center transition-all border shadow-sm sm:p-5 bg-base-100 rounded-2xl border-base-200 hover:border-warning/50 hover:shadow-md col-span-2 sm:col-span-1">
            <div class="p-1.5 mb-1.5 rounded-full sm:mb-3 bg-warning/10 text-warning"><x-icon name="o-star" class="w-4 h-4 sm:w-6 sm:h-6" /></div>
            <h2 class="mb-0 text-xl sm:text-3xl font-black text-base-content">{{ number_format($stats['rata_rating'], 1) }}</h2>
            <h3 class="text-[9px] sm:text-xs font-bold text-base-content/80 leading-tight">Nilai Kepuasan</h3>
        </div>

    </div>

    {{-- Row 1: Peta + Grafik Tren --}}
    <div class="grid grid-cols-1 gap-6 lg:gap-8 lg:grid-cols-5 mb-6">

        {{-- Peta Sebaran --}}
        <div class="lg:col-span-3 flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
            <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                <h2 class="text-base font-bold text-base-content">Peta Sebaran Laporan</h2>
                <p class="mt-0.5 text-xs text-base-content/60">Pemetaan koordinat aduan masyarakat</p>
            </div>
            <div wire:ignore id="admin-map" class="w-full h-[300px] sm:h-[380px] z-0"></div>
        </div>

        {{-- Grafik Tren --}}
        <div class="lg:col-span-2 flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
            <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                <h2 class="text-base font-bold text-base-content">Tren Laporan</h2>
                <p class="mt-0.5 text-xs text-base-content/60">6 bulan terakhir</p>
            </div>
            <div class="flex-1 p-4 sm:p-5 flex items-center">
                <canvas id="trenChart" class="w-full"></canvas>
            </div>
        </div>

    </div>

    {{-- Row 2: Laporan Kritis + Laporan Terbaru --}}
    <div class="grid grid-cols-1 gap-6 lg:gap-8 lg:grid-cols-2">

        {{-- Laporan Prioritas Tinggi --}}
        <div class="flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-warning/30">
            <div class="p-4 border-b sm:p-5 border-warning/20 bg-warning/5 flex items-center gap-2">
                <x-icon name="o-exclamation-triangle" class="w-5 h-5 text-warning" />
                <div>
                    <h2 class="text-base font-bold text-warning">Laporan Prioritas Tinggi</h2>
                    <p class="text-xs text-warning/80">Laporan aktif dengan tingkat prioritas tinggi</p>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto max-h-[320px]">
                <ul class="flex flex-col divide-y divide-base-200">
                    @forelse($laporanPrioritasTinggi ?? [] as $lap)
                    <li class="p-4 sm:p-5 hover:bg-warning/5 transition-colors">
                        <a href="{{ route('admin.pengaduan.detail', $lap->kode_tracking) }}" wire:navigate class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold leading-tight truncate text-base-content">{{ $lap->judul }}</h4>
                                <div class="flex items-center gap-2 mt-1.5 text-xs text-base-content/60">
                                    <x-icon name="o-tag" class="w-3 h-3" />
                                    <span>{{ $lap->kategori->nama ?? '-' }}</span>
                                    <span class="text-base-content/30">•</span>
                                    <x-icon name="o-clock" class="w-3 h-3" />
                                    <span>{{ $lap->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="badge badge-error badge-sm font-bold uppercase">{{ $lap->prioritas }}</span>
                                <div class="text-[10px] text-base-content/40 mt-1">{{ ucfirst($lap->status) }}</div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="flex flex-col items-center justify-center p-10 text-center text-base-content/40">
                        <x-icon name="o-check-circle" class="w-10 h-10 text-success/50 mb-2" />
                        <span class="text-sm font-medium text-success/70">Tidak ada laporan prioritas tinggi!</span>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Laporan Terbaru --}}
        <div class="flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
            <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                <h2 class="text-base font-bold text-base-content">5 Laporan Terbaru</h2>
                <p class="mt-0.5 text-xs text-base-content/60">Aduan yang baru masuk ke sistem</p>
            </div>
            <div class="flex-1 overflow-y-auto max-h-[320px]">
                <ul class="flex flex-col divide-y divide-base-200">
                    @forelse($laporanTerbaru ?? [] as $lap)
                    <li class="p-4 transition-colors sm:p-5 hover:bg-base-200/30">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold leading-tight truncate text-base-content">{{ $lap->judul }}</h4>
                                <div class="flex items-center gap-1.5 mt-2 text-xs font-medium text-base-content/60">
                                    <x-icon name="o-user" class="w-3.5 h-3.5 shrink-0" />
                                    <span class="truncate">{{ $lap->user->name ?? 'Anonim' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-base-content/50">
                                    <x-icon name="o-clock" class="w-3.5 h-3.5 shrink-0" />
                                    <span>{{ $lap->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div>
                                @if($lap->status == 'menunggu') <x-badge value="Menunggu" class="badge-warning badge-sm" />
                                @elseif($lap->status == 'diproses') <x-badge value="Diproses" class="badge-info badge-sm" />
                                @elseif($lap->status == 'selesai') <x-badge value="Selesai" class="badge-success badge-sm" />
                                @elseif($lap->status == 'ditolak') <x-badge value="Ditolak" class="badge-error badge-sm" />
                                @endif
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="flex flex-col items-center justify-center p-10 text-center text-base-content/40">
                        <div class="p-4 mb-3 rounded-full bg-base-200/50"><x-icon name="o-inbox" class="w-8 h-8" /></div>
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

    {{-- Row 3: Analisis Kepuasan & Feedback --}}
    <div class="grid grid-cols-1 gap-6 lg:gap-8 lg:grid-cols-5 mt-6 sm:mt-8">
        
        {{-- Distribusi Rating --}}
        <div class="lg:col-span-2 flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
            <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                <h2 class="text-base font-bold text-base-content">Analisis Kepuasan</h2>
                <p class="mt-0.5 text-xs text-base-content/60">Distribusi rating dari warga</p>
            </div>
            <div class="flex-1 p-6 flex flex-col justify-center">
                <div class="space-y-4">
                    @foreach(range(5, 1) as $i)
                        @php
                            $count = $ratingDistribution[$i] ?? 0;
                            $total = array_sum($ratingDistribution);
                            $percent = $total > 0 ? ($count / $total) * 100 : 0;
                            $colorMap = [5 => 'bg-success', 4 => 'bg-success/70', 3 => 'bg-warning', 2 => 'bg-orange-400', 1 => 'bg-error'];
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1 w-12">
                                <span class="text-xs font-black">{{ $i }}</span>
                                <x-icon name="o-star" class="w-3.5 h-3.5 text-warning fill-warning" />
                            </div>
                            <div class="flex-1 h-2.5 bg-base-200 rounded-full overflow-hidden">
                                <div class="h-full {{ $colorMap[$i] }} transition-all duration-1000" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-base-content/50 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 text-center p-4 bg-base-200/30 rounded-xl border border-base-200 border-dashed">
                    <div class="text-3xl font-black text-primary mb-1">{{ number_format($stats['rata_rating'], 1) }} / 5.0</div>
                    <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Rata-rata Kepuasan</p>
                </div>
            </div>
        </div>

        {{-- Feedback Terbaru --}}
        <div class="lg:col-span-3 flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
            <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                <h2 class="text-base font-bold text-base-content">Feedback & Komentar Warga</h2>
                <p class="mt-0.5 text-xs text-base-content/60">Tanggapan terbaru mengenai pelayanan</p>
            </div>
            <div class="flex-1 overflow-y-auto max-h-[400px]">
                <ul class="flex flex-col divide-y divide-base-200">
                    @forelse($recentFeedbacks ?? [] as $fb)
                    <li class="p-4 sm:p-5 transition-colors hover:bg-base-200/30">
                        <div class="flex items-start gap-4">
                            <x-user-avatar :user="$fb->user" size="w-10 h-10" />
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h4 class="text-sm font-bold text-base-content truncate">{{ $fb->user->name ?? 'Anonim' }}</h4>
                                    <span class="text-[10px] text-base-content/40 font-semibold">{{ $fb->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-0.5 mb-2">
                                    @foreach(range(1, 5) as $i)
                                        <x-icon name="o-star" class="w-3 h-3 {{ $i <= $fb->rating ? 'text-warning fill-warning' : 'text-base-300' }}" />
                                    @endforeach
                                </div>
                                <p class="text-xs text-base-content/80 font-medium leading-relaxed line-clamp-2">
                                    "{{ $fb->rating_komentar ?: 'Memberikan rating tanpa komentar.' }}"
                                </p>
                                <a href="{{ route('admin.pengaduan.detail', $fb->kode_tracking) }}" wire:navigate class="mt-2 inline-block text-[10px] font-black text-primary hover:underline uppercase tracking-tighter">
                                    Lihat Laporan: {{ $fb->kode_tracking }}
                                </a>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="flex flex-col items-center justify-center p-12 text-center text-base-content/40">
                        <div class="p-4 mb-3 rounded-full bg-base-200/50"><x-icon name="o-chat-bubble-bottom-center-text" class="w-8 h-8" /></div>
                        <span class="text-sm font-medium">Belum ada feedback dari warga.</span>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    {{-- Row 4: Leaderboard Kategori --}}
    <div class="mt-8 border shadow-sm bg-base-100 rounded-2xl border-base-200 overflow-hidden mb-8">
        <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-base-content">Leaderboard Performa Kategori</h2>
                <p class="mt-0.5 text-xs text-base-content/60">Kategori dengan penyelesaian terbaik & kecepatan respons</p>
            </div>
            <x-icon name="o-trophy" class="w-6 h-6 text-warning" />
        </div>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-base-200/50 text-[9px] uppercase tracking-wider text-base-content/50">
                    <tr>
                        <th class="py-2 px-4 w-16 text-center">Rank</th>
                        <th>Kategori</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Selesai</th>
                        <th class="text-center">Efektivitas</th>
                        <th class="text-right px-4">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @foreach($categoryPerformance as $index => $kat)
                        @php
                            $efektivitas = $kat->total_laporan > 0 ? ($kat->laporan_selesai / $kat->total_laporan) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-base-200/30 transition-colors">
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center mx-auto w-6 h-6 rounded-full font-black text-[10px]
                                    {{ $index == 0 ? 'bg-yellow-400 text-yellow-900 shadow-sm' : '' }}
                                    {{ $index == 1 ? 'bg-gray-300 text-gray-800' : '' }}
                                    {{ $index == 2 ? 'bg-orange-300 text-orange-900' : '' }}
                                    {{ $index > 2 ? 'bg-base-200 text-base-content/60' : '' }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="font-bold text-xs text-base-content leading-tight py-2">{{ $kat->nama }}</td>
                            <td class="text-center font-medium text-xs">{{ $kat->total_laporan }}</td>
                            <td class="text-center font-bold text-xs text-success">{{ $kat->laporan_selesai }}</td>
                            <td class="text-center">
                                <div class="flex flex-col items-center gap-0.5">
                                    <div class="w-16 h-1 bg-base-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary" style="width: {{ $efektivitas }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-bold text-base-content/50">{{ round($efektivitas) }}%</span>
                                </div>
                            </td>
                            <td class="text-right px-4 py-2">
                                <div class="flex flex-col items-end">
                                    <span class="text-xs font-black text-primary">{{ $kat->avg_resolution_time }}h</span>
                                    <span class="text-[8px] text-base-content/40 uppercase font-bold tracking-tighter">Selesai</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    </style>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://leaflet.github.io/Leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endassets

    @script
    <script>
        setTimeout(() => {
            // ===== PETA =====
            const mapData = @json($markers ?? []);
            const map = L.map('admin-map').setView([-7.4245, 109.2882], 13);
            
            // Layer Satelit + Label
            L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20, 
                subdomains:['mt0','mt1','mt2','mt3'], 
                attribution: '&copy; Google Maps'
            }).addTo(map);

            // 1. DATA LAPORAN (Heatmap & Markers)
            if (mapData && mapData.length > 0) {
                const heatPoints = mapData.map(item => [item.latitude, item.longitude, 0.5]);
                L.heatLayer(heatPoints, { radius: 25, blur: 15, maxZoom: 17, gradient: {0.4: 'blue', 0.65: 'lime', 1: 'red'} }).addTo(map);
                
                mapData.forEach(function (item) {
                    if (item.latitude && item.longitude) {
                        const statusLabel = item.status.charAt(0).toUpperCase() + item.status.slice(1);
                        L.marker([item.latitude, item.longitude]).addTo(map)
                            .bindPopup(`<div class="font-sans"><b class="text-sm block mb-1">${item.judul}</b><span class="text-xs text-gray-600">Status: <strong>${statusLabel}</strong></span></div>`);
                    }
                });
            }

            // ===== GRAFIK TREN =====
            const trenData = @json($trenData ?? []);
            const ctx = document.getElementById('trenChart');
            if (ctx && trenData.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: trenData.map(d => d.label),
                        datasets: [{
                            label: 'Laporan Masuk',
                            data: trenData.map(d => d.total),
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }, 100);
    </script>
    @endscript
</div>
