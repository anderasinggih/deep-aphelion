<div class="max-w-5xl px-0.1 py-8 mx-auto sm:px-6 lg:px-8">
    <x-header title="Buat Laporan Baru"
        subtitle="Ceritakan masalah atau aspirasi di sekitar Anda dengan jelas agar mudah ditangani." size="text-3xl"
        class="mb-8" />

    @if (session()->has('error'))
    <x-alert icon="o-exclamation-triangle" class="mb-6 shadow-sm alert-error rounded-xl">
        {{ session('error') }}
    </x-alert>
    @endif

    <x-card class="border shadow-sm bg-base-100 rounded-2xl border-base-200">
        <x-form wire:submit="save">

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:gap-8">
                <div class="space-y-5">
                    <x-input label="Judul Laporan" wire:model="judul"
                        placeholder="Contoh: Jalan berlubang parah di Jl. Merdeka" required icon="o-pencil" />

                    <x-select label="Kategori" wire:model="kategori_id" :options="$kategoris" option-value="id"
                        option-label="nama" placeholder="Pilih Kategori" required icon="o-tag" />

                    <x-textarea label="Deskripsi Lengkap" wire:model="deskripsi"
                        placeholder="Jelaskan kondisi secara detail (kapan, apa yang terjadi, dan dampaknya)..."
                        rows="5" required />

                    <x-file label="Foto Bukti (Maksimal 2MB)" wire:model="foto_bukti" accept="image/*"
                        hint="Format yang didukung: JPG, PNG, JPEG." />

                    @if ($foto_bukti)
                    <div class="p-3 mt-2 border shadow-sm rounded-xl bg-base-200/50 border-base-300">
                        <span class="block mb-2 text-xs font-semibold text-base-content/60">Preview Foto:</span>
                        <img src="{{ $foto_bukti->temporaryUrl() }}"
                            class="object-cover w-full h-auto rounded-lg shadow-sm aspect-video">
                    </div>
                    @endif
                </div>

                <div class="space-y-5">
                    <x-input label="Detail Lokasi Kejadian" wire:model="lokasi_kejadian"
                        placeholder="Contoh: Sebelah utara pertigaan pasar, dekat tiang listrik" required
                        icon="o-map-pin" />

                    <div class="p-5 border shadow-sm bg-base-200/30 rounded-xl border-base-200">
                        <div class="flex items-center gap-2 mb-2 text-sm font-bold text-base-content">
                            <x-icon name="o-globe-asia-australia" class="w-5 h-5 text-primary" /> Koordinat Peta
                            (Opsional)
                        </div>
                        <p class="mb-4 text-xs leading-relaxed text-base-content/60">
                            Pilih titik lokasi pada peta untuk mempermudah petugas menemukan lokasi kejadian.
                        </p>

                        <div x-data="leafletMap()" x-init="initMap()" class="w-full relative z-0 mb-4">
                            <!-- Map Controls -->
                            <div class="flex flex-col sm:flex-row gap-2 mb-3">
                                <div class="relative flex-1">
                                    <x-input x-model="searchQuery" @keydown.enter.prevent="searchLocation"
                                        placeholder="Cari nama tempat / jalan..." class="w-full bg-base-100 pr-10" />
                                    <button type="button" @click="searchLocation"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-ghost btn-xs btn-circle text-base-content/50 hover:text-primary">
                                        <x-icon name="o-magnifying-glass" class="w-4 h-4" />
                                    </button>
                                </div>
                                <x-button type="button" @click="getCurrentLocation" icon="o-map-pin"
                                    label="Lokasi Saat Ini"
                                    class="btn-primary btn-outline bg-base-100 sm:w-auto w-full" />
                            </div>

                            <!-- Map Container -->
                            <div wire:ignore id="map"
                                class="w-full h-64 rounded-xl border border-base-300 shadow-inner z-[1]"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <x-input label="Latitude" wire:model="latitude" placeholder="-7.425..." readonly
                                class="bg-base-200/50" />
                            <x-input label="Longitude" wire:model="longitude" placeholder="109.255..." readonly
                                class="bg-base-200/50" />
                        </div>

                        <div class="flex items-start gap-1.5 text-[11px] text-info/80 italic">
                            <x-icon name="o-information-circle" class="w-3.5 h-3.5 shrink-0 mt-0.5" />
                            <span>Anda dapat mengklik atau menggeser peta untuk menandai lokasi secara presisi.</span>
                        </div>
                    </div>

                    <div
                        class="p-4 transition-colors border shadow-sm bg-base-200/30 rounded-xl border-base-200 hover:border-primary/30">
                        <label class="flex items-start gap-3 cursor-pointer group">

                            <div class="relative flex items-center justify-center shrink-0 w-5 h-5 mt-0.5">
                                <input wire:model="is_anonymous" id="is_anonymous" type="checkbox"
                                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer peer" />

                                <div
                                    class="absolute inset-0 w-full h-full transition-colors border-2 rounded bg-base-100 border-base-content/30 peer-checked:bg-primary peer-checked:border-primary">
                                </div>

                                <svg class="absolute z-0 w-3.5 h-3.5 text-white transition-opacity opacity-0 peer-checked:opacity-100"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <span
                                    class="text-sm font-bold transition-colors text-base-content group-hover:text-primary">Sembunyikan
                                    nama saya (Anonim)</span>
                                <span class="text-xs mt-0.5 leading-relaxed text-base-content/60">
                                    Nama Anda tidak akan tampil di publik, namun tetap aman tercatat di sistem admin.
                                </span>
                            </div>

                        </label>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <div class="flex items-center justify-end w-full gap-3">
                    <x-button label="Batal" link="/" class="rounded-xl btn-ghost hover:bg-base-200" />

                    <x-button label="Kirim Laporan" type="submit" icon="o-paper-airplane"
                        class="text-white border-none shadow-sm rounded-xl btn-primary bg-primary hover:bg-primary/90 p-4"
                        spinner="save" />
                </div>
            </x-slot:actions>


        </x-form>
    </x-card>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('leafletMap', () => ({
            map: null,
            marker: null,
            searchQuery: '',

            initMap() {
                // Return if already initialized
                let mapContainer = document.getElementById('map');
                if (!mapContainer || mapContainer._leaflet_id) return;

                // Default koordinat Kabupaten Banyumas
                let defaultLat = -7.4248;
                let defaultLng = 109.2302;

                this.map = L.map('map').setView([defaultLat, defaultLng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);

                // Initialize with current location if possible automatically
                this.getCurrentLocation(false);

                // Map click event handling
                this.map.on('click', (e) => {
                    this.placeMarker(e.latlng.lat, e.latlng.lng);
                });

                // Fix map rendering issues inside hidden tabs or delayed renders
                setTimeout(() => {
                    this.map.invalidateSize();
                }, 500);
            },

            placeMarker(lat, lng, zoom = null) {
                if (this.marker) {
                    this.map.removeLayer(this.marker);
                }
                this.marker = L.marker([lat, lng]).addTo(this.map);

                if (zoom) {
                    this.map.setView([lat, lng], zoom);
                }

                // Perbarui properti Livewire agar form tahu koordinatnya
                this.$wire.set('latitude', lat.toFixed(6));
                this.$wire.set('longitude', lng.toFixed(6));
            },

            getCurrentLocation(forceZoom = true) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;
                        let zoomTarget = forceZoom ? 16 : 14;
                        this.map.setView([lat, lng], zoomTarget);
                        this.placeMarker(lat, lng);
                    }, (error) => {
                        // Gagal ambil lokasi diam saja kalau auto, alert kalau force
                        if (forceZoom) { alert('Tidak dapat mengambil lokasi. Pastikan GPS aktif dan diizinkan.'); }
                    });
                } else {
                    if (forceZoom) { alert('Browser Anda tidak mendukung deteksi lokasi.'); }
                }
            },

            async searchLocation() {
                if (!this.searchQuery.trim()) return;

                let query = encodeURIComponent(this.searchQuery + ' Banyumas'); // Bias to local area
                try {
                    // OpenStreetMap Nominatim API for free geocoding
                    let response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`);
                    let data = await response.json();

                    if (data && data.length > 0) {
                        let lat = parseFloat(data[0].lat);
                        let lng = parseFloat(data[0].lon);
                        this.placeMarker(lat, lng, 16);
                    } else {
                        alert('Lokasi tidak ditemukan. Coba gunakan nama jalan atau tempat yang lebih spesifik.');
                    }
                } catch (e) {
                    console.error('Pencarian lokasi gagal', e);
                    alert('Terjadi kesalahan saat mencari lokasi.');
                }
            }
        }))
    });
</script>
@endpush