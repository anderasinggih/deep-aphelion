<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $map_status = 'all';
    public $isMapExpanded = false;

    public function toggleMapExpand()
    {
        $this->isMapExpanded = !$this->isMapExpanded;
        $this->dispatch('map-resized');
    }

    public function mount()
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'petugas']), 403);
    }

    public function render()
    {
        // 1. Konsolidasi Stats (Gunakan satu query untuk semua status)
        $statusCounts = Pengaduan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $stats = [
            'total_laporan'    => array_sum($statusCounts),
            'menunggu'         => $statusCounts['menunggu'] ?? 0,
            'diproses'         => $statusCounts['diproses'] ?? 0,
            'selesai'          => $statusCounts['selesai'] ?? 0,
            'ditolak'          => $statusCounts['ditolak'] ?? 0,
            'total_warga'      => User::where('role', 'warga')->count(),
            'rata_rating'      => Pengaduan::whereNotNull('rating')->avg('rating') ?? 0,
            'rata_pelayanan'   => Pengaduan::whereNotNull('rating_pelayanan')->avg('rating_pelayanan') ?? 0,
            'rata_respon'      => Pengaduan::whereNotNull('rating_respon')->avg('rating_respon') ?? 0,
            'rata_kompetensi'  => Pengaduan::whereNotNull('rating_kompetensi')->avg('rating_kompetensi') ?? 0,
            'rata_fasilitas'   => Pengaduan::whereNotNull('rating_fasilitas')->avg('rating_fasilitas') ?? 0,
            'laporan_bulan_ini'=> Pengaduan::whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)->count(),
        ];

        // 2. Tren laporan 6 bulan terakhir (Satu query untuk semua bulan)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $trenQuery = Pengaduan::where('created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function($item) {
                return $item->month . '-' . $item->year;
            });

        $trenData = collect(range(5, 0))->map(function ($i) use ($trenQuery) {
            $bulan = now()->subMonths($i);
            $key = $bulan->month . '-' . $bulan->year;
            return [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => $trenQuery->get($key)->total ?? 0,
            ];
        });

        // 3. Laporan Prioritas Tinggi
        $laporanPrioritasTinggi = Pengaduan::with(['user', 'kategori'])
            ->whereIn('status', ['menunggu', 'diproses'])
            ->where('prioritas', 'tinggi')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $stats['prioritas_tinggi'] = Pengaduan::whereIn('status', ['menunggu', 'diproses'])->where('prioritas', 'tinggi')->count();

        // 4. Data Marker peta (Optimasi: Filter Status + Ambil Kode Tracking untuk Link)
        $markerQuery = Pengaduan::whereNotNull('latitude')->whereNotNull('longitude')
            ->where('created_at', '>', now()->subMonths(3));
        
        if ($this->map_status !== 'all') {
            $markerQuery->where('status', $this->map_status);
        }

        $markers = $markerQuery->select('id', 'judul', 'latitude', 'longitude', 'status', 'kode_tracking')
            ->take(300)
            ->get();

        // 5. 5 Laporan Terbaru
        $laporanTerbaru = Pengaduan::with('user', 'kategori')->latest()->take(5)->get();

        // 6. Feedback & Rating Analysis
        $recentFeedbacks = Pengaduan::with('user')
            ->whereNotNull('rating')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $ratingDistribution = Pengaduan::whereNotNull('rating')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        // 7. Leaderboard Performa Kategori (Optimasi Query: Hindari Loop N+1)
        $categoryPerformance = \App\Models\Kategori::select('kategoris.id', 'kategoris.nama')
            ->withCount(['pengaduans as total_laporan'])
            ->withCount(['pengaduans as laporan_selesai' => function($q) {
                $q->where('status', 'selesai');
            }])
            ->leftJoin('pengaduans', function($join) {
                $join->on('kategoris.id', '=', 'pengaduans.kategori_id')
                    ->where('pengaduans.status', 'selesai');
            })
            ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, pengaduans.created_at, pengaduans.updated_at)), 1) as avg_resolution_time')
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->orderByDesc('laporan_selesai')
            ->take(5)
            ->get();

        // 8. Tren 7 Hari Terakhir
        $sevenDaysTrendQuery = Pengaduan::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $sevenDaysTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sevenDaysTrend[] = [
                'label' => now()->subDays($i)->translatedFormat('D'),
                'total' => $sevenDaysTrendQuery[$date] ?? 0
            ];
        }

        // 9. Sebaran Per Desa (Ekstrak dari lokasi_kejadian atau Dummy list)
        // Jika tidak ada kolom desa khusus, kita coba deteksi kata 'Desa' dari lokasi_kejadian
        $topVillages = Pengaduan::where('lokasi_kejadian', 'like', '%Desa%')
            ->select('lokasi_kejadian', DB::raw('count(*) as total'))
            ->groupBy('lokasi_kejadian')
            ->get()
            ->map(function($item) {
                // Ekstrak nama desa (misal "Desa Pliken, Kec. Kembaran" -> "Pliken")
                preg_match('/Desa\s+([a-zA-Z\s]+)/i', $item->lokasi_kejadian, $matches);
                $name = $matches[1] ?? 'Lainnya';
                $name = trim(explode(',', $name)[0]);
                return [
                    'name' => $name,
                    'total' => $item->total
                ];
            })
            ->groupBy('name')
            ->map(fn($group) => $group->sum('total'))
            ->sortDesc()
            ->take(5)
            ->toArray();

        $this->dispatch('map-updated', $markers);

        return view('livewire.admin.dashboard', compact(
            'stats', 'markers', 'laporanTerbaru', 'trenData', 'laporanPrioritasTinggi', 'recentFeedbacks', 'ratingDistribution', 'categoryPerformance', 'sevenDaysTrend', 'topVillages'
        ))->layout('layouts.app');
    }

    public function formatNumber($val)
    {
        if (!is_numeric($val)) return $val;
        if ($val >= 1000) {
            $formatted = number_format($val / 1000, 1, ',', '.');
            return str_replace(',0', '', $formatted) . 'rb';
        }
        return number_format($val, 0, ',', '.');
    }
}
