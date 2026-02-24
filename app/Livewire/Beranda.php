<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\PengaduanDukungan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Beranda extends Component
{
    use WithPagination;

    public $kategori_id = '';
    public $search = '';
    public $sort = 'terbaru';

    public function upvote($pengaduan_id)
    {
        if (!Auth::check() || Auth::user()->role !== 'warga') {
            session()->flash('error', 'Silakan login sebagai warga untuk memberikan dukungan.');
            return;
        }

        $userId = Auth::id();
        $existing = PengaduanDukungan::query()->where('pengaduan_id', $pengaduan_id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete(); // Toggle (Cancel upvote)
            session()->flash('success', 'Dukungan dibatalkan.');
        }
        else {
            PengaduanDukungan::create([
                'pengaduan_id' => $pengaduan_id,
                'user_id' => $userId
            ]);
            session()->flash('success', 'Terima kasih atas dukungan Anda!');
        }
    }

    public function render()
    {
        $query = Pengaduan::query()
            ->with(['user', 'kategori', 'dukungans'])
            ->withCount('dukungans')
            ->where('status', '!=', 'ditolak');

        if ($this->kategori_id) {
            $query->where('kategori_id', $this->kategori_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sort === 'terpopuler') {
            $query->orderBy('dukungans_count', 'desc');
        }
        else {
            $query->orderBy('created_at', 'desc');
        }

        return view('livewire.beranda', [
            'pengaduans' => $query->paginate(10),
            'kategoris' => Kategori::all()
        ])->layout('layouts.app');
    }
}