<div class="px-2 py-4 sm:py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

    <div class="flex flex-col gap-3 mb-6 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl sm:text-3xl font-black tracking-tight text-primary">Dashboard Admin</h1>
            <p class="mt-0.5 text-[10px] sm:text-sm text-base-content/70 uppercase font-bold tracking-tighter sm:normal-case sm:font-normal">Monitoring Pengaduan Kembaran</p>
        </div>
        <div class="flex items-center gap-2">
            <x-button label="Laporan Eksekutif" icon="o-document-chart-bar" class="shadow-sm btn-primary btn-sm sm:btn-md rounded-xl w-full sm:w-auto text-xs font-bold"
                link="/admin/laporan" />
        </div>
    </div>




    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 gap-2 mb-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 sm:gap-4 sm:mb-8">
        @php
            $cardClasses = "flex flex-col items-center justify-center p-2 sm:p-5 text-center transition-all border shadow-sm bg-base-100 rounded-2xl border-base-200 hover:border-primary/50 hover:shadow-md min-h-[85px] sm:min-h-[140px]";
            $numClasses = "mb-0 text-lg sm:text-3xl lg:text-4xl font-black text-base-content leading-none tracking-tighter";
            $labelClasses = "text-[9px] sm:text-xs font-bold text-base-content/60 leading-tight mt-1 uppercase tracking-wider";
            $iconWrapper = "w-7 h-7 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1.5 sm:mb-2";
        @endphp

        <div class="{{ $cardClasses }}">
            <div class="{{ $iconWrapper }} bg-primary/10 text-primary"><x-icon name="o-users" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ $this->formatNumber($stats['total_warga'] ?? 0) }}</h2>
            <h3 class="{{ $labelClasses }}">Warga</h3>
        </div>

        <div class="{{ $cardClasses }} hover:border-info/50">
            <div class="{{ $iconWrapper }} bg-info/10 text-info"><x-icon name="o-document-text" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ $this->formatNumber($stats['total_laporan'] ?? 0) }}</h2>
            <h3 class="{{ $labelClasses }}">Aduan</h3>
        </div>

        <div class="{{ $cardClasses }} hover:border-warning/50">
            <div class="{{ $iconWrapper }} bg-warning/10 text-warning"><x-icon name="o-clock" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ $this->formatNumber($stats['menunggu'] ?? 0) }}</h2>
            <h3 class="{{ $labelClasses }}">Antre</h3>
        </div>

        <div class="{{ $cardClasses }} hover:border-info/50">
            <div class="{{ $iconWrapper }} bg-info/10 text-info"><x-icon name="o-bolt" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ $this->formatNumber($stats['diproses'] ?? 0) }}</h2>
            <h3 class="{{ $labelClasses }}">Proses</h3>
        </div>

        <div class="{{ $cardClasses }} hover:border-success/50">
            <div class="{{ $iconWrapper }} bg-success/10 text-success"><x-icon name="o-check-badge" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ $this->formatNumber($stats['selesai'] ?? 0) }}</h2>
            <h3 class="{{ $labelClasses }}">Selesai</h3>
        </div>

        <div class="{{ $cardClasses }} hover:border-accent/50">
            <div class="{{ $iconWrapper }} bg-accent/10 text-accent"><x-icon name="o-chart-bar" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ $this->formatNumber($stats['laporan_bulan_ini'] ?? 0) }}</h2>
            <h3 class="{{ $labelClasses }}">Bulan Ini</h3>
        </div>

        <div class="{{ $cardClasses }} hover:border-warning/50 col-span-2 sm:col-span-1">
            <div class="{{ $iconWrapper }} bg-warning/10 text-warning"><x-icon name="o-star" class="w-3.5 h-3.5 sm:w-6 sm:h-6" /></div>
            <h2 class="{{ $numClasses }}">{{ number_format($stats['rata_rating'], 1) }}</h2>
            <h3 class="{{ $labelClasses }}">Rating</h3>
        </div>
    </div>

    {{-- Row 1: Peta + Grafik Tren --}}
    <div class="grid grid-cols-1 gap-6 lg:gap-8 lg:grid-cols-5 mb-6">
        {{-- Peta Sebaran --}}
        <div class="{{ $isMapExpanded ? 'lg:col-span-5' : 'lg:col-span-3' }} flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200 transition-all duration-500">
            <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-lg text-primary hidden sm:block">
                        <x-icon name="o-map" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-base-content">Peta Sebaran Laporan</h2>
                        <p class="mt-0.5 text-xs text-base-content/60">Pemetaan koordinat aduan masyarakat</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Map Filter --}}
                    <div class="flex items-center gap-1 bg-base-200 p-1 rounded-xl">
                        @foreach(['all' => 'Semua', 'menunggu' => 'Menunggu', 'diproses' => 'Proses', 'selesai' => 'Selesai'] as $val => $label)
                            <button 
                                wire:click="$set('map_status', '{{ $val }}')"
                                class="px-2.5 py-1.5 text-[9px] font-black uppercase tracking-tighter rounded-lg transition-all {{ $map_status === $val ? 'bg-primary text-white shadow-sm' : 'hover:bg-base-300 text-base-content/60' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    <button wire:click="toggleMapExpand" 
                            class="hidden lg:flex btn btn-sm btn-ghost border border-base-300 rounded-xl px-3 hover:bg-base-200 transition-all">
                        <x-icon name="{{ $isMapExpanded ? 'o-arrows-pointing-in' : 'o-arrows-pointing-out' }}" class="w-4 h-4" />
                        <span class="hidden sm:inline text-[10px] font-black uppercase tracking-widest">{{ $isMapExpanded ? 'Kecilkan' : 'Perlebar' }}</span>
                    </button>
                </div>
            </div>
            <div wire:ignore id="admin-map" class="w-full {{ $isMapExpanded ? 'h-[500px] sm:h-[700px]' : 'h-[350px] sm:h-[450px]' }} z-0 transition-all duration-500"></div>
            {{-- Map Legend --}}
            <div class="p-3 bg-base-100 border-t border-base-200 flex flex-wrap gap-4 justify-center">
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-base-content/60">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 border border-yellow-600/20"></span> Menunggu
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-base-content/60">
                    <span class="w-3 h-3 rounded-full bg-blue-500 border border-blue-700/20"></span> Diproses
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-base-content/60">
                    <span class="w-3 h-3 rounded-full bg-green-500 border border-green-700/20"></span> Selesai
                </div>
            </div>
        </div>

        @if(!$isMapExpanded)
            {{-- Insights: Tren 7 Hari & Top Desa --}}
            <div class="lg:col-span-2 space-y-6 lg:space-y-8 animate-in slide-in-from-right duration-500">
                {{-- Tren 7 Hari --}}
                <div wire:ignore class="flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
                    <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                        <h2 class="text-base font-bold text-base-content">Aktivitas 7 Hari Terakhir</h2>
                        <p class="mt-0.5 text-xs text-base-content/60">Volume laporan harian</p>
                    </div>
                    <div class="p-4 h-[200px] flex items-center">
                        <canvas id="sevenDayChart"></canvas>
                    </div>
                </div>

                {{-- Top Desa --}}
                <div wire:ignore class="flex flex-col overflow-hidden border shadow-sm bg-base-100 rounded-2xl border-base-200">
                    <div class="p-4 border-b sm:p-5 border-base-200 bg-base-100/50">
                        <h2 class="text-base font-bold text-base-content">Sebaran Laporan per Desa</h2>
                        <p class="mt-0.5 text-xs text-base-content/60">5 Desa dengan laporan terbanyak</p>
                    </div>
                    <div class="p-4 h-[200px] flex items-center">
                        <canvas id="villageChart"></canvas>
                    </div>
                </div>
            </div>
        @endif
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
                    <li class="p-2.5 sm:p-5 hover:bg-warning/5 transition-colors">
                        <a href="{{ route('admin.pengaduan.detail', $lap->kode_tracking) }}" wire:navigate class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="text-[9px] font-black text-primary uppercase tracking-tighter">{{ $lap->kode_tracking }}</span>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold leading-tight truncate text-base-content">{{ $lap->judul }}</h4>
                                <div class="flex items-center gap-2 mt-1 text-[10px] sm:text-xs text-base-content/60">
                                    <x-icon name="o-tag" class="w-3 h-3" />
                                    <span>{{ $lap->kategori->nama ?? '-' }}</span>
                                    <span class="text-base-content/30">•</span>
                                    <x-icon name="o-clock" class="w-3 h-3" />
                                    <span>{{ $lap->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="badge badge-error badge-xs sm:badge-sm font-bold uppercase text-[9px] sm:text-[10px]">{{ $lap->prioritas }}</span>
                                <div class="text-[8px] sm:text-[10px] text-base-content/40 mt-0.5">{{ ucfirst($lap->status) }}</div>
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
                    <li class="p-2.5 transition-colors sm:p-5 hover:bg-base-200/30">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold leading-tight truncate text-base-content">{{ $lap->judul }}</h4>
                                <div class="flex items-center gap-1.5 mt-1 sm:mt-2 text-[10px] sm:text-xs font-medium text-base-content/60">
                                    <x-icon name="o-user" class="w-3 h-3 sm:w-3.5 sm:h-3.5 shrink-0" />
                                    <span class="truncate">{{ $lap->user->name ?? 'Anonim' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5 sm:mt-1 text-[9px] sm:text-[11px] text-base-content/50">
                                    <x-icon name="o-clock" class="w-3 h-3 sm:w-3.5 sm:h-3.5 shrink-0" />
                                    <span>{{ $lap->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0">
                                @if($lap->status == 'menunggu') <x-badge value="Menunggu" class="badge-warning badge-xs sm:badge-sm" />
                                @elseif($lap->status == 'diproses') <x-badge value="Diproses" class="badge-info badge-xs sm:badge-sm" />
                                @elseif($lap->status == 'selesai') <x-badge value="Selesai" class="badge-success badge-xs sm:badge-sm" />
                                @elseif($lap->status == 'ditolak') <x-badge value="Ditolak" class="badge-error badge-xs sm:badge-sm" />
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
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="p-3 bg-base-200/30 rounded-xl border border-base-200">
                        <p class="text-[9px] font-black text-base-content/40 uppercase mb-2 tracking-widest">Prosedur</p>
                        <div class="flex items-center gap-1 mb-1">
                            @foreach(range(1, 5) as $i)
                                <x-icon name="o-star" class="w-3 h-3 {{ $i <= round($stats['rata_pelayanan']) ? 'text-success fill-success' : 'text-base-300' }}" />
                            @endforeach
                        </div>
                        <div class="text-base font-black text-base-content">{{ number_format($stats['rata_pelayanan'], 1, ',', '.') }}</div>
                    </div>
                    <div class="p-3 bg-base-200/30 rounded-xl border border-base-200">
                        <p class="text-[9px] font-black text-base-content/40 uppercase mb-2 tracking-widest">Kecepatan</p>
                        <div class="flex items-center gap-1 mb-1">
                            @foreach(range(1, 5) as $i)
                                <x-icon name="o-star" class="w-3 h-3 {{ $i <= round($stats['rata_respon']) ? 'text-info fill-info' : 'text-base-300' }}" />
                            @endforeach
                        </div>
                        <div class="text-base font-black text-base-content">{{ number_format($stats['rata_respon'], 1, ',', '.') }}</div>
                    </div>
                    <div class="p-3 bg-base-200/30 rounded-xl border border-base-200">
                        <p class="text-[9px] font-black text-base-content/40 uppercase mb-2 tracking-widest">Kompetensi</p>
                        <div class="flex items-center gap-1 mb-1">
                            @foreach(range(1, 5) as $i)
                                <x-icon name="o-star" class="w-3 h-3 {{ $i <= round($stats['rata_kompetensi']) ? 'text-warning fill-warning' : 'text-base-300' }}" />
                            @endforeach
                        </div>
                        <div class="text-base font-black text-base-content">{{ number_format($stats['rata_kompetensi'], 1, ',', '.') }}</div>
                    </div>
                    <div class="p-3 bg-base-200/30 rounded-xl border border-base-200">
                        <p class="text-[9px] font-black text-base-content/40 uppercase mb-2 tracking-widest">Fasilitas</p>
                        <div class="flex items-center gap-1 mb-1">
                            @foreach(range(1, 5) as $i)
                                <x-icon name="o-star" class="w-3 h-3 {{ $i <= round($stats['rata_fasilitas']) ? 'text-error fill-error' : 'text-base-300' }}" />
                            @endforeach
                        </div>
                        <div class="text-base font-black text-base-content">{{ number_format($stats['rata_fasilitas'], 1, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-4 text-center p-4 bg-primary/5 rounded-xl border border-primary/20">
                    <div class="text-3xl font-black text-primary mb-1">{{ number_format($stats['rata_rating'], 1, ',', '.') }} / 5.0</div>
                    <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">Kepuasan Keseluruhan</p>
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
                <thead class="bg-base-200/50 text-[8px] sm:text-[9px] uppercase tracking-wider text-base-content/50">
                    <tr>
                        <th class="py-2 px-2 sm:px-4 w-12 sm:w-16 text-center">Rank</th>
                        <th class="px-2">Kategori</th>
                        <th class="text-center px-1">Total</th>
                        <th class="text-center px-1">Selesai</th>
                        <th class="text-center hidden sm:table-cell">Efektivitas</th>
                        <th class="text-right px-2 sm:px-4">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @foreach($categoryPerformance as $index => $kat)
                        @php
                            $efektivitas = $kat->total_laporan > 0 ? ($kat->laporan_selesai / $kat->total_laporan) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-base-200/30 transition-colors">
                            <td class="px-2 py-1.5">
                                <div class="flex items-center justify-center mx-auto w-5 h-5 sm:w-6 sm:h-6 rounded-full font-black text-[9px] sm:text-[10px]
                                    {{ $index == 0 ? 'bg-yellow-400 text-yellow-900 shadow-sm' : '' }}
                                    {{ $index == 1 ? 'bg-gray-300 text-gray-800' : '' }}
                                    {{ $index == 2 ? 'bg-orange-300 text-orange-900' : '' }}
                                    {{ $index > 2 ? 'bg-base-200 text-base-content/60' : '' }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="font-bold text-[10px] sm:text-xs text-base-content leading-tight py-1.5 px-2">{{ $kat->nama }}</td>
                            <td class="text-center font-medium text-[10px] sm:text-xs px-1">{{ $this->formatNumber($kat->total_laporan) }}</td>
                            <td class="text-center font-bold text-[10px] sm:text-xs text-success px-1">{{ $this->formatNumber($kat->laporan_selesai) }}</td>
                            <td class="text-center hidden sm:table-cell">
                                <div class="flex flex-col items-center gap-0.5">
                                    <div class="w-16 h-1 bg-base-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary" style="width: {{ $efektivitas }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-bold text-base-content/50">{{ round($efektivitas) }}%</span>
                                </div>
                            </td>
                            <td class="text-right px-2 sm:px-4 py-1.5">
                                <div class="flex flex-col items-end">
                                    <span class="text-[10px] sm:text-xs font-black text-primary">{{ $kat->avg_resolution_time }}h</span>
                                    <span class="text-[7px] sm:text-[8px] text-base-content/40 uppercase font-bold tracking-tighter">Selesai</span>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://leaflet.github.io/Leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endassets

    @script
    <script>
        let map;
        let markerLayer = L.layerGroup();
        let heatLayer;

        const updateMarkers = (data) => {
            if (!map) return;
            markerLayer.clearLayers();
            if (heatLayer) map.removeLayer(heatLayer);

            if (!data || data.length === 0) return;

            // Heatmap logic
            const heatPoints = data.map(item => [item.latitude, item.longitude, 0.5]);
            heatLayer = L.heatLayer(heatPoints, { radius: 25, blur: 15, maxZoom: 17, gradient: {0.4: 'blue', 0.65: 'lime', 1: 'red'} }).addTo(map);

            data.forEach(item => {
                if (item.latitude && item.longitude) {
                    const statusLabel = item.status.charAt(0).toUpperCase() + item.status.slice(1);
                    
                    // Color Logic
                    let color = '#fbbf24'; // Yellow (menunggu)
                    if(item.status === 'diproses') color = '#3b82f6'; // Blue
                    if(item.status === 'selesai') color = '#22c55e'; // Green

                    const marker = L.circleMarker([item.latitude, item.longitude], {
                        radius: 8,
                        fillColor: color,
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    });

                    marker.on('click', (e) => {
                        map.flyTo(e.latlng, 17, {
                            duration: 1.5,
                            easeLinearity: 0.25
                        });
                    });

                    const popupContent = `
                        <div class="p-1 min-w-[150px]">
                            <h4 class="font-black text-xs mb-1 text-slate-800 leading-tight">${item.judul}</h4>
                            <div class="flex items-center gap-1.5 mb-3">
                                <span class="w-2 h-2 rounded-full" style="background: ${color}"></span>
                                <span class="text-[9px] font-black uppercase tracking-tighter text-slate-500">${statusLabel}</span>
                            </div>
                            <a href="/admin/pengaduan?search=${item.kode_tracking}" 
                               class="btn btn-primary btn-xs w-full rounded-lg shadow-sm font-black text-[9px] uppercase tracking-tighter">
                                Kelola Laporan
                            </a>
                        </div>
                    `;

                    marker.bindPopup(popupContent).addTo(markerLayer);
                }
            });
        };

        const initMap = () => {
            const mapContainer = document.getElementById('admin-map');
            if (!mapContainer || map) return;

            const mapData = @json($markers ?? []);
            map = L.map('admin-map').setView([-7.4245, 109.2882], 13);
            
            L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20, 
                subdomains:['mt0','mt1','mt2','mt3'], 
                attribution: '&copy; Google Maps'
            }).addTo(map);

            markerLayer.addTo(map);
            updateMarkers(mapData);
        };

        // Watch for Livewire updates
        $wire.on('map-updated', (data) => {
            updateMarkers(data[0]);
        });

        $wire.on('map-resized', () => {
            setTimeout(() => {
                if (map) map.invalidateSize();
                // Re-init charts if they were destroyed by conditional
                initCharts();
            }, 550); // Wait for transition animation to finish
        });

        // Grafik Tren & Analisis
        const initCharts = () => {
            const ctxSevenDay = document.getElementById('sevenDayChart');
            const ctxVillage = document.getElementById('villageChart');
            
            const sevenDayData = @json($sevenDaysTrend ?? []);
            const villageData = @json($topVillages ?? []);

            // 1. Chart 7 Hari (Line Chart)
            if (ctxSevenDay && sevenDayData.length > 0) {
                new Chart(ctxSevenDay, {
                    type: 'line',
                    data: {
                        labels: sevenDayData.map(d => d.label),
                        datasets: [{
                            label: 'Laporan',
                            data: sevenDayData.map(d => d.total),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#4f46e5'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                            x: { ticks: { font: { size: 10 } }, grid: { display: false } }
                        }
                    }
                });
            }

            // 2. Chart Desa (Doughnut)
            if (ctxVillage && Object.keys(villageData).length > 0) {
                new Chart(ctxVillage, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(villageData),
                        datasets: [{
                            data: Object.values(villageData),
                            backgroundColor: [
                                '#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 10, weight: 'bold' },
                                    padding: 15
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }
        };

        // Execute on load
        setTimeout(() => {
            initMap();
            initCharts();
        }, 100);
    </script>
    @endscript
</div>
