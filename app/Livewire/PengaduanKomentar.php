<?php

namespace App\Livewire;

use App\Models\PengaduanKomentar as KomentarModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;

class PengaduanKomentar extends Component
{
    public $pengaduan_id;
    public $komentar = '';
    public $reply_to = null;
    public $reply_text = ''; 
    public $limit = 5;

    protected $listeners = ['commentAdded' => '$refresh'];

    public function mount($pengaduan_id) {
        $this->pengaduan_id = $pengaduan_id;
    }

    #[Computed]
    public function komentars() {
        return KomentarModel::query()->with(['user', 'replies.user'])
            ->where('pengaduan_id', $this->pengaduan_id)
            ->whereNull('parent_id')
            ->latest()
            ->take($this->limit)
            ->get();
    }

    #[Computed]
    public function totalComments() {
        return KomentarModel::query()->where('pengaduan_id', $this->pengaduan_id)->whereNull('parent_id')->count();
    }

    public function postComment() {
        $this->validate(['komentar' => 'required|max:1000']);
        KomentarModel::create([
            'pengaduan_id' => $this->pengaduan_id,
            'user_id' => Auth::id(),
            'komentar' => $this->komentar
        ]);
        $this->reset('komentar');
    }

    public function setReply($id) {
        $this->reply_to = ($this->reply_to === $id) ? null : $id;
        $this->reset('reply_text');
    }

    public function postReply($parentId) {
        $this->validate(['reply_text' => 'required|max:1000']);
        KomentarModel::create([
            'pengaduan_id' => $this->pengaduan_id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'komentar' => $this->reply_text
        ]);
        $this->reset(['reply_text', 'reply_to']);
    }

    public function deleteComment($id) {
        $comment = KomentarModel::find($id);
        if ($comment && (Auth::id() === $comment->user_id || Auth::user()->role === 'admin')) {
            $comment->delete();
        }
    }

    public function loadMore() { $this->limit += 5; }

    public function render() { return view('livewire.pengaduan-komentar'); }
}