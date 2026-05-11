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
        <button wire:click="setTab('email')" 
            class="btn btn-sm rounded-full px-5 transition-all {{ $activeTab === 'email' ? 'btn-primary text-white shadow-md' : 'btn-ghost bg-base-200/50 hover:bg-base-200' }}">
            <x-icon name="o-envelope" class="w-4 h-4" /> Pengaturan Email
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
                    <x-icon name="o-megaphone" class="w-5 h-5 text-primary" /> Papan Informasi (Pengumuman Beranda)
                </h2>
                <div class="bg-base-200/30 p-5 rounded-2xl border border-base-200 space-y-6">
                    <div class="flex items-center justify-between bg-base-100 p-4 rounded-xl border border-base-200 shadow-sm">
                        <div class="flex-1">
                            <p class="font-bold text-sm">Status Pengumuman</p>
                            <p class="text-[10px] text-base-content/60">Aktifkan untuk memunculkan banner di Beranda</p>
                        </div>
                        <x-toggle wire:model="pengumuman_aktif" class="toggle-primary" />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <x-textarea 
                                label="Isi Pengumuman" 
                                wire:model="pengumuman_isi" 
                                rows="3" 
                                placeholder="Masukkan pengumuman di sini..." 
                                hint="Anda dapat menggunakan tag HTML dasar." />
                        </div>
                        <div>
                            <x-select 
                                label="Tipe Tampilan" 
                                wire:model="pengumuman_tipe" 
                                :options="[
                                    ['id' => 'info', 'name' => 'Info (Biru)'],
                                    ['id' => 'success', 'name' => 'Sukses (Hijau)'],
                                    ['id' => 'warning', 'name' => 'Peringatan (Kuning)'],
                                    ['id' => 'error', 'name' => 'Bahaya (Merah)']
                                ]" 
                                icon="o-swatch" />
                        </div>
                    </div>
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
                        <x-input label="Hapus Foto Jika Lebih Dari (Bulan)" type="number" wire:model="media_cleanup_bulan" hint="Contoh: 24 (2 Tahun)." />
                    </div>
                </div>

                <h2 class="text-xl font-bold mb-4 mt-8 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-wrench-screwdriver" class="w-5 h-5 text-primary" /> Pemeliharaan & Perawatan
                </h2>
                <div class="bg-base-200/30 p-5 rounded-2xl border border-base-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <p class="font-bold text-sm">Bersihkan Cache Sistem</p>
                            <p class="text-[10px] text-base-content/60">Hapus cache konfigurasi, rute, dan view untuk menyegarkan sistem.</p>
                        </div>
                        <x-button label="Bersihkan Seluruh Cache" icon="o-trash" 
                            class="btn-outline btn-sm rounded-xl" 
                            wire:click="clearCache" spinner="clearCache"
                            wire:confirm="Bersihkan seluruh cache sistem? Ini mungkin akan membuat loading pertama sedikit lebih lambat." />
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
                            <div class="mt-4 flex items-center gap-4 p-4 bg-base-200/50 rounded-xl border border-base-200">
                                <div class="flex-1">
                                    <p class="text-xs font-bold mb-2">Tanda Tangan Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $existing_ttd_file) }}" alt="Tanda Tangan" class="h-16 object-contain border border-base-200 rounded p-1 bg-white">
                                </div>
                                <x-button label="Hapus" icon="o-trash" wire:click="deleteSignature" class="btn-error btn-sm text-white rounded-lg" 
                                    wire:confirm="Hapus tanda tangan ini?" />
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                    {{-- Logo Section --}}
                    <div class="space-y-6">
                        <div class="space-y-4 p-4 bg-base-200/20 rounded-2xl border border-base-200">
                            <x-file label="Logo Utama (Instansi)" wire:model="app_logo" accept="image/*" hint="Disarankan format PNG transparan." />
                            <div class="flex items-center justify-center bg-white rounded-xl p-4 h-32 border border-base-200 shadow-inner">
                                @if($app_logo)
                                    <img src="{{ $app_logo->temporaryUrl() }}" class="max-h-full object-contain">
                                @elseif($existing_app_logo)
                                    <img src="{{ asset('storage/' . $existing_app_logo) }}" class="max-h-full object-contain">
                                @else
                                    <div class="text-xs italic opacity-30">Belum ada logo</div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4 p-4 bg-base-200/20 rounded-2xl border border-base-200">
                            <x-file label="Logo Sekunder (Pendamping)" wire:model="app_logo_sekunder" accept="image/*" hint="Contoh: Logo Kominfo." />
                            <div class="flex items-center justify-center bg-white rounded-xl p-4 h-32 border border-base-200 shadow-inner">
                                @if($app_logo_sekunder)
                                    <img src="{{ $app_logo_sekunder->temporaryUrl() }}" class="max-h-full object-contain">
                                @elseif($existing_app_logo_sekunder)
                                    <img src="{{ asset('storage/' . $existing_app_logo_sekunder) }}" class="max-h-full object-contain">
                                @else
                                    <div class="text-xs italic opacity-30">Belum ada logo sekunder</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Banners Section --}}
                    <div class="space-y-6">
                        @foreach(range(1, 3) as $i)
                        @php $prop = 'app_banner_' . $i; $exist = 'existing_app_banner_' . $i; @endphp
                        <div class="space-y-4 p-4 bg-base-200/20 rounded-2xl border border-base-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="label-text font-bold">Hero Banner #{{ $i }}</label>
                                @if($this->{$exist})
                                    <x-button icon="o-trash" wire:click="deleteBanner({{ $i }})" class="btn-xs btn-error text-white" 
                                        wire:confirm="Hapus banner {{ $i }}?" />
                                @endif
                            </div>
                            <x-file wire:model="{{ $prop }}" accept="image/*" hint="Resolusi tinggi (1920x1080)." />
                            <div class="flex items-center justify-center bg-black/5 rounded-xl overflow-hidden h-32 border border-base-200 shadow-inner">
                                @if($this->{$prop})
                                    <img src="{{ $this->{$prop}->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($this->{$exist})
                                    <img src="{{ asset('storage/' . $this->{$exist}) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-xs italic opacity-30">Banner {{ $i }} Kosong</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tab Pengaturan Email --}}
            <div class="{{ $activeTab === 'email' ? 'block' : 'hidden' }}">
                <h2 class="text-xl font-bold mb-4 border-b border-base-200 pb-2 flex items-center gap-2">
                    <x-icon name="o-envelope" class="w-5 h-5 text-primary" /> Konfigurasi SMTP Email
                </h2>

                <div class="flex flex-col lg:flex-row gap-6 mb-6">
                    <div class="flex-1 p-4 bg-info/10 border border-info/20 rounded-xl flex gap-3 items-start">
                        <x-icon name="o-information-circle" class="w-6 h-6 text-info mt-1" />
                        <div class="text-sm">
                            <p class="font-bold text-info">Penting!</p>
                            <p class="text-base-content/70">Pengaturan ini akan menimpa konfigurasi di file .env. Pastikan data benar agar sistem dapat mengirim email verifikasi.</p>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-warning/10 border border-warning/20 rounded-xl flex items-center gap-4">
                        <div class="flex-1">
                            <p class="text-xs font-black text-warning uppercase">Keamanan Konfigurasi</p>
                            <p class="text-[10px] text-base-content/60">{{ $unlock_email ? 'Mode Edit Aktif' : 'Terproteksi - Klik Gembok' }}</p>
                        </div>
                        <button type="button" wire:click="openUnlockModal" 
                            class="btn btn-circle btn-sm {{ $unlock_email ? 'btn-error' : 'btn-ghost bg-base-200' }} shadow-sm transition-all duration-300">
                            <x-icon name="{{ $unlock_email ? 'o-lock-open' : 'o-lock-closed' }}" class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ !$unlock_email ? 'opacity-50 pointer-events-none' : '' }}">
                    <x-input label="SMTP Host" wire:model="mail_host" placeholder="smtp.gmail.com" icon="o-server" :disabled="!$unlock_email" />
                    <x-input label="SMTP Port" wire:model="mail_port" type="number" placeholder="587" icon="o-hashtag" :disabled="!$unlock_email" />
                    <x-input label="Email / Username" wire:model="mail_username" placeholder="instansi@gmail.com" icon="o-user" :disabled="!$unlock_email" />
                    <x-input label="App Password" wire:model="mail_password" type="password" placeholder="Token app password" icon="o-key" :disabled="!$unlock_email" />
                    <x-input label="Mail From Name" wire:model="mail_from_name" placeholder="Nama Instansi Anda" icon="o-identification" :disabled="!$unlock_email" />
                    
                    <div>
                        <x-select 
                            label="Enkripsi" 
                            wire:model="mail_encryption" 
                            :options="[
                                ['id' => 'tls', 'name' => 'TLS (Port 587)'],
                                ['id' => 'ssl', 'name' => 'SSL (Port 465)'],
                                ['id' => 'none', 'name' => 'Tanpa Enkripsi']
                            ]"
                            icon="o-lock-closed"
                            :disabled="!$unlock_email" />
                    </div>
                    
                    @if($unlock_email)
                        <div class="md:col-span-2 flex justify-end pt-2">
                            <x-button label="Simpan Konfigurasi SMTP" type="submit" icon="o-check-circle" 
                                class="btn-warning text-white rounded-xl shadow-md" 
                                spinner="saveSettings" />
                        </div>
                    @endif
                </div>

                <div class="mt-10 pt-8 border-t border-base-200">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <x-icon name="o-bell-alert" class="w-5 h-5 text-primary" /> Email Notifikasi Admin
                    </h2>
                    <div class="bg-base-200/30 p-5 rounded-2xl border border-base-200">
                        <x-textarea 
                            label="Email Penerima Notifikasi Laporan" 
                            wire:model="notif_email_penerima" 
                            placeholder="email1@gmail.com, email2@gmail.com, ..." 
                            rows="2"
                            hint="Pisahkan dengan tanda koma (,) jika lebih dari satu. Setiap email di sini akan menerima notifikasi otomatis ketika ada laporan baru masuk." 
                            icon="o-user-group" />
                        
                        <div class="flex justify-end mt-4">
                            <x-button label="Perbarui Daftar Email" icon="o-check" 
                                class="btn-sm btn-primary text-white rounded-xl shadow-sm" 
                                wire:click="saveNotifSettings" spinner="saveNotifSettings" />
                        </div>
                    </div>
                </div>

                @if($unlock_email)
                <div class="mt-6 p-4 bg-error/5 border border-error/20 rounded-xl">
                    <p class="text-xs text-error font-medium flex items-center gap-2">
                        <x-icon name="o-exclamation-triangle" class="w-4 h-4" />
                        Hati-hati: Perubahan pada bagian ini dapat menyebabkan pengiriman email (verifikasi & laporan) berhenti berfungsi jika data tidak valid.
                    </p>
                </div>
                @endif
            </div>

            <x-slot:actions>
                @if($activeTab !== 'email')
                    <x-button label="Simpan Pengaturan" type="submit" icon="o-check-circle" class="btn-primary text-white"
                        spinner="saveSettings" />
                @endif
            </x-slot:actions>
        </x-form>
    </div>

    {{-- Modal Konfirmasi Unlock Email --}}
    <x-modal wire:model="showUnlockModal" title="Konfirmasi Perubahan Sensitif" separator>
        <div class="space-y-4">
            <div class="p-4 bg-error/10 border border-error/20 rounded-xl flex gap-3 items-start">
                <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error mt-1" />
                <div class="text-sm">
                    <p class="font-bold text-error text-base">Hati-hati!</p>
                    <p class="text-base-content/70">Mengubah pengaturan email dapat menghentikan fungsi verifikasi akun dan pengiriman laporan jika data tidak valid. Server akan melakukan restart konfigurasi secara otomatis.</p>
                </div>
            </div>

            <p class="text-sm font-medium">Ketik tulisan <span class="text-error font-black uppercase tracking-widest">SAYA MENGERTI</span> di bawah untuk melanjutkan:</p>
            
            <x-input 
                wire:model.live="confirmText" 
                placeholder="Tulis di sini..." 
                class="bg-base-200 font-black uppercase tracking-wider" 
                @keydown.enter="$wire.confirmUnlock()" />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.showUnlockModal = false" />
            <x-button label="Buka Kunci" wire:click="confirmUnlock" class="btn-error text-white" :disabled="strtoupper($confirmText) !== 'SAYA MENGERTI'" />
        </x-slot:actions>
    </x-modal>

    {{-- Modal Konfirmasi Simpan Email --}}
    <x-modal wire:model="showSaveEmailModal" title="Konfirmasi Simpan Perubahan" separator>
        <div class="space-y-4">
            <div class="p-4 bg-warning/10 border border-warning/20 rounded-xl flex gap-3 items-start">
                <x-icon name="o-information-circle" class="w-6 h-6 text-warning mt-1" />
                <div class="text-sm">
                    <p class="font-bold text-warning text-base">Final Check!</p>
                    <p class="text-base-content/70">Sistem akan memperbarui konfigurasi email dan melakukan restart layanan secara otomatis. Pastikan data SMTP sudah benar.</p>
                </div>
            </div>

            <p class="text-sm font-medium">Ketik tulisan <span class="text-warning font-black uppercase tracking-widest">SIMPAN PERUBAHAN</span> di bawah untuk mengeksekusi:</p>
            
            <x-input 
                wire:model.live="saveConfirmText" 
                placeholder="Tulis di sini..." 
                class="bg-base-200 font-black uppercase tracking-wider" 
                @keydown.enter="$wire.saveSettings()" />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.showSaveEmailModal = false" />
            <x-button label="Eksekusi Simpan" wire:click="saveSettings" class="btn-warning text-white" :disabled="strtoupper($saveConfirmText) !== 'SIMPAN PERUBAHAN'" spinner="saveSettings" />
        </x-slot:actions>
    </x-modal>
</div>
