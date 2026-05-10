<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pengaduan;

class PengaduanFeedDetail extends Component
{
    public Pengaduan $pengaduan;
    public $rating = 5;
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
            'rating' => 'required|integer|min:1|max:5',
            'rating_komentar' => 'nullable|string|max:200',
        ]);

        $this->pengaduan->update([
            'rating' => $this->rating,
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