<div class="w-full lg:px-0">
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            transition: background-color 5000s ease-in-out 0s !important;
            -webkit-text-fill-color: #ffffff !important;
            color: #ffffff !important;
            font-weight: 500 !important;
        }

        .banner-paksa-atas {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            /* Tarik ke atas melampaui padding x-main (sesuaikan angka ini) */
            margin-top: -5rem !important;
        }

        @media (min-width: 1024px) {
            .banner-paksa-atas {
                margin-top: -6.5rem !important;
            }
        }
    </style>

    {{-- Banner Wrapper --}}
    <div class="overflow-hidden mb-0 banner-paksa-atas">
        <div class="absolute inset-0 z-0">
            <img class="absolute inset-0 opacity-40 w-full h-full object-cover"
                src="{{ asset('storage/assets/banner.jpg') }}">
        </div>

        {{-- Padding Top (pt) di sini diperbesar agar Logo & Tulisan turun dari bawah Navbar --}}
        <div
            class="relative z-10 flex flex-col items-center justify-center text-center pt-32 pb-16 md:pt-44 md:pb-24 lg:pt-52 lg:pb-32 px-6 max-w-7xl mx-auto">

            <img src="{{ asset('storage/assets/logobanyumas.png') }}"
                class="w-20 md:w-28 lg:w-32 h-auto mb-6 drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)]" alt="Logo" />

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-semibold text-white mb-4 tracking-tight drop-shadow-2xl">
                Selamat Datang
            </h1>

            <p
                class="text-sm md:text-lg lg:text-xl text-white/80 mb-10 max-w-2xl leading-relaxed font-medium drop-shadow-md">
                Layanan Pengaduan Masyarakat Kecamatan Kembaran.<br class="hidden sm:block" />
                Silakan klik tombol di bawah untuk mulai melaporkan masalah maupun aspirasi Anda.
            </p>

            {{-- Tombol Auth Tetap Sama --}}
            @auth
            <a href="/pengaduan/create" wire:navigate
                class="btn border-none text-white shadow-2xl bg-[#0085FF] hover:bg-white hover:text-[#0085FF] hover:-translate-y-1 px-10 md:px-14 py-4 rounded-full font-black text-sm md:text-lg transition-all duration-300">
                Mulai Pengaduan
            </a>
            @else
            <a href="/login" wire:navigate
                class="btn border-none text-white shadow-2xl bg-[#0085FF] hover:bg-white hover:text-[#0085FF] hover:-translate-y-1 px-10 md:px-14 py-4 rounded-full font-black text-sm md:text-lg transition-all duration-300">
                Mulai Pengaduan
            </a>
            @endauth
        </div>
    </div>

    <div class="w-full px-0.1 py-6 mx-auto lg:px-4">


        @php
        $sortOptions = [
        ['id' => 'terbaru', 'nama' => 'Terbaru'],
        ['id' => 'terpopuler', 'nama' => 'Populer'],
        ];
        @endphp

        {{-- Search & Filter --}}
        <form wire:submit.prevent class="p-0.1 mb-7 lg:p-4 shadow-sm rounded-2xl">
            <div class="flex flex-row items-center w-full gap-1.5 lg:gap-4">
                <div class="flex-1">
                    <x-input placeholder="Cari..." wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                        clearable />
                </div>
                <div class="w-28 sm:w-40 lg:w-48 shrink-0">
                    <x-select wire:model.live="kategori_id" :options="$kategoris" option-value="id" option-label="nama"
                        placeholder="Semua Kategori" icon="o-tag" />
                </div>
                <div class="w-24 sm:w-36 lg:w-40 shrink-0">
                    <x-select wire:model.live="sort" :options="$sortOptions" option-value="id" option-label="nama"
                        icon="o-arrows-up-down" />
                </div>
            </div>
        </form>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-3">
            @forelse($pengaduans as $pengaduan)
            <div
                class="flex flex-col overflow-hidden transition-all duration-300 transform border shadow-sm bg-base-100 rounded-2xl border-base-200 hover:shadow-lg hover:border-primary/50 hover:-translate-y-1 group">

                {{-- Klik Gambar ke Detail --}}
                @if($pengaduan->foto_bukti)
                <a href="{{ route('pengaduan.feed-detail', $pengaduan->id) }}" wire:navigate
                    class="relative w-full overflow-hidden bg-base-200 aspect-video cursor-pointer">
                    <img src="{{ Storage::url($pengaduan->foto_bukti) }}" alt="Bukti {{ $pengaduan->judul }}"
                        class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 group-hover:scale-110" />

                    <div class="absolute top-3 right-3 shadow-sm">
                        <span class="px-3 py-1 text-xs font-bold rounded-full 
                            {{ $pengaduan->status == 'menunggu' ? 'bg-warning text-warning-content' : '' }}
                            {{ $pengaduan->status == 'diproses' ? 'bg-info text-info-content' : '' }}
                            {{ $pengaduan->status == 'selesai' ? 'bg-success text-success-content' : '' }}
                            {{ $pengaduan->status == 'ditolak' ? 'bg-error text-error-content' : '' }} shadow-sm">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>
                </a>
                @endif

                <div class="flex flex-col flex-1 p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3 text-xs font-medium text-base-content/60">
                        {{-- Tambahkan 'truncate' dan 'max-w' agar tidak dorong-dorongan sama waktu --}}
                        <span class="flex items-center gap-1.5 px-1.5 py-1 rounded-md bg-base-200 text-primary min-w-0"
                            title="{{ $pengaduan->kategori->nama }}">
                            <x-icon name="{{ $pengaduan->kategori->icon ?? 'o-tag' }}" class="w-3.5 h-3.5 shrink-0" />
                            <span class="truncate">{{ $pengaduan->kategori->nama }}</span>
                        </span>

                        <span class="flex items-center gap-1 shrink-0 ml-2">
                            <x-icon name="o-clock" class="w-3.5 h-3.5" /> {{ $pengaduan->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Klik Judul ke Detail --}}
                    <h2
                        class="mb-1 text-base font-bold leading-tight transition-colors sm:text-lg text-base-content hover:text-primary line-clamp-2">
                        <a href="{{ route('pengaduan.feed-detail', $pengaduan->id) }}" wire:navigate>
                            {{ $pengaduan->judul }}
                        </a>
                    </h2>

                    <div class="flex items-start gap-1 mb-3 text-xs text-base-content/50">
                        <x-icon name="o-map-pin" class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-error/80" />
                        <span class="line-clamp-1">{{ $pengaduan->lokasi_kejadian ?? 'Lokasi via koordinat peta'
                            }}</span>
                    </div>

                    <p class="flex-1 mb-4 text-sm leading-relaxed text-base-content/70 line-clamp-3">
                        {{ $pengaduan->deskripsi }}
                    </p>

                    <div class="flex items-center justify-between pt-4 mt-auto border-t border-base-200">
                        <div class="flex items-center gap-2 text-sm font-medium text-base-content/80">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-base-300">
                                <x-icon name="o-user" class="w-4 h-4 opacity-70" />
                            </div>
                            <span class="line-clamp-1 max-w-[100px]">{{ $pengaduan->is_anonymous ? 'Anonim' :
                                $pengaduan->user->name }}</span>
                        </div>

                        {{-- Action Buttons (Gak ikutan link detail biar upvote tetep jalan) --}}
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                @if(session()->has('success') && session('id') == $pengaduan->id)
                                <span
                                    class="absolute right-0 bottom-full mb-1 whitespace-nowrap text-[10px] font-bold animate-bounce text-success">Berhasil!</span>
                                @endif
                                <button wire:click="upvote({{ $pengaduan->id }})"
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all duration-300 group/vote
                                    {{ session()->has('success') && session('id') == $pengaduan->id ? 'bg-primary text-white shadow-md scale-105' : 'bg-primary/10 text-primary ' }}">
                                    <x-icon name="o-hand-thumb-up" class="w-6 h-6" />
                                    <span class="text-sm font-black">{{ $pengaduan->dukungans_count }}</span>
                                </button>
                            </div>

                            @php
                            $waText = urlencode("Bantu dukung laporan ini di Kembaran Ngadu: *" . $pengaduan->judul .
                            "*. Cek di sini: " . route('pengaduan.feed-detail', $pengaduan->id));
                            @endphp
                            <a href="https://wa.me/?text={{ $waText }}" target="_blank"
                                class="flex items-center justify-center w-9 h-9 transition-all duration-200 rounded-xl text-base-content/70 hover:bg-success hover:text-white hover:rotate-12">
                                <x-icon name="o-share" class="w-5 h-5" />
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Status bar untuk yang gapunya foto --}}
                @if(!$pengaduan->foto_bukti)
                <div class="px-4 pb-4 sm:px-5">
                    <a href="{{ route('pengaduan.feed-detail', $pengaduan->id) }}" wire:navigate class="block w-full py-1.5 text-xs text-center font-bold rounded-lg hover:brightness-95 transition-all
                        {{ $pengaduan->status == 'menunggu' ? 'bg-warning/20 text-warning' : '' }}
                        {{ $pengaduan->status == 'diproses' ? 'bg-info/20 text-info' : '' }}
                        {{ $pengaduan->status == 'selesai' ? 'bg-success/20 text-success' : '' }}
                        {{ $pengaduan->status == 'ditolak' ? 'bg-error/20 text-error' : '' }}">
                        {{ ucfirst($pengaduan->status) }}
                    </a>
                </div>
                @endif
            </div>
            @empty
            {{-- Empty State --}}
            <div
                class="flex flex-col items-center justify-center py-20 text-center border-2 border-dashed lg:col-span-3 xl:col-span-4 2xl:col-span-3 bg-base-200/50 rounded-[1.5rem] border-base-300">
                <x-icon name="o-inbox" class="w-10 h-10 text-base-content/40 mb-4" />
                <h3 class="text-lg font-bold text-base-content/70">Belum ada laporan pengaduan</h3>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $pengaduans->links() }}
        </div>
    </div>

    {{-- Footer --}}
    <div class="relative w-[100vw] left-1/2 -ml-[50vw] mt-16">
        <x-footer />
    </div>
</div>