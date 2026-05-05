<div class="px-0 py-2 sm:py-4 mx-auto max-w-7xl sm:px-0 lg:px-8 text-base-content -mx-5 sm:mx-auto">

    {{-- Breadcrumb --}}
    <div class="mb-3 px-3 sm:px-0 sm:mb-5">
        <div class="flex items-center gap-1 text-xs font-semibold text-base-content/50">
            <a href="{{ route('beranda') }}" wire:navigate class="hover:text-primary transition-colors">Beranda</a>
            <x-icon name="o-chevron-right" class="w-3 h-3" />
            <span class="text-base-content/80 truncate max-w-[220px]">{{ Str::limit($this->pengaduan->judul, 35) }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 sm:gap-6 lg:gap-8">

        {{-- Kolom Kiri: Detil Laporan --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- Card Utama --}}
            <div class="bg-base-100 sm:rounded-2xl sm:shadow-sm border-y sm:border border-base-200 overflow-hidden">
                <div class="py-4 px-3 sm:p-6 sm:px-0 md:p-8 space-y-4 sm:space-y-6">

                    {{-- Header Laporan --}}
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-3 sm:mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="px-2 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg bg-base-200 text-primary font-bold text-[10px] sm:text-xs">
                                    {{ $this->pengaduan->kategori->nama }}
                                </span>

                                @if($this->pengaduan->status == 'menunggu')
                                <x-badge value="Menunggu"
                                    class="badge-warning font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                                @elseif($this->pengaduan->status == 'diproses')
                                <x-badge value="Diproses"
                                    class="badge-info font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                                @elseif($this->pengaduan->status == 'selesai')
                                <x-badge value="Selesai"
                                    class="badge-success font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                                @elseif($this->pengaduan->status == 'ditolak')
                                <x-badge value="Ditolak"
                                    class="badge-error font-bold sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                                @endif

                                <x-badge value="{{ ucfirst($this->pengaduan->prioritas) }}"
                                    class="{{ $this->pengaduan->prioritas == 'tinggi' ? 'badge-error' : ($this->pengaduan->prioritas == 'sedang' ? 'badge-info' : 'badge-success') }} font-black sm:shadow-sm text-[10px] sm:text-xs px-2 py-1 h-auto min-h-0" />
                            </div>
                            
                            <button 
                                onclick="if (navigator.share) { 
                                    navigator.share({ 
                                        title: '{{ addslashes($this->pengaduan->judul) }}', 
                                        text: 'Cek laporan ini di Kembaran Ngadu: {{ addslashes($this->pengaduan->judul) }}', 
                                        url: window.location.href 
                                    }).catch(console.error); 
                                } else { 
                                    window.open('https://wa.me/?text=' + encodeURIComponent('Cek laporan ini di Kembaran Ngadu: *{{ addslashes($this->pengaduan->judul) }}*. Cek di sini: ' + window.location.href), '_blank');
                                }"
                                class="btn btn-ghost btn-circle btn-sm text-base-content/60 hover:text-primary">
                                <x-icon name="o-share" class="w-5 h-5" />
                            </button>
                        </div>

                        <h1
                            class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-black leading-tight text-base-content mb-2 sm:mb-4">
                            {{ $this->pengaduan->judul }}
                        </h1>

                        <div
                            class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[10px] sm:text-xs font-semibold text-base-content/60">
                            
                            <div class="w-full sm:w-auto mb-1 sm:mb-0">
                                <span class="bg-base-200 px-2 py-0.5 rounded font-mono text-base-content/50">{{ $this->pengaduan->kode_tracking }}</span>
                            </div>

                            <div class="flex items-center gap-1 sm:gap-1.5">
                                @if($this->pengaduan->is_anonymous)
                                    <div class="avatar placeholder">
                                        <div class="bg-base-300 text-base-content/50 rounded-full w-5 h-5 sm:w-6 sm:h-6 shadow-sm flex items-center justify-center">
                                            <span class="text-[8px] font-black tracking-tighter">AN</span>
                                        </div>
                                    </div>
                                    <span>Anonim</span>
                                @else
                                    <x-user-avatar :user="$this->pengaduan->user" size="w-5 h-5 sm:w-6 sm:h-6" />
                                    <span>{{ $this->pengaduan->user->name }}</span>
                                @endif
                            </div>
                            <span>&bull;</span>
                            <div class="flex items-center gap-1 sm:gap-1.5"
                                title="Tanggal Kejadian">
                                <x-icon name="o-exclamation-circle" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-primary" />
                                <span>Terjadi: {{ $this->pengaduan->tanggal_kejadian ? $this->pengaduan->tanggal_kejadian->isoFormat('D MMMM YYYY') : '-' }}</span>
                            </div>
                            <span>&bull;</span>
                            <div class="flex items-center gap-1 sm:gap-1.5"
                                title="Waktu Lapor: {{ $this->pengaduan->created_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB">
                                <x-icon name="o-calendar" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                                <span>Dilaporkan: {{ $this->pengaduan->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Tracker - Government Style --}}
                    @php
                        $status = $this->pengaduan->status;
                        $steps = [
                            ['id' => 'terkirim', 'label' => 'Terkirim', 'icon' => 'o-paper-airplane', 'active' => true],
                            ['id' => 'verifikasi', 'label' => 'Verifikasi', 'icon' => 'o-magnifying-glass', 'active' => in_array($status, ['diproses', 'selesai', 'ditolak'])],
                            ['id' => 'proses', 'label' => 'Diproses', 'icon' => 'o-arrow-path', 'active' => in_array($status, ['diproses', 'selesai'])],
                            ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'o-check-badge', 'active' => $status == 'selesai'],
                        ];
                        if($status == 'ditolak') {
                            $steps[2] = ['id' => 'ditolak', 'label' => 'Ditolak', 'icon' => 'o-x-circle', 'active' => true, 'error' => true];
                            unset($steps[3]);
                        }
                    @endphp

                    <div class="py-4 border-y border-base-200/50 mb-6 mt-2">
                        <div class="flex items-center justify-between w-full max-w-xl mx-auto px-2">
                            @foreach($steps as $index => $step)
                                <div class="flex flex-col items-center flex-1 relative">
                                    {{-- Line connecting steps --}}
                                    @if($index < count($steps) - 1)
                                        <div class="absolute left-1/2 top-4 w-full h-[2px] {{ isset($steps[$index+1]) && $steps[$index+1]['active'] ? 'bg-success' : 'bg-base-300' }} -z-0 transition-colors duration-500"></div>
                                    @endif

                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm relative z-10
                                        {{ $step['active'] ? (isset($step['error']) ? 'bg-error text-white' : 'bg-success text-white') : 'bg-base-200 text-base-content/30' }}">
                                        <x-icon name="{{ $step['icon'] }}" class="w-4 h-4" />
                                    </div>
                                    <span class="text-[9px] sm:text-[10px] font-black mt-2 transition-colors
                                        {{ $step['active'] ? (isset($step['error']) ? 'text-error' : 'text-success') : 'text-base-content/40' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($this->pengaduan->foto_bukti && count($this->pengaduan->foto_bukti) > 0)
                    <div class="mt-4 sm:mt-8">
                        <span
                            class="text-[9px] sm:text-[10px] font-black text-base-content/40 mb-2 sm:mb-3 ml-1 hidden sm:block">
                            Lampiran Foto Bukti ({{ count($this->pengaduan->foto_bukti) }})
                        </span>

                        <div class="relative group">
                            {{-- Instagram-style Slide: Snap Carousel --}}
                            <div class="carousel w-full snap-x snap-mandatory overflow-x-auto scroll-smooth sm:rounded-2xl bg-base-200 sm:border border-base-200 no-scrollbar" id="carousel-detail">
                                @foreach($this->pengaduan->foto_bukti as $index => $foto)
                                <div id="detail-slide{{ $index }}" class="carousel-item relative w-full snap-start flex justify-center items-center bg-black/5" style="aspect-ratio: 3/4;">
                                    <img src="{{ Storage::url($foto) }}" 
                                         class="absolute inset-0 w-full h-full object-cover transition duration-500 cursor-zoom-in"
                                         onclick="window.open(this.src, '_blank')">
                                    
                                    {{-- Navigation Overlay --}}
                                    <div class="absolute bottom-4 right-4 bg-black/60 text-white text-[10px] px-2.5 py-1 rounded-full font-bold backdrop-blur-md border border-white/10">
                                        {{ $index + 1 }} / {{ count($this->pengaduan->foto_bukti) }}
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if(count($this->pengaduan->foto_bukti) > 1)
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="document.getElementById('carousel-detail').scrollBy({left: -document.getElementById('carousel-detail').offsetWidth, behavior: 'smooth'})" class="btn btn-circle btn-xs sm:btn-sm bg-white/20 backdrop-blur-md border-none text-white hover:bg-white/40">❮</button>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="document.getElementById('carousel-detail').scrollBy({left: document.getElementById('carousel-detail').offsetWidth, behavior: 'smooth'})" class="btn btn-circle btn-xs sm:btn-sm bg-white/20 backdrop-blur-md border-none text-white hover:bg-white/40">❯</button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Lokasi Kejadian --}}
                    @if($this->pengaduan->lokasi_kejadian)
                    <div
                        class="bg-base-200/50 p-3 sm:p-4 rounded-lg sm:rounded-xl border border-base-200 flex items-start gap-2.5 sm:gap-3">
                        <div class="p-1.5 sm:p-2 bg-error/10 rounded-md sm:rounded-lg text-error shrink-0">
                            <x-icon name="o-map-pin" class="w-4 h-4 sm:w-5 sm:h-5 mt-0.5" />
                        </div>

                        <div class="flex-1 min-w-0">
                                <span class="text-[9px] sm:text-[10px] font-black text-base-content/40 mb-0.5">
                                    Lokasi Terkait
                                </span>

                            <p class="text-[13px] sm:text-sm font-bold text-base-content leading-tight line-clamp-2">
                                {{ $this->pengaduan->lokasi_kejadian }}
                            </p>

                            @if($this->pengaduan->latitude)
                            <a href="https://maps.google.com/?q={{ $this->pengaduan->latitude }},{{ $this->pengaduan->longitude }}"
                                target="_blank"
                                class="text-primary hover:underline text-[11px] sm:text-xs mt-1 inline-block font-semibold">
                                Buka di Peta
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div
                        class="text-[13px] sm:text-base leading-relaxed text-base-content/90 font-medium whitespace-pre-line break-words overflow-hidden">
                        {{ $this->pengaduan->deskripsi }}
                    </div>

                    @if($this->pengaduan->harapan_pelapor)
                    <div class="p-4 bg-primary/5 border-l-4 border-primary rounded-r-xl mt-4 break-words overflow-hidden">
                        <p class="text-[10px] font-black text-primary mb-1">Harapan Pelapor:</p>
                        <p class="text-sm font-bold text-base-content/80 italic">"{{ $this->pengaduan->harapan_pelapor }}"</p>
                    </div>
                    @endif

                    {{-- Bukti Penyelesaian / Tindak Lanjut Instansi --}}
                    @if($this->pengaduan->status === 'selesai')
                    <div class="mt-6 border border-success/30 bg-success/5 rounded-2xl p-4 sm:p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center shadow-sm">
                                    <x-icon name="o-check-badge" class="w-5 h-5" />
                                </div>
                                <h3 class="text-sm sm:text-base font-black text-success">Bukti Tindak Lanjut Instansi</h3>
                            </div>
                            <span class="text-[10px] font-black uppercase text-success/60">{{ $this->pengaduan->updated_at->isoFormat('D MMMM YYYY') }}</span>
                        </div>

                        @if($this->pengaduan->foto_penyelesaian)
                        <div class="mb-4">
                            <img src="{{ Storage::url($this->pengaduan->foto_penyelesaian) }}" 
                                 class="w-full rounded-xl object-cover shadow-sm border border-success/20 cursor-zoom-in hover:shadow-md transition"
                                 style="max-height: 400px;"
                                 onclick="window.open(this.src, '_blank')">
                        </div>
                        @endif

                        @if($this->pengaduan->pesan_penutup)
                        <div class="bg-base-100 rounded-xl p-3 sm:p-4 border border-base-200 shadow-sm relative">
                            <x-icon name="o-chat-bubble-left-ellipsis" class="w-6 h-6 text-success/20 absolute top-3 right-3" />
                            <p class="text-xs font-bold text-base-content/50 mb-1">Pesan dari Admin/Instansi:</p>
                            <p class="text-sm text-base-content/90 font-medium whitespace-pre-line break-words overflow-hidden">{{ $this->pengaduan->pesan_penutup }}</p>
                        </div>
                        @endif

                        {{-- Feedback Section --}}
                        <div class="mt-6 pt-6 border-t border-success/20">
                            @if($showFeedbackForm)
                                <div class="bg-white/50 dark:bg-base-300/50 rounded-xl p-4 border border-dashed border-success/40">
                                    <h4 class="text-sm font-black text-base-content mb-3 flex items-center gap-2">
                                        <x-icon name="o-star" class="w-4 h-4 text-warning" />
                                        Bagaimana pelayanan kami?
                                    </h4>
                                    
                                    <form wire:submit="submitFeedback" class="space-y-4">
                                        <div class="flex flex-col gap-2">
                                            <div class="rating rating-lg">
                                                @foreach(range(1, 5) as $i)
                                                    <input type="radio" wire:model="rating" value="{{ $i }}" class="mask mask-star-2 bg-warning" {{ $rating == $i ? 'checked' : '' }} />
                                                @endforeach
                                            </div>
                                            <div class="flex justify-between px-1 text-[10px] font-bold text-base-content/40 uppercase tracking-tighter">
                                                <span>Buruk</span>
                                                <span>Sangat Puas</span>
                                            </div>
                                        </div>

                                        <x-textarea 
                                            wire:model="rating_komentar" 
                                            placeholder="Beri komentar atau saran untuk pelayanan kami..."
                                            rows="2"
                                            class="bg-base-100 border-base-200 focus:border-success/50" />

                                        <div class="flex justify-end">
                                            <x-button label="Kirim Feedback" type="submit" class="btn-success btn-sm text-white font-black" spinner="submitFeedback" />
                                        </div>
                                    </form>
                                </div>
                            @elseif($this->pengaduan->rating)
                                <div class="flex flex-col sm:flex-row items-center gap-4 bg-success/10 rounded-xl p-4 border border-success/20">
                                    <div class="flex flex-col items-center shrink-0">
                                        <div class="flex items-center gap-1 mb-1">
                                            @foreach(range(1, 5) as $i)
                                                <x-icon name="o-star" class="w-4 h-4 {{ $i <= $this->pengaduan->rating ? 'text-warning fill-warning' : 'text-base-300' }}" />
                                            @endforeach
                                        </div>
                                        <span class="text-[10px] font-black uppercase text-success/60">Rating Anda</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if($this->pengaduan->rating_komentar)
                                            <p class="text-xs italic text-base-content/70 font-medium leading-relaxed break-words overflow-hidden">
                                                "{{ $this->pengaduan->rating_komentar }}"
                                            </p>
                                        @else
                                            <p class="text-xs font-bold text-success/70 italic">Terima kasih atas penilaian Anda!</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Komentar --}}
                    @livewire('pengaduan-komentar', ['pengaduan_id' => $this->pengaduan->id])

                </div>
            </div>

        </div>

        <div class="space-y-4 sm:space-y-6 px-0">

            <div
                class="bg-base-100 sm:rounded-2xl sm:shadow-sm border-y sm:border border-base-200 overflow-hidden sticky top-6">
                <div class="px-3 sm:px-4 py-3 sm:py-4 bg-base-200/30 border-b border-base-200 flex items-center gap-2">
                    <x-icon name="o-clock" class="w-4 h-4 text-primary" />
                    <h2 class="font-bold text-base-content/80 text-xs">Riwayat Laporan</h2>
                </div>

                <div class="px-3 py-4 sm:p-5">

                    @if($this->pengaduan->histories->count() > 0)
                    <div class="space-y-3">
                        @foreach($this->pengaduan->histories->sortByDesc('created_at') as $index => $history)
                        @php
                        $isLatest = $index === 0;
                        $bgMap = [
                            'menunggu' => 'bg-warning/10 border-warning/30',
                            'diproses' => 'bg-info/10 border-info/30',
                            'selesai'  => 'bg-success/10 border-success/30',
                            'ditolak'  => 'bg-error/10 border-error/30',
                        ];
                        $dotMap = [
                            'menunggu' => 'bg-warning',
                            'diproses' => 'bg-info',
                            'selesai'  => 'bg-success',
                            'ditolak'  => 'bg-error',
                        ];
                        $textMap = [
                            'menunggu' => 'text-warning',
                            'diproses' => 'text-info',
                            'selesai'  => 'text-success',
                            'ditolak'  => 'text-error',
                        ];
                        $labelMap = [
                            'menunggu' => 'Menunggu',
                            'diproses' => 'Sedang Diproses',
                            'selesai'  => 'Selesai',
                            'ditolak'  => 'Ditolak',
                        ];
                        $cardBg = $bgMap[$history->status_baru] ?? 'bg-base-200/50 border-base-300';
                        $dot = $dotMap[$history->status_baru] ?? 'bg-base-300';
                        $textColor = $textMap[$history->status_baru] ?? 'text-base-content';
                        $label = $labelMap[$history->status_baru] ?? ucfirst($history->status_baru);
                        @endphp

                        <div class="rounded-xl border p-3 {{ $cardBg }} {{ $isLatest ? 'ring-1 ring-current/20' : 'opacity-80' }}">
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $dot }} shrink-0 mt-0.5"></div>
                                    <span class="font-black text-[11px] sm:text-xs {{ $textColor }}">{{ $label }}</span>
                                    @if($isLatest)
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-base-100/80 text-base-content/60">Terbaru</span>
                                    @endif
                                </div>
                                <span class="text-[9px] sm:text-[10px] text-base-content/40 font-semibold shrink-0">{{ $history->created_at->diffForHumans() }}</span>
                            </div>

                            @if($history->user)
                            <div class="text-[10px] text-base-content/50 mb-1.5 flex items-center gap-1">
                                <x-icon name="o-user" class="w-3 h-3" />
                                {{ $history->user->name }}
                                <span class="px-1 py-0.5 rounded text-[9px] font-black bg-base-100/60">{{ ucfirst($history->user->role) }}</span>
                            </div>
                            @endif

                            @if($history->keterangan_admin)
                            <p class="text-[11px] sm:text-xs leading-relaxed text-base-content/80 italic mt-2 pl-2 border-l-2 border-current/30 break-words overflow-hidden line-clamp-3">
                                "{{ Str::limit($history->keterangan_admin, 150) }}"
                            </p>
                            @endif

                            @if($history->foto_bukti)
                            <div class="mt-2 cursor-zoom-in"
                                onclick="window.open('{{ Storage::url($history->foto_bukti) }}', '_blank')">
                                <img src="{{ Storage::url($history->foto_bukti) }}"
                                    alt="Foto Bukti"
                                    class="w-full h-28 object-cover rounded-lg border border-base-200 shadow-sm hover:brightness-105 transition">
                                <p class="text-[9px] text-base-content/40 mt-1 text-center">Ketuk untuk perbesar</p>
                            </div>
                            @endif
                        </div>

                        @endforeach

                        {{-- Base: Laporan Dibuat --}}
                        <div class="rounded-xl border border-base-200 p-3 bg-base-200/30 opacity-60">
                            <div class="flex items-center gap-2 mb-0.5">
                                <div class="w-2 h-2 rounded-full bg-base-400 shrink-0"></div>
                                <span class="font-black text-[11px] text-base-content/60">Laporan Dikirim</span>
                            </div>
                            <div class="text-[10px] text-base-content/40 font-semibold">
                                {{ $this->pengaduan->created_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 text-base-content/40">
                        <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center mx-auto mb-3">
                            <x-icon name="o-clock" class="w-6 h-6 opacity-40" />
                        </div>
                        <p class="text-xs font-bold">Belum ada pembaruan status.</p>
                        <p class="text-[10px] mt-1">Kami akan segera menindaklanjuti laporan Anda.</p>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <div class="relative w-[100vw] left-1/2 -ml-[50vw] mt-16">
        <x-footer />
    </div>
</div>