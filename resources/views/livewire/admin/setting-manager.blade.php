<div class="px-2 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 text-base-content">

    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Pengaturan Web</h1>
            <p class="mt-1 text-sm text-base-content/70">Sesuaikan informasi standar pelayanan publik (SOP) yang tampil di Beranda.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
            {{ session('success') }}
        </x-alert>
    @endif

    {{-- Chip Navigation --}}
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <button wire:click="setTab('umum')" 
            class="btn btn-sm rounded-full px-5 transition-all {{ $activeTab === 'umum' ? 'btn-primary text-white shadow-md' : 'btn-ghost bg-base-200/50 hover:bg-base-200' }}">
            <x-icon name="o-cog-6-tooth" class="w-4 h-4" /> Umum
        </button>
        <button wire:click="setTab('ttd')" 
            class="btn btn-sm rounded-full px-5 transition-all {{ $activeTab === 'ttd' ? 'btn-primary text-white shadow-md' : 'btn-ghost bg-base-200/50 hover:bg-base-200' }}">
            <x-icon name="o-pencil-square" class="w-4 h-4" /> Tanda Tangan
        </button>
        <button wire:click="setTab('konten')" 
            class="btn btn-sm rounded-full px-5 transition-all {{ $activeTab === 'konten' ? 'btn-primary text-white shadow-md' : 'btn-ghost bg-base-200/50 hover:bg-base-200' }}">
            <x-icon name="o-document-text" class="w-4 h-4" /> Konten Web
        </button>
        <button wire:click="setTab('aset')" 
            class="btn btn-sm rounded-full px-5 transition-all {{ $activeTab === 'aset' ? 'btn-primary text-white shadow-md' : 'btn-ghost bg-base-200/50 hover:bg-base-200' }}">
            <x-icon name="o-photo" class="w-4 h-4" /> Aset Visual
        </button>
    </div>

    <div class="pb-4 border shadow-sm bg-base-100 rounded-2xl border-base-200 p-6 md:p-8">
        <x-form wire:submit="saveSettings">
            
            {{-- Tab Umum: SOP --}}
            <div class="{{ $activeTab === 'umum' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-bold mb-4 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-book-open" class="w-5 h-5 text-primary" /> Standar Pelayanan Publik (SOP)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-textarea 
                        label="Waktu Pemrosesan" 
                        wire:model="sop_waktu_pemrosesan" 
                        rows="3" 
                        placeholder="Contoh: Laporan akan diverifikasi maksimal 3x24 Jam Kerja..." 
                        hint="Anda dapat menggunakan tag HTML dasar seperti <b> atau <br>." />

                    <x-textarea 
                        label="Jam Operasional" 
                        wire:model="sop_jam_operasional" 
                        rows="3" 
                        placeholder="Contoh: Senin - Jumat Pukul 08:00 - 15:00 WIB." 
                        hint="Gunakan <br> untuk baris baru jika diperlukan." />

                    <x-textarea 
                        label="Dasar Hukum & Privasi" 
                        wire:model="sop_dasar_hukum" 
                        rows="3" 
                        placeholder="Sesuai UU Pelayanan Publik dan UU PDP..." />

                    <x-textarea 
                        label="Tindak Lanjut" 
                        wire:model="sop_tindak_lanjut" 
                        rows="3" 
                        placeholder="Laporan yang valid akan diteruskan..." />
                </div>

                <h2 class="text-xl font-bold mb-4 mt-8 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-shield-check" class="w-5 h-5 text-primary" /> Keamanan & Pemeliharaan Sistem
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-base-200/30 p-4 rounded-xl border border-base-200">
                    <div class="space-y-4">
                        <x-toggle label="Aktifkan Anti-Spam" wire:model="anti_spam_aktif" hint="Batasi jumlah laporan per user dalam 24 jam." />
                        <x-input label="Batas Laporan Per Hari" type="number" wire:model="anti_spam_limit" />
                    </div>
                    <div class="space-y-4 border-l border-base-200 pl-6">
                        <x-toggle label="Otomatis Hapus Foto Lama" wire:model="media_cleanup_aktif" hint="Hapus foto laporan yang sudah sangat lama." />
                        <x-input label="Hapus Foto Jika Lebih Dari (Bulan)" type="number" wire:model="media_cleanup_bulan" hint="Contoh: 24 (2 Tahun)." />
                    </div>
                </div>
            </div>

            {{-- Tab Tanda Tangan --}}
            <div class="{{ $activeTab === 'ttd' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-bold mb-4 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-pencil-square" class="w-5 h-5 text-primary" /> Tanda Tangan Cetak PDF
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Jabatan Pejabat" wire:model="ttd_jabatan" placeholder="Contoh: Camat Kembaran" required icon="o-identification" />
                    <x-input label="Nama Lengkap Pejabat" wire:model="ttd_nama" placeholder="Contoh: Drs. H. Ahmad, M.Si." required icon="o-user" />
                    
                    <div class="md:col-span-2">
                        <x-file label="File Tanda Tangan (Format PNG transparan disarankan)" wire:model="ttd_file" accept="image/png, image/jpeg" hint="Maksimal ukuran file 2MB." />
                        @if ($existing_ttd_file)
                            <div class="mt-4 flex items-center gap-4">
                                <span class="text-sm font-bold">Tanda Tangan Saat Ini:</span>
                                <img src="{{ asset('storage/' . $existing_ttd_file) }}" alt="Tanda Tangan" class="h-16 object-contain border border-base-200 rounded p-1 bg-white">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tab Konten Web --}}
            <div class="{{ $activeTab === 'konten' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-bold mb-4 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-building-office" class="w-5 h-5 text-primary" /> Profil & Konten Tentang Kami
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <x-input label="Nama Instansi" wire:model="instansi_nama" placeholder="Contoh: Kantor Kecamatan Kembaran" required icon="o-building-library" />
                    </div>
                    <div class="md:col-span-2">
                        <x-textarea label="Alamat Instansi" wire:model="instansi_alamat" placeholder="Alamat lengkap..." rows="2" required icon="o-map-pin" />
                    </div>
                    <x-input label="Nomor Telepon" wire:model="instansi_telepon" placeholder="Contoh: (0281) 684XXX" required icon="o-phone" />
                    <x-input label="Email Instansi" wire:model="instansi_email" placeholder="Contoh: kec@banyumaskab.go.id" required icon="o-envelope" />
                </div>

                <h2 class="text-lg font-bold mb-4 flex items-center gap-2 text-base-content/70">
                    <x-icon name="o-clock" class="w-5 h-5" /> Jam Kerja (Tampil di Tentang Kami)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-input label="Senin - Kamis" wire:model="instansi_jam_senkam" placeholder="07.30 - 16.00 WIB" required />
                    <x-input label="Jumat" wire:model="instansi_jam_jumat" placeholder="07.30 - 11.00 WIB" required />
                    <x-input label="Sabtu - Minggu" wire:model="instansi_jam_sabtu" placeholder="Libur" required />
                </div>
            </div>

            {{-- Tab Aset Visual --}}
            <div class="{{ $activeTab === 'aset' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-bold mb-4 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-photo" class="w-5 h-5 text-primary" /> Logo & Banner Website
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <x-file label="Logo Instansi / App" wire:model="app_logo" accept="image/*" hint="Disarankan format PNG transparan (Square/1:1)." />
                        <div class="p-4 bg-base-200/30 rounded-xl border border-base-200">
                            <p class="text-[10px] font-bold text-base-content/40 uppercase mb-3">Preview Logo Saat Ini</p>
                            <div class="flex items-center justify-center bg-white rounded-lg p-4 h-32 border border-base-200">
                                @if($app_logo)
                                    <img src="{{ $app_logo->temporaryUrl() }}" class="max-h-full object-contain">
                                @elseif($existing_app_logo)
                                    <img src="{{ asset('storage/' . $existing_app_logo) }}" class="max-h-full object-contain">
                                @else
                                    <div class="text-xs italic opacity-30">Belum ada logo</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <x-file label="Hero Banner (Beranda)" wire:model="app_banner" accept="image/*" hint="Disarankan resolusi tinggi (1920x1080)." />
                        <div class="p-4 bg-base-200/30 rounded-xl border border-base-200">
                            <p class="text-[10px] font-bold text-base-content/40 uppercase mb-3">Preview Banner Saat Ini</p>
                            <div class="flex items-center justify-center bg-black/5 rounded-lg overflow-hidden h-32 border border-base-200">
                                @if($app_banner)
                                    <img src="{{ $app_banner->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($existing_app_banner)
                                    <img src="{{ asset('storage/' . $existing_app_banner) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-xs italic opacity-30">Belum ada banner</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Simpan Pengaturan" type="submit" icon="o-check-circle" class="btn-primary text-white"
                    spinner="saveSettings" />
            </x-slot:actions>
        </x-form>
    </div>
</div>
