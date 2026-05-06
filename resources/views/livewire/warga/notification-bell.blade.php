<div class="dropdown dropdown-end" wire:poll.60s>
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle btn-sm relative hover:bg-base-200/50 transition-colors flex items-center justify-center">
        @if($this->unreadCount > 0)
            <lottie-player src="https://lottie.host/88029d59-673e-46f3-a447-06109968411c/n2lVwXkZ1G.json" background="transparent" speed="1.5" style="width: 32px; height: 32px;" loop autoplay></lottie-player>
            <span class="absolute top-1 right-1 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
            </span>
        @else
            <x-icon name="o-bell" class="w-5 h-5 opacity-70" />
        @endif
    </div>
    <div tabindex="0" class="dropdown-content z-[100] menu p-0 shadow-2xl bg-base-100 rounded-2xl w-[88vw] sm:w-80 border border-base-200 mt-3 -mr-10 sm:mr-0 overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-4 py-3 bg-base-200/50 border-b border-base-200 flex items-center justify-between">
            <span class="text-[11px] font-black text-base-content/60">Notifikasi</span>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-bold text-primary hover:underline">Tandai semua dibaca</button>
            @endif
        </div>
        
        <div class="max-h-96 overflow-y-auto no-scrollbar">
            @forelse($this->notifications as $notification)
                <div wire:click="markAsRead('{{ $notification->id }}')" 
                     class="px-4 py-4 border-b border-base-200/50 hover:bg-base-200/30 cursor-pointer transition-colors relative {{ $notification->read_at ? 'opacity-50' : '' }}">
                    @if(!$notification->read_at)
                        <div class="absolute left-1.5 top-1/2 -translate-y-1/2 w-1 h-1 rounded-full bg-primary"></div>
                    @endif
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[9px] font-black text-primary tracking-tight">{{ $notification->data['kode_tracking'] ?? 'INFO' }}</span>
                            <span class="text-[9px] font-bold text-base-content/40">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[12px] font-bold text-base-content leading-snug">
                            {{ $notification->data['pesan'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-4 py-10 text-center">
                    <div class="w-12 h-12 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <x-icon name="o-bell-slash" class="w-6 h-6 opacity-20" />
                    </div>
                    <p class="text-[10px] font-black text-base-content/40">Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>
        
        @if(count($this->notifications) > 0)
        <div class="p-2.5 border-t border-base-200 bg-base-200/20 text-center">
            <span class="text-[9px] font-bold text-base-content/30">Menampilkan 10 notifikasi terbaru</span>
        </div>
        @endif
    </div>
</div>
