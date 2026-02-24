<div class="max-w-4xl mx-auto py-10 px-4">
    <x-header title="Buat Laporan Baru" subtitle="Ceritakan masalah di sekitar Anda dengan jelas agar mudah ditangani."
        size="text-2xl" class="mb-5" />

    @if (session()->has('error'))
    <x-alert icon="o-exclamation-triangle" class="alert-error mb-5">
        {{ session('error') }}
    </x-alert>
    @endif

    <x-card class="shadow-sm">
        <x-form wire:submit="save">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kiri: Info Dasar -->
                <div class="space-y-4">
                    <x-input label="Judul Laporan" wire:model="judul"
                        placeholder="Contoh: Jalan berlubang di Jl. Merdeka" required />
                    <x-select label="Kategori" wire:model="kategori_id" :options="$kategoris" option-value="id"
                        option-label="nama" placeholder="Pilih Kategori" required />
                    <x-textarea label="Deskripsi Lengkap" wire:model="deskripsi"
                        placeholder="Jelaskan kondisi secara detail..." rows="5" required />
                    <x-file label="Foto Bukti (Opsional / Max 2MB)" wire:model="foto_bukti" accept="image/*" />

                    @if ($foto_bukti)
                    <div class="mt-2">
                        <span class="text-sm font-semibold text-gray-500">Preview:</span>
                        <img src="{{ $foto_bukti->temporaryUrl() }}"
                            class="rounded-lg shadow w-48 mt-1 border border-base-300">
                    </div>
                    @endif
                </div>

                <!-- Kanan: Lokasi & Opsi -->
                <div class="space-y-4">
                    <x-input label="Lokasi Kejadian (Teks)" wire:model="lokasi_kejadian"
                        placeholder="Contoh: Sebelah utara pertigaan pasar..." required />

                    <div class="p-4 bg-base-200/50 rounded-lg border border-base-300">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <x-icon name="o-map-pin" class="w-5 h-5 text-primary" /> Koordinat Peta (Opsional)
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Jika memungkinkan, cantumkan titik koordinat untuk
                            mempermudah petugas ke lokasi.</p>
                        <div class="grid grid-cols-2 gap-3 mb-2">
                            <x-input label="Latitude" wire:model="latitude" placeholder="-7.4..." />
                            <x-input label="Longitude" wire:model="longitude" placeholder="109.2..." />
                        </div>
                        <!-- Hint: Placeholder untuk implementasi Leaflet.js Geocoding (Saran/Nilai Plus) -->
                        <div class="text-xs text-info italic">* Integrasi Peta Leaflet.js dapat dipasang di sini pada
                            tahapan selanjutnya.</div>
                    </div>

                    <div class="p-4 bg-base-200/50 rounded-lg border border-base-300 mt-4">
                        <x-toggle label="Posting sebagai Anonim" wire:model="is_anonymous"
                            hint="Nama Anda akan disembunyikan dari publik, namun tetap tercatat di sistem Admin." />
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Batal" link="/" class="btn-ghost" />
                <x-button label="Kirim Laporan" type="submit" icon="o-paper-airplane" class="btn-primary"
                    spinner="save" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>