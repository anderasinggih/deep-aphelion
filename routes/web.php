<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Beranda;
use App\Livewire\Warga\PengaduanForm;
use App\Livewire\Warga\Dashboard as WargaDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PengaduanManager;
use App\Livewire\Admin\PengaduanDetail;
use App\Livewire\Petugas\Disposisi;
use App\Livewire\PengaduanFeedDetail;

Route::get('/', Beranda::class)->name('beranda');
Route::get('/pengaduan/{id}', PengaduanFeedDetail::class)->name('pengaduan.feed-detail')->where('id', '[0-9]+');

Route::middleware(['auth'])->group(function () {
    // Warga
    Route::get('/pengaduan/create', PengaduanForm::class)->name('pengaduan.create');
    Route::get('/pengaduan/{id}/edit', PengaduanForm::class)->name('pengaduan.edit');
    Route::get('/dashboard', WargaDashboard::class)->name('dashboard');

    // Admin
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/pengaduan', PengaduanManager::class)->name('admin.pengaduan');
    Route::get('/admin/pengaduan/{id}', PengaduanDetail::class)->name('admin.pengaduan.detail');

    // Petugas
    Route::get('/petugas/disposisi', Disposisi::class)->name('petugas.disposisi');
});



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';