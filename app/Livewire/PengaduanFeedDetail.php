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
        
        // Show form if status is selesai and user is the reporter and rating is still null
        if ($this->pengaduan->status === 'selesai' && auth()->id() === $this->pengaduan->user_id && is_null($this->pengaduan->rating)) {
            $this->showFeedbackForm = true;
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
        if (auth()->id() !== $this->pengaduan->user_id) return;

        $this->validate([
            'rating_pelayanan' => 'required|integer|min:1|max:5',
            'rating_respon' => 'required|integer|min:1|max:5',
            'rating_kompetensi' => 'required|integer|min:1|max:5',
            'rating_fasilitas' => 'required|integer|min:1|max:5',
            'rating_komentar' => 'nullable|string|max:200',
        ]);

        // Calculate average for the main rating column
        $averageRating = round(($this->rating_pelayanan + $this->rating_respon + $this->rating_kompetensi + $this->rating_fasilitas) / 4);

        $this->pengaduan->update([
            'rating' => $averageRating,
            'rating_pelayanan' => $this->rating_pelayanan,
            'rating_respon' => $this->rating_respon,
            'rating_kompetensi' => $this->rating_kompetensi,
            'rating_fasilitas' => $this->rating_fasilitas,
            'rating_komentar' => $this->rating_komentar,
        ]);

        $this->showFeedbackForm = false;
        
        $this->dispatch('feedback-submitted');
        session()->flash('success', 'Terima kasih atas feedback Anda!');
    }

    public function render()
    {
        return view('livewire.pengaduan-feed-detail')
            ->layout('layouts.app');
    }
}