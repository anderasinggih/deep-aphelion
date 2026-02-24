<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Beranda;
use App\Livewire\Warga\PengaduanForm;
use App\Livewire\Warga\Dashboard as WargaDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PengaduanManager;
use App\Livewire\Petugas\Disposisi;

Route::get('/', Beranda::class)->name('beranda');

Route::middleware(['auth'])->group(function () {
    // Warga
    Route::get('/pengaduan/create', PengaduanForm::class)->name('pengaduan.create');
    Route::get('/dashboard', WargaDashboard::class)->name('dashboard');

    // Admin
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/pengaduan', PengaduanManager::class)->name('admin.pengaduan');

    // Petugas
    Route::get('/petugas/disposisi', Disposisi::class)->name('petugas.disposisi');
});



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';