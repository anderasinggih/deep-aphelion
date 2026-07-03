<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;

class PengaduanFeedDetail extends Component
{
    public Pengaduan $pengaduan;
    public $rating = 5;
    public $rating_pelayanan = 5;
    public $rating_respon = 5;
    public $rating_kompetensi = 5;
    public $rating_fasilitas = 5;
    public $rating_komentar = '';
    public $showFeedbackForm = false;
    public $shouldShowFeedback = false;

    public function mount($kode_tracking)
    {
        $this->pengaduan = Pengaduan::with([
            'user',
            'kategori',
            'linkedReport',
            'histories' => function ($query) {
                $query->latest();
            },
            'histories.user'
        ])->where('kode_tracking', $kode_tracking)->firstOrFail();

        $this->rating = $this->pengaduan->rating ?? 5;
        $this->rating_pelayanan = $this->pengaduan->rating_pelayanan ?? 5;
        $this->rating_respon = $this->pengaduan->rating_respon ?? 5;
        $this->rating_kompetensi = $this->pengaduan->rating_kompetensi ?? 5;
        $this->rating_fasilitas = $this->pengaduan->rating_fasilitas ?? 5;
        $this->rating_komentar = $this->pengaduan->rating_komentar ?? '';
        
        // Eager load ratings
        $this->pengaduan->load('ratings.user');

        $deviceIdentifier = request()->cookie('_kn_dfp') ?? request()->ip();
        $userId = auth()->id();
        
        $hasRated = $this->pengaduan->ratings->contains(function($r) use ($deviceIdentifier, $userId) {
            return $r->ip_address === $deviceIdentifier || ($userId && $r->user_id === $userId);
        });

        if ($this->pengaduan->status === 'selesai' && !$hasRated) {
            $this->shouldShowFeedback = true;
        }
    }

    public $previewModal = false;
    public $previewImageUrl = '';

    public function openPreview($url)
    {
        $this->previewImageUrl = $url;
        $this->previewModal = true;
    }

    public function submitFeedback()
    {
        $deviceIdentifier = request()->cookie('_kn_dfp') ?? request()->ip();
        $userId = auth()->id();

        $hasRated = $this->pengaduan->ratings()->where(function($query) use ($deviceIdentifier, $userId) {
            $query->where('ip_address', $deviceIdentifier);
            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        })->exists();

        if ($hasRated) {
            session()->flash('error', 'Anda atau perangkat Anda sudah mengirimkan feedback untuk aduan ini.');
            return;
        }

        $this->validate([
            'rating_pelayanan' => 'required|integer|min:1|max:5',
            'rating_respon' => 'required|integer|min:1|max:5',
            'rating_kompetensi' => 'required|integer|min:1|max:5',
            'rating_fasilitas' => 'required|integer|min:1|max:5',
            'rating_komentar' => 'nullable|string|max:200',
        ]);

        $averageRating = round(($this->rating_pelayanan + $this->rating_respon + $this->rating_kompetensi + $this->rating_fasilitas) / 4);

        $this->pengaduan->ratings()->create([
            'user_id' => $userId,
            'ip_address' => $deviceIdentifier,
            'rating_pelayanan' => $this->rating_pelayanan,
            'rating_respon' => $this->rating_respon,
            'rating_kompetensi' => $this->rating_kompetensi,
            'rating_fasilitas' => $this->rating_fasilitas,
            'rating' => $averageRating,
            'rating_komentar' => $this->rating_komentar,
        ]);

        $avgRating = round($this->pengaduan->ratings()->avg('rating'));
        $avgPelayanan = round($this->pengaduan->ratings()->avg('rating_pelayanan'));
        $avgRespon = round($this->pengaduan->ratings()->avg('rating_respon'));
        $avgKompetensi = round($this->pengaduan->ratings()->avg('rating_kompetensi'));
        $avgFasilitas = round($this->pengaduan->ratings()->avg('rating_fasilitas'));
        
        $this->pengaduan->update([
            'rating' => $avgRating,
            'rating_pelayanan' => $avgPelayanan,
            'rating_respon' => $avgRespon,
            'rating_kompetensi' => $avgKompetensi,
            'rating_fasilitas' => $avgFasilitas,
        ]);

        $this->shouldShowFeedback = false;
        $this->showFeedbackForm = false;
        
        $this->pengaduan->load('ratings.user');

        $this->dispatch('feedback-submitted');
        session()->flash('success', 'Terima kasih atas feedback Anda!');
    }

    public function render()
    {
        return view('livewire.pengaduan-feed-detail')
            ->layout('layouts.app');
    }
}