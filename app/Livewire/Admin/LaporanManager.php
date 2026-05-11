<?php

namespace App\Livewire\Admin;

use App\Models\Pengaduan;
use App\Models\Kategori;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanManager extends Component
{
    public $startDate;
    public $endDate;
    public $period = 'this_month';

    public function mount()
    {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'admin', 'petugas']), 403);
        $this->setPeriod('this_month');
    }

    public function updatedPeriod($value)
    {
        $this->setPeriod($value);
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        
        switch ($period) {
            case 'this_month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                // Keep current dates
                break;
        }
    }

    public function getReportData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $query = Pengaduan::whereBetween('created_at', [$start, $end]);

        $stats = [
            'total' => (clone $query)->count(),
            'menunggu' => (clone $query)->where('status', 'menunggu')->count(),
            'diproses' => (clone $query)->where('status', 'diproses')->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
            'ditolak' => (clone $query)->where('status', 'ditolak')->count(),
            'rata_rating' => (clone $query)->whereNotNull('rating')->avg('rating') ?: 0,
        ];

        $ratingDistribution = (clone $query)->whereNotNull('rating')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $categoryPerformance = Kategori::withCount(['pengaduans as total_laporan' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }])
            ->withCount(['pengaduans as laporan_selesai' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])->where('status', 'selesai');
            }])
            ->get()
            ->map(function($kat) {
                $kat->avg_resolution_time = DB::table('pengaduan_histories')
                    ->join('pengaduans', 'pengaduans.id', '=', 'pengaduan_histories.pengaduan_id')
                    ->where('pengaduans.kategori_id', $kat->id)
                    ->whereBetween('pengaduans.created_at', [Carbon::parse($this->startDate), Carbon::parse($this->endDate)])
                    ->where('pengaduan_histories.status_baru', 'selesai')
                    ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, pengaduans.created_at, pengaduan_histories.created_at)) as avg_time'))
                    ->first()->avg_time ?: 0;
                return $kat;
            })
            ->sortByDesc('total_laporan')
            ->take(5);

        $laporanList = (clone $query)->with(['user', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'stats' => $stats,
            'ratingDistribution' => $ratingDistribution,
            'categoryPerformance' => $categoryPerformance,
            'laporanList' => $laporanList,
            'period_label' => $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y')
        ];
    }

    public function render()
    {
        return view('livewire.admin.laporan-manager', [
            'data' => $this->getReportData()
        ])->layout('layouts.app');
    }
}
