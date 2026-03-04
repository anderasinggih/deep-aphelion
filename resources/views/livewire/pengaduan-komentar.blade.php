<div class="mt-6 space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between pb-1 border-b border-base-200">
        <div class="flex items-center gap-2">
            <x-icon name="o-chat-bubble-left-right" class="w-4 h-4 text-primary" />
            <h3 class="font-bold text-sm uppercase tracking-wider text-base-content/70">Diskusi</h3>
        </div>
        <span class="text-[10px] font-bold opacity-50">{{ $this->totalComments }} Komentar</span>
    </div>

    {{-- Form Input Utama --}}
    @auth
    <div class="flex gap-2 items-start bg-base-200/30 p-2 rounded-xl ">
        <x-user-avatar :user="auth()->user()" size="w-8 h-8" />
        <div class="flex-1 min-w-0">
            <x-form wire:submit="postComment" class="flex flex-col gap-2">
                <x-textarea wire:model="komentar" placeholder="Tulis komentar..." rows="1"
                    class="!min-h-[35px] text-sm bg-base-100 border-none focus:ring-1 focus:ring-primary rounded-lg" />
                <div class="flex justify-end">
                    <x-button type="submit" label="Kirim" class="btn-primary btn-xs p-4 rounded-md"
                        spinner="postComment" />
                </div>
            </x-form>
        </div>
    </div>
    @endauth

    {{-- List Komentar --}}
    <div class="space-y-5">
        @forelse($this->komentars as $comment)
        <div class="flex gap-3">
            {{-- Avatar --}}
            <x-user-avatar :user="$comment->user" size="w-8 h-8" class="mt-0.5 shrink-0" />

            {{-- Bubble Konten --}}
            <div class="flex-1 min-w-0">
                <div
                    class="bg-base-200/50 rounded-2xl rounded-tl-none px-3 py-2 inline-block max-w-full group relative">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="font-bold text-xs">{{ $comment->user->name }}</span>
                        @if($comment->user->role !== 'warga')
                        <span class="text-[9px] px-1 bg-base-300 rounded text-base-content/70 font-bold uppercase">{{
                            $comment->user->role }}</span>
                        @endif
                        <span class="text-[9px] opacity-40 italic">{{ $comment->created_at->diffForHumans(null, true,
                            true) }}</span>
                    </div>
                    <p class="text-[13px] text-base-content/90 leading-snug break-words">{{ $comment->komentar }}</p>

                    {{-- Delete icon --}}
                    @if(Auth::id() === $comment->user_id || Auth::user()?->role === 'admin')
                    <button wire:click="deleteComment({{ $comment->id }})"
                        class="absolute -right-6 top-1 text-error/40 hover:text-error opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-icon name="o-trash" class="w-3 h-3" />
                    </button>
                    @endif
                </div>

                {{-- Action Balas --}}
                <div class="mt-1 ml-1 flex items-center gap-4">
                    <button wire:click="setReply({{ $comment->id }})"
                        class="text-[10px] font-bold opacity-60 hover:text-primary">Balas</button>
                </div>

                {{-- Input Reply --}}
                @if($reply_to === $comment->id)
                <div class="mt-2 flex gap-2">
                    <x-textarea wire:model="reply_text" placeholder="Balas..."
                        class="!min-h-[32px] text-xs flex-1 rounded-lg" rows="1" />
                    <x-button wire:click="postReply({{ $comment->id }})" icon="o-paper-airplane"
                        class="btn-primary btn-sm p-2" />
                </div>
                @endif

                {{-- Balasan (Replies) --}}
                @if($comment->replies->count() > 0)
                <div class="mt-3 ml-2 pl-4 border-l-2 border-base-200 space-y-3">
                    @foreach($comment->replies as $reply)
                    <div class="flex gap-2">
                        <x-user-avatar :user="$reply->user" size="w-6 h-6" class="mt-0.5 shrink-0" />
                        <div class="bg-base-100  rounded-xl px-2.5 py-1.5 inline-block max-w-full">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="font-bold text-[11px]">{{ $reply->user->name }}</span>
                                <span class="text-[8px] opacity-40 italic">{{ $reply->created_at->diffForHumans(null,
                                    true, true) }}</span>
                            </div>
                            <p class="text-xs text-base-content/80 leading-tight">{{ $reply->komentar }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-6 opacity-40">
            <x-icon name="o-chat-bubble-bottom-center" class="w-8 h-8 mx-auto" />
            <p class="text-xs mt-1">Belum ada diskusi.</p>
        </div>
        @endforelse
    </div>

    {{-- Load More --}}
    @if($this->totalComments > $limit)
    <button wire:click="loadMore"
        class="w-full text-center py-2 text-[11px] font-bold opacity-50 hover:opacity-100 transition-opacity">
        Lihat komentar lainnya...
    </button>
    @endif
</div>