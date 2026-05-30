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
        
        // Show form if status is selesai and (logged in user is the owner OR it is a guest report) and rating is still null
        $isOwner = auth()->check() ? (auth()->id() === $this->pengaduan->user_id && auth()->user()?->role === 'warga') : (is_null($this->pengaduan->user_id));
        
        // Cek juga jika IP ini sudah pernah memberi feedback untuk aduan ini
        $ip = request()->ip();
        $hasSubmitted = \Illuminate\Support\Facades\Cache::has('feedback_submitted_' . $this->pengaduan->id . '_' . $ip);

        if ($this->pengaduan->status === 'selesai' && $isOwner && is_null($this->pengaduan->rating) && !$hasSubmitted) {
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
        $isOwner = auth()->check() ? (auth()->id() === $this->pengaduan->user_id && auth()->user()?->role === 'warga') : (is_null($this->pengaduan->user_id));

        if (!$isOwner) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk memberikan penilaian.');
            return;
        }

        if (!is_null($this->pengaduan->rating)) {
            session()->flash('error', 'Laporan ini sudah diberi penilaian.');
            return;
        }

        $ip = request()->ip();
        $cacheKey = 'feedback_submitted_' . $this->pengaduan->id . '_' . $ip;
        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            session()->flash('error', 'IP Anda sudah mengirimkan feedback untuk aduan ini.');
            return;
        }

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

        // Simpan cache agar IP ini tidak bisa submit lagi untuk aduan ini (selamanya / 1 tahun)
        \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addYear());

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