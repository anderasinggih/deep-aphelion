<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Stats untuk Kotak Ringkasan
        $stats = [
            'total_laporan' => Pengaduan::count(),
            'menunggu' => Pengaduan::query()->where('status', 'menunggu')->count(),
            'diproses' => Pengaduan::query()->where('status', 'diproses')->count(),
            'selesai' => Pengaduan::query()->where('status', 'selesai')->count(),
            'ditolak' => Pengaduan::query()->where('status', 'ditolak')->count(),
            'total_warga' => User::query()->where('role', 'warga')->count()
        ];

        // 2. Data Marker untuk Peta Sebaran (Heatmap)
        $markers = Pengaduan::query()->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'judul', 'latitude', 'longitude', 'status')
            ->get();

        // 3. 5 Laporan Terbaru
        $laporanTerbaru = Pengaduan::with('user', 'kategori')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'markers' => $markers,
            'laporanTerbaru' => $laporanTerbaru
        ])->layout('layouts.app');
    }
}