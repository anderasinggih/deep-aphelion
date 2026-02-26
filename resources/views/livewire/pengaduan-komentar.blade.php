<div class="mt-8 space-y-6">
    <div class="flex items-center gap-2 pb-2 border-b border-base-200">
        <x-icon name="o-chat-bubble-left-ellipsis" class="w-5 h-5 text-primary" />
        <h3 class="font-bold text-lg text-base-content/80">Komentar & Diskusi</h3>
        <span class="badge badge-primary badge-sm badge-outline ml-auto">{{ $komentars->count() }} Komentar</span>
    </div>

    {{-- Form Tambah Komentar Utama --}}
    @auth
    <div class="bg-base-200/50 p-4 rounded-2xl border border-base-200">
        <x-form wire:submit="postComment">
            <div class="flex gap-3">
                <div class="avatar placeholder hidden sm:flex shrink-0">
                    <div
                        class="bg-primary text-white rounded-full w-10 h-10 flex items-center justify-center shadow-sm">
                        <span class="text-xs font-black">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0 flex flex-col justify-end">
                    <x-textarea wire:model="komentar" placeholder="Tulis komentar..." rows="1"
                        class="w-full bg-base-100 border-base-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-sm transition-all !pt-3 !pb-2 !px-4" />
                    <div class="flex justify-end mt-2">
                        <x-button type="submit" label="Kirim" icon="o-paper-airplane"
                            class="btn-primary btn-sm rounded-lg shadow-sm px-4" spinner="postComment" />
                    </div>
                </div>
            </div>
        </x-form>
    </div>
    @else
    <div class="bg-base-200/50 p-4 rounded-xl border border-base-200 text-center">
        <p class="text-sm font-medium text-base-content/70 mb-3">Silakan masuk (login) untuk ikut berdiskusi.</p>
        <x-button label="Masuk Sekarang" icon="o-arrow-right-end-on-rectangle" link="{{ route('login') }}"
            class="btn-primary btn-sm rounded-lg shadow-sm" />
    </div>
    @endauth

    {{-- Daftar Komentar --}}
    <div class="space-y-4">
        @forelse($komentars as $comment)
        {{-- Komentar Item Utama --}}
        <div class="flex gap-3 {{ $loop->last ? '' : 'pb-4 border-b border-base-200/50' }}">
            {{-- Avatar --}}
            <div class="avatar placeholder shrink-0 mt-1">
                @php
                $avatarColor = $comment->user->role === 'admin' ? 'bg-error' : ($comment->user->role === 'petugas' ?
                'bg-info' : 'bg-base-300');
                $textColor = $comment->user->role === 'warga' ? 'text-base-content/70' : 'text-white';
                @endphp
                <div
                    class="{{ $avatarColor }} {{ $textColor }} rounded-full w-9 h-9 flex items-center justify-center shadow-sm border border-base-100">
                    <span class="text-xs font-black">{{ substr($comment->user->name, 0, 1) }}</span>
                </div>
            </div>

            {{-- Konten Komentar --}}
            <div class="flex-1 min-w-0">
                <div
                    class="bg-base-100 p-3 rounded-2xl border border-base-200/60 shadow-sm relative group overflow-hidden">
                    {{-- Header Komentar --}}
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span
                                class="font-bold text-[13px] text-base-content leading-tight hover:underline cursor-pointer">
                                {{ $comment->user->name }}
                            </span>
                            @if($comment->user->role === 'admin')
                            <x-badge value="Admin"
                                class="badge-error badge-xs font-bold px-1 py-0.5 text-[9px] uppercase tracking-wider" />
                            @elseif($comment->user->role === 'petugas')
                            <x-badge value="Petugas"
                                class="badge-info badge-xs font-bold px-1 py-0.5 text-[9px] uppercase tracking-wider" />
                            @endif
                            <span class="text-[10px] font-medium text-base-content/50">•</span>
                            <span class="text-[10px] font-medium text-base-content/50"
                                title="{{ $comment->created_at->format('d M Y, H:i') }}">
                                {{ $comment->created_at->diffForHumans(null, true, true) }}
                            </span>
                        </div>

                        {{-- Delete Button (Tampil jika hover & owner/admin) --}}
                        @auth
                        @if(Auth::id() === $comment->user_id || Auth::user()->role === 'admin')
                        <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Hapus komentar ini?"
                            class="text-error/70 hover:text-error transition-colors p-1 md:opacity-0 md:group-hover:opacity-100 absolute right-2 top-2">
                            <x-icon name="o-trash" class="w-3.5 h-3.5" />
                        </button>
                        @endif
                        @endauth
                    </div>

                    {{-- Teks Komentar --}}
                    <p class="text-sm text-base-content/80 leading-relaxed whitespace-pre-wrap word-break">{{
                        $comment->komentar }}</p>
                </div>

                {{-- Actions (Balas) --}}
                <div class="flex items-center gap-3 mt-1.5 ml-2">
                    <button wire:click="setReply({{ $comment->id }})"
                        class="text-[11px] font-bold text-base-content/60 hover:text-primary transition-colors flex items-center gap-1">
                        <x-icon name="o-chat-bubble-oval-left" class="w-3.5 h-3.5" /> Balas
                    </button>
                </div>

                {{-- Form Balas --}}
                @if($reply_to === $comment->id)
                <div class="mt-3 ml-2 flex gap-2">
                    <div class="flex-1 min-w-0">
                        <x-form wire:submit="postReply({{ $comment->id }})">
                            <x-textarea wire:model="reply_komentar.{{ $comment->id }}"
                                placeholder="Balas @ {{ $comment->user->name }}..." rows="1"
                                class="w-full bg-base-100 border-base-300 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-xs transition-all !min-h-[2.5rem] !py-2" />
                            <div class="flex justify-end gap-2 mt-2">
                                <x-button label="Batal" wire:click="setReply(null)"
                                    class="btn-ghost btn-xs rounded-lg text-[10px]" />
                                <x-button type="submit" label="Kirim"
                                    class="btn-primary btn-xs rounded-lg shadow-sm text-[10px]"
                                    spinner="postReply({{ $comment->id }})" />
                            </div>
                        </x-form>
                    </div>
                </div>
                @endif

                {{-- Render Balasan (Nesting Level 1) --}}
                @if($comment->replies->count() > 0)
                <div class="mt-3 space-y-3">
                    {{-- Indikator Garis Samping Kiri untuk Balasan --}}
                    <div class="relative pl-6 sm:pl-8">
                        <div class="absolute left-3 top-0 bottom-6 w-px bg-base-300 rounded-full"></div>

                        @foreach($comment->replies as $reply)
                        <div class="flex gap-2 relative {{ !$loop->first ? 'mt-3' : '' }}">
                            {{-- Garis L shaped --}}
                            <div class="absolute -left-6 top-3 w-4 h-px bg-base-300"></div>

                            {{-- Avatar Balasan --}}
                            <div class="avatar placeholder shrink-0 relative z-10">
                                @php
                                $replyAvatarColor = $reply->user->role === 'admin' ? 'bg-error' : ($reply->user->role
                                === 'petugas' ? 'bg-info' : 'bg-base-200');
                                $replyTextColor = $reply->user->role === 'warga' ? 'text-base-content/70' :
                                'text-white';
                                @endphp
                                <div
                                    class="{{ $replyAvatarColor }} {{ $replyTextColor }} rounded-full w-6 h-6 flex items-center justify-center shadow-sm border border-base-100">
                                    <span class="text-[10px] font-black">{{ substr($reply->user->name, 0, 1) }}</span>
                                </div>
                            </div>

                            {{-- Konten Balasan --}}
                            <div class="flex-1 min-w-0">
                                <div
                                    class="bg-base-200/30 p-2.5 rounded-2xl rounded-tl-sm border border-base-200 shadow-sm relative group overflow-hidden">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span
                                                class="font-bold text-xs text-base-content leading-tight hover:underline cursor-pointer">
                                                {{ $reply->user->name }}
                                            </span>
                                            @if($reply->user->role === 'admin')
                                            <x-badge value="Admin"
                                                class="badge-error badge-xs font-bold px-1 py-0.5 text-[8px] uppercase tracking-wider" />
                                            @elseif($reply->user->role === 'petugas')
                                            <x-badge value="Petugas"
                                                class="badge-info badge-xs font-bold px-1 py-0.5 text-[8px] uppercase tracking-wider" />
                                            @endif
                                            <span class="text-[9px] font-medium text-base-content/50">•</span>
                                            <span class="text-[9px] font-medium text-base-content/50"
                                                title="{{ $reply->created_at->format('d M Y, H:i') }}">
                                                {{ $reply->created_at->diffForHumans(null, true, true) }}
                                            </span>
                                        </div>

                                        @auth
                                        @if(Auth::id() === $reply->user_id || Auth::user()->role === 'admin')
                                        <button wire:click="deleteComment({{ $reply->id }})"
                                            wire:confirm="Hapus balasan ini?"
                                            class="text-error/70 hover:text-error transition-colors p-1 opacity-0 group-hover:opacity-100 absolute right-1 top-1">
                                            <x-icon name="o-trash" class="w-3 h-3" />
                                        </button>
                                        @endif
                                        @endauth
                                    </div>
                                    <p class="text-[13px] text-base-content/80 leading-relaxed word-break">{{
                                        $reply->komentar }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-10 bg-base-100 rounded-2xl border border-dashed border-base-300">
            <x-icon name="o-chat-bubble-bottom-center-text" class="w-12 h-12 text-base-content/20 mx-auto mb-3" />
            <p class="text-sm font-bold text-base-content/60">Belum ada komentar.</p>
            <p class="text-xs text-base-content/40 mt-1">Jadilah yang pertama untuk berpendapat di pengaduan ini!</p>
        </div>
        @endforelse
    </div>

    <style>
        .word-break {
            word-break: break-word;
        }
    </style>
</div>