<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Beranda;
use App\Livewire\Warga\PengaduanForm;
use App\Livewire\Warga\Dashboard as WargaDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PengaduanManager;
use App\Livewire\Admin\PengaduanDetail;
use App\Livewire\TentangKami;

use App\Livewire\PengaduanFeedDetail;

Route::get('/', Beranda::class)->name('beranda');
Route::get('/tentang-kami', TentangKami::class)->name('tentang-kami');

Route::middleware(['auth', 'verified'])->group(function () {
    // Warga
    Route::get('/pengaduan/create', PengaduanForm::class)->name('pengaduan.create');
    Route::get('/pengaduan/{kode_tracking}/edit', PengaduanForm::class)->name('pengaduan.edit')->where('kode_tracking', '.*');
    Route::get('/dashboard', WargaDashboard::class)->name('dashboard');

    // Admin
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/pengaduan', PengaduanManager::class)->name('admin.pengaduan');
    Route::get('/admin/kategori', \App\Livewire\Admin\KategoriManager::class)->name('admin.kategori');
    Route::get('/admin/users', \App\Livewire\Admin\UserManager::class)->name('admin.users');
    Route::get('/admin/pengaturan', \App\Livewire\Admin\SettingManager::class)->name('admin.pengaturan');
    Route::get('/admin/laporan', \App\Livewire\Admin\LaporanManager::class)->name('admin.laporan');
    Route::get('/admin/pengaduan/print', [\App\Http\Controllers\PrintController::class, 'laporan'])->name('print.laporan');
    Route::get('/admin/pengaduan/{kode_tracking}', PengaduanDetail::class)->name('admin.pengaduan.detail')->where('kode_tracking', '.*');
    
    // Warga (Print Resi)
    Route::get('/pengaduan/{id}/print', [\App\Http\Controllers\PrintController::class, 'resi'])->name('print.resi');

});

Route::get('/pengaduan/{kode_tracking}', PengaduanFeedDetail::class)->name('pengaduan.feed-detail')->where('kode_tracking', '.*');



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';