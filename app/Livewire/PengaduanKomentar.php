<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PengaduanKomentar extends Component
{
    public $pengaduan_id;
    public $komentar = '';
    public $reply_to = null;
    public $reply_komentar = []; // Store reply text per comment id

    // Listen to refresh event
    protected $listeners = ['commentAdded' => '$refresh'];

    public function mount($pengaduan_id)
    {
        $this->pengaduan_id = $pengaduan_id;
    }

    public function render()
    {
        $komentars = \App\Models\PengaduanKomentar::with(['user', 'replies.user', 'replies.replies.user'])
            ->where('pengaduan_id', $this->pengaduan_id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.pengaduan-komentar', [
            'komentars' => $komentars
        ]);
    }

    public function postComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'komentar' => 'required|string|max:1000'
        ]);

        \App\Models\PengaduanKomentar::create([
            'pengaduan_id' => $this->pengaduan_id,
            'user_id' => Auth::id(),
            'komentar' => $this->komentar
        ]);

        $this->komentar = '';
        $this->dispatch('commentAdded');
    }

    public function setReply($commentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $this->reply_to = $this->reply_to === $commentId ? null : $commentId;
        if (!isset($this->reply_komentar[$commentId])) {
            $this->reply_komentar[$commentId] = '';
        }
    }

    public function postReply($parentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            "reply_komentar.$parentId" => 'required|string|max:1000'
        ]);

        \App\Models\PengaduanKomentar::create([
            'pengaduan_id' => $this->pengaduan_id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'komentar' => $this->reply_komentar[$parentId]
        ]);

        $this->reply_komentar[$parentId] = '';
        $this->reply_to = null;
        $this->dispatch('commentAdded');
    }

    public function deleteComment($id)
    {
        $comment = \App\Models\PengaduanKomentar::find($id);

        if ($comment && (Auth::id() === $comment->user_id || Auth::user()->role === 'admin')) {
            $comment->delete();
            $this->dispatch('commentAdded');
        }
    }
}