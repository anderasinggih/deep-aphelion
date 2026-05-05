<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Stats untuk Kotak Ringkasan
        $stats = [
            'total_laporan'    => Pengaduan::count(),
            'menunggu'         => Pengaduan::where('status', 'menunggu')->count(),
            'diproses'         => Pengaduan::where('status', 'diproses')->count(),
            'selesai'          => Pengaduan::where('status', 'selesai')->count(),
            'ditolak'          => Pengaduan::where('status', 'ditolak')->count(),
            'total_warga'      => User::where('role', 'warga')->count(),
            'rata_rating'      => Pengaduan::whereNotNull('rating')->avg('rating') ?? 0,
            'laporan_bulan_ini'=> Pengaduan::whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)->count(),
        ];

        // 2. Tren laporan 6 bulan terakhir untuk Chart.js
        $trenData = collect(range(5, 0))->map(function ($i) {
            $bulan = now()->subMonths($i);
            return [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => Pengaduan::whereMonth('created_at', $bulan->month)
                                    ->whereYear('created_at', $bulan->year)
                                    ->count(),
            ];
        });

        // 3. Laporan Prioritas Tinggi: aktif dan prioritas tinggi
        $laporanPrioritasTinggi = Pengaduan::with(['user', 'kategori'])
            ->whereIn('status', ['menunggu', 'diproses'])
            ->where('prioritas', 'tinggi')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $stats['prioritas_tinggi'] = Pengaduan::whereIn('status', ['menunggu', 'diproses'])->where('prioritas', 'tinggi')->count();

        // 4. Data Marker peta
        $markers = Pengaduan::whereNotNull('latitude')->whereNotNull('longitude')
            ->select('id', 'judul', 'latitude', 'longitude', 'status')->get();

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
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('total', 'rating')
            ->toArray();

        // 7. Leaderboard Performa Kategori (Selesai terbanyak & Rata-rata waktu penyelesaian)
        $categoryPerformance = \App\Models\Kategori::withCount(['pengaduans as total_laporan'])
            ->withCount(['pengaduans as laporan_selesai' => function($q) {
                $q->where('status', 'selesai');
            }])
            ->get()
            ->map(function($kat) {
                // Rata-rata waktu penyelesaian dalam jam
                $avgHours = DB::table('pengaduans')
                    ->where('kategori_id', $kat->id)
                    ->where('status', 'selesai')
                    ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time'))
                    ->first()->avg_time ?? 0;
                
                $kat->avg_resolution_time = round($avgHours, 1);
                return $kat;
            })
            ->sortByDesc('laporan_selesai')
            ->take(5);

        return view('livewire.admin.dashboard', compact(
            'stats', 'markers', 'laporanTerbaru', 'trenData', 'laporanPrioritasTinggi', 'recentFeedbacks', 'ratingDistribution', 'categoryPerformance'
        ))->layout('layouts.app');
    }
}