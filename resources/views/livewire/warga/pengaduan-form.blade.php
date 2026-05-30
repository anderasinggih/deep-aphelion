<div class="w-full max-w-7xl mx-auto px-0 sm:px-1.5 lg:px-2 pt-4 sm:pt-8" x-data="{ uploading: false }">
    {{-- Global Loading Overlay --}}
    <div wire:loading.flex wire:target="save" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="flex flex-col items-center bg-white text-slate-900 p-6 rounded-2xl shadow-2xl border border-slate-200 w-11/12 max-w-xs text-center">
            <span class="loading loading-spinner loading-lg text-[#0085ff] mb-4"></span>
            <p class="text-base font-black text-[#0085ff] animate-pulse">Sedang Mengirim Laporan...</p>
            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Mohon tunggu sebentar, jangan tutup atau refresh halaman ini.</p>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(var(--p), 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(var(--p), 0.5);
        }
    </style>

    <div class="flex flex-col gap-4 mb-6 sm:mb-8 sm:flex-row sm:items-center sm:justify-between pt-2 sm:pt-0 px-4 sm:px-0">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Buat Laporan Baru</h1>
            <p class="mt-1 text-sm text-base-content/70 font-medium">Ceritakan masalah atau aspirasi di sekitar Anda dengan jelas agar mudah ditangani.</p>
        </div>
    </div>

    @if (session()->has('error')) 
    <div class="px-4 sm:px-0">
        <x-alert icon="o-exclamation-triangle" class="mb-6 shadow-sm alert-error rounded-xl">
            {{ session('error') }}
        </x-alert>
    </div>
    @endif 

    <div class="bg-base-100 sm:border sm:shadow-sm sm:rounded-2xl border-y border-base-200 sm:border-base-200 overflow-hidden">
        <x-form wire:submit="save" class="p-4 sm:p-8 md:p-10">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                
                {{-- Kolom Kiri: Inti Laporan --}}
                <div class="space-y-8">
                    <x-input label="Judul Laporan" wire:model.live.debounce.500ms="judul"
                         placeholder="Contoh: Jalan berlubang parah di Jl. Merdeka" required icon="o-pencil" maxlength="100"
                         wire:key="input-judul" />
                    
                    @if(!auth()->check())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input label="Nama Pelapor" wire:model.live.debounce.500ms="guest_name"
                            placeholder="Nama Lengkap / Samaran Anda" required icon="o-user" maxlength="100"
                            wire:key="input-guest-name" />
                        
                        <x-input label="Nomor WhatsApp (Aktif)" wire:model.live.debounce.500ms="guest_wa"
                            placeholder="Contoh: 081234567890" required icon="o-phone" maxlength="15"
                            wire:key="input-guest-wa" />
                    </div>
                    @endif
                     
                     @if(count($similarPengaduans) > 0 && !$isEdit)
                     <div class="p-4 -mt-4 bg-primary/5 border border-primary/10 rounded-xl animate-in fade-in slide-in-from-top-2 duration-300">
                         <div class="flex items-center gap-2 mb-3 text-xs font-bold text-primary">
                             <x-icon name="o-light-bulb" class="w-4 h-4" />
                             LAPORAN SERUPA DITEMUKAN
                         </div>
                         <p class="text-[11px] text-base-content/60 mb-3 leading-relaxed">
                             Mungkin masalah ini sudah dilaporkan oleh warga lain. Anda bisa mendukung (upvote) laporan yang sudah ada agar lebih cepat ditindaklanjuti.
                         </p>
                         <div class="space-y-2 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                             @foreach($similarPengaduans as $similar)
                             <a href="{{ route('pengaduan.feed-detail', $similar['kode_tracking']) }}" target="_blank" 
                                 class="flex items-center justify-between p-2.5 bg-base-100 border border-base-200 rounded-lg hover:border-primary transition-colors group">
                                 <div class="flex flex-col min-w-0">
                                     <span class="text-xs font-bold truncate text-base-content group-hover:text-primary">{{ $similar['judul'] }}</span>
                                     <span class="text-[10px] text-base-content/40">{{ $similar['kode_tracking'] }} • {{ ucfirst($similar['status']) }}</span>
                                 </div>
                                 <x-icon name="o-arrow-top-right-on-square" class="w-4 h-4 text-base-content/30 group-hover:text-primary" />
                             </a>
                             @endforeach
                         </div>
                     </div>
                     @endif
                     
                     <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                         <x-select
                             label="Kategori" wire:model.live="kategori_id" :options="$kategoris" option-value="id"
                             option-label="nama" placeholder="Pilih Kategori" required icon="o-tag" />
                         
                         <x-datetime label="Tanggal Kejadian" wire:model="tanggal_kejadian" icon="o-calendar" required />
                     </div>
 
                     <div class="space-y-2">
                         <label class="label pb-0">
                             <span class="label-text font-bold text-base-content/80">Tingkat Urgensi</span>
                         </label>
                         <div class="flex p-1 bg-base-200 rounded-xl w-full border border-base-300">
                             <label class="flex-1 cursor-pointer">
                                 <input type="radio" name="prioritas" wire:model.live="prioritas" value="rendah" class="sr-only peer" wire:key="prioritas-rendah" />
                                 <div class="py-2 text-center rounded-lg transition-all peer-checked:bg-base-100 peer-checked:shadow-sm peer-checked:text-[#0085ff] text-[11px] font-black text-base-content uppercase tracking-tighter">
                                     Rendah
                                 </div>
                             </label>
                             <label class="flex-1 cursor-pointer">
                                 <input type="radio" name="prioritas" wire:model.live="prioritas" value="sedang" class="sr-only peer" wire:key="prioritas-sedang" />
                                 <div class="py-2 text-center rounded-lg transition-all peer-checked:bg-base-100 peer-checked:shadow-sm peer-checked:text-[#0085ff] text-[11px] font-black text-base-content uppercase tracking-tighter">
                                     Sedang
                                 </div>
                             </label>
                             <label class="flex-1 cursor-pointer">
                                 <input type="radio" name="prioritas" wire:model.live="prioritas" value="tinggi" class="sr-only peer" wire:key="prioritas-tinggi" />
                                 <div class="py-2 text-center rounded-lg transition-all peer-checked:bg-base-100 peer-checked:shadow-sm peer-checked:text-[#0085ff] text-[11px] font-black text-base-content uppercase tracking-tighter">
                                     Tinggi
                                 </div>
                             </label>
                         </div>
                     </div>
 
                     <x-textarea
                         label="Deskripsi Lengkap" wire:model.live.debounce.250ms="deskripsi"
                         placeholder="Jelaskan kondisi secara detail (kapan, apa yang terjadi, dan dampaknya)..."
                         rows="4" required maxlength="2000"
                         wire:key="input-deskripsi" />
 
                     <x-textarea
                         label="Harapan Pelapor (Opsional)" wire:model.live.debounce.250ms="harapan_pelapor"
                         placeholder="Tuliskan solusi atau bantuan yang Anda harapkan dari pihak berwenang..."
                         rows="2" maxlength="500"
                         wire:key="input-harapan" />
                     
                     <div class="space-y-2">
                         <label class="label pb-0">
                             <span class="label-text font-bold text-base-content/80">Foto Bukti (Maksimal 4)</span>
                         </label>
                         <div x-data="{
                              async handleFileSelect(event) {
                                  const files = event.target.files;
                                  if (!files.length) return;

                                  uploading = true;
                                  const compressedFiles = [];

                                  for (let i = 0; i < files.length; i++) {
                                      if (compressedFiles.length >= 4) break;
                                      
                                      try {
                                          const compressed = await this.compressImage(files[i], 600, 0.3);
                                          compressedFiles.push(compressed);
                                      } catch (e) {
                                          console.error('Gagal mengompres gambar:', e);
                                          compressedFiles.push(files[i]);
                                      }
                                  }

                                  @this.uploadMultiple('foto_bukti', compressedFiles, 
                                      () => { uploading = false; }, 
                                      () => { uploading = false; alert('Gagal mengunggah foto.'); }
                                  );
                              },
                              compressImage(file, maxWidth = 600, quality = 0.3) {
                                  return new Promise((resolve) => {
                                      const reader = new FileReader();
                                      reader.readAsDataURL(file);
                                      reader.onload = (event) => {
                                          const img = new Image();
                                          img.src = event.target.result;
                                          img.onload = () => {
                                              const canvas = document.createElement('canvas');
                                              let width = img.width;
                                              let height = img.height;

                                              if (width > maxWidth) {
                                                  height = Math.round((height * maxWidth) / width);
                                                  width = maxWidth;
                                              }

                                              canvas.width = width;
                                              canvas.height = height;

                                              const ctx = canvas.getContext('2d');
                                              ctx.drawImage(img, 0, 0, width, height);

                                              canvas.toBlob((blob) => {
                                                  if (blob) {
                                                      const compressedFile = new File([blob], file.name.substring(0, file.name.lastIndexOf('.')) + '.jpg', {
                                                          type: 'image/jpeg',
                                                          lastModified: Date.now(),
                                                      });
                                                      resolve(compressedFile);
                                                  } else {
                                                      resolve(file);
                                                  }
                                              }, 'image/jpeg', quality);
                                          };
                                          img.onerror = () => resolve(file);
                                      };
                                      reader.onerror = () => resolve(file);
                                  });
                              }
                          }" class="relative">
                             
                             <input type="file" id="foto_bukti" @change="handleFileSelect" multiple accept="image/*" class="hidden" x-ref="fileInput">
                             
                             <div class="flex items-center gap-3">
                                 <button type="button" @click="$refs.fileInput.click()" 
                                         class="btn btn-primary btn-md rounded-xl text-white border-none shadow-md px-6">
                                     <x-icon name="o-camera" class="w-5 h-5 mr-1" />
                                     Pilih Foto
                                 </button>
                                 
                                 <div class="text-xs font-medium text-base-content/60">
                                     @if($foto_bukti)
                                         {{ count($foto_bukti) }} foto terpilih
                                     @else
                                         Belum ada foto
                                     @endif
                                 </div>
                             </div>
 
                             <div x-show="uploading" class="mt-2">
                                 <progress class="progress progress-primary w-full h-1" value="100" max="100"></progress>
                                 <span class="text-[10px] font-bold text-primary animate-pulse">Sedang mengunggah & mengompres...</span>
                             </div>
                         </div>
                         <p class="text-[11px] text-base-content/50 italic">Pilih hingga 4 foto.</p>
                     </div>
 
                     @if (!empty($foto_bukti) || !empty($old_foto_bukti))
                         <div class="mt-4 border shadow-sm rounded-xl overflow-hidden bg-base-200/50 border-base-300">
                             <p class="p-3 text-[10px] font-bold text-base-content/50 border-b border-base-300 bg-base-100">
                                 Pratinjau Foto Bukti
                             </p>
 
                             <div class="carousel w-full snap-x snap-mandatory overflow-x-auto scroll-smooth no-scrollbar bg-black/5" id="carousel-preview">
                                 {{-- New Uploads --}}
                                 @if($foto_bukti)
                                     @foreach($foto_bukti as $index => $foto)
                                         @php
                                             $tmpUrl = null;
                                             try { $tmpUrl = $foto->temporaryUrl(); } catch (\Exception $e) {}
                                         @endphp
                                         <div class="carousel-item relative w-full snap-start flex justify-center items-center bg-base-300" style="aspect-ratio: 3/4;">
                                             @if($tmpUrl)
                                                 <img src="{{ $tmpUrl }}" class="absolute inset-0 w-full h-full object-cover" />
                                             @else
                                                 <div class="flex flex-col items-center justify-center p-4 text-center">
                                                     <x-icon name="o-arrow-path" class="w-6 h-6 animate-spin opacity-20 mb-2" />
                                                     <span class="text-[10px] opacity-40">Memproses...</span>
                                                 </div>
                                             @endif
                                             
                                             {{-- Tombol Hapus Foto Baru --}}
                                             <button type="button" wire:click="removeFoto({{ $index }})" 
                                                     class="absolute top-3 left-3 bg-error/90 hover:bg-error text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg z-20 transition-all hover:scale-110"
                                                     title="Hapus Foto">
                                                 <x-icon name="o-x-mark" class="w-4 h-4" />
                                             </button>
                                             
                                             <div class="absolute top-3 right-3 bg-primary text-white text-[9px] px-2 py-0.5 rounded-full font-bold shadow-lg z-10">
                                                 Baru
                                             </div>
                                         </div>
                                     @endforeach
                                 @endif
 
                                 {{-- Existing Photos --}}
                                 @if($old_foto_bukti)
                                     @foreach($old_foto_bukti as $index => $foto)
                                         <div class="carousel-item relative w-full snap-start flex justify-center items-center bg-base-300" style="aspect-ratio: 3/4;">
                                             <img src="{{ asset('storage/' . $foto) }}" class="absolute inset-0 w-full h-full object-cover opacity-80" />
                                             
                                             {{-- Tombol Hapus Foto Lama --}}
                                             <button type="button" wire:click="removeOldFoto({{ $index }})" 
                                                     class="absolute top-3 left-3 bg-error/90 hover:bg-error text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg z-20 transition-all hover:scale-110"
                                                     title="Hapus Foto">
                                                 <x-icon name="o-x-mark" class="w-4 h-4" />
                                             </button>
                                             
                                             <div class="absolute top-3 right-3 bg-base-100 text-base-content text-[9px] px-2 py-0.5 rounded-full font-bold shadow-lg border border-base-300 z-10">
                                                 Lama
                                             </div>
                                         </div>
                                     @endforeach
                                 @endif
                             </div>
                             
                             @if((is_array($foto_bukti) && count($foto_bukti) > 1) || (is_array($old_foto_bukti) && count($old_foto_bukti) > 1) || ($foto_bukti && $old_foto_bukti))
                             <div class="p-2 flex justify-center gap-1.5 border-t border-base-300 bg-base-100">
                                 <span class="text-[9px] font-medium text-base-content/40 italic">Geser untuk melihat foto lainnya</span>
                             </div>
                             @endif
                         </div>
                     @endif
                 </div>
                 
                 {{-- Kolom Kanan: Lokasi & Privasi --}}
                 <div class="space-y-8">
                     <x-input label="Detail Lokasi Kejadian" wire:model="lokasi_kejadian"
                         placeholder="Contoh: Sebelah utara pertigaan pasar, dekat tiang listrik" required
                         icon="o-map-pin" />
                     
                     <div class="p-0 sm:p-5 border-0 sm:border shadow-none sm:shadow-sm bg-transparent sm:bg-base-200/30 rounded-none sm:rounded-xl border-base-200">
                         <div class="flex items-center gap-2 mb-2 text-sm font-bold text-base-content">
                             <x-icon name="o-globe-asia-australia" class="w-5 h-5 text-primary" />Koordinat Peta (Opsional)
                         </div>
                         <p class="mb-4 text-xs leading-relaxed text-base-content/60">Pilih titik lokasi pada peta untuk mempermudah petugas menemukan lokasi kejadian. </p>
                         
                         <div x-data="mapComponent()" x-init="initMap()" 
                             x-on:location-updated.window="placeMarker($event.detail.lat, $event.detail.lng, 16)"
                             class="w-full relative z-0 mb-4">
                             <div class="flex flex-col sm:flex-row gap-2 mb-3">
                                 <div class="relative flex-1">
                                     <x-input x-model="searchQuery"
                                         @keydown.enter.prevent="searchLocation"
                                         placeholder="Cari nama tempat / jalan..."
                                         class="w-full bg-base-100 pr-10" />
                                     <button type="button" @click="searchLocation"
                                         class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-ghost btn-xs btn-circle text-base-content/50 hover:text-primary">
                                         <x-icon name="o-magnifying-glass" class="w-4 h-4" />
                                     </button>
                                 </div>
                                 <x-button
                                     type="button" @click="getCurrentLocation" icon="o-map-pin" label="Lokasi Saat Ini"
                                     class="btn-primary btn-outline bg-base-100 sm:w-auto w-full" />
                             </div>
 
                             <div wire:ignore id="map" wire:key="map-container-warga"
                                 class="w-full rounded-xl border border-base-300 shadow-inner z-[1]" 
                                 style="height: 400px; min-height: 400px;"></div>
                         </div>
                         
                         <div class="grid grid-cols-2 gap-3 mb-3 hidden">
                             <x-input label="Latitude" wire:model="latitude" placeholder="-7.425..." readonly class="bg-base-200/50" />
                             <x-input label="Longitude" wire:model="longitude" placeholder="109.255..." readonly class="bg-base-200/50" />
                         </div>
                         
                         <div class="flex items-start gap-1.5 text-[11px] text-info/80 italic">
                             <x-icon name="o-information-circle" class="w-3.5 h-3.5 shrink-0 mt-0.5" />
                             <span>Anda dapat mengklik atau menggeser peta untuk menandai lokasi secara presisi.</span>
                         </div>
                     </div>
 
                     {{-- Checkbox Anonim & Rahasia Grid --}}
                     <div class="grid grid-cols-1 gap-6 mb-4 sm:grid-cols-2">
                         {{-- Checkbox Anonim --}}
                         <div class="p-4 transition-colors border shadow-sm bg-base-200/30 rounded-xl border-base-200 hover:border-primary/30">
                             <label class="flex items-start gap-3 cursor-pointer group">
                                 <div class="relative flex items-center justify-center shrink-0 w-5 h-5 mt-0.5">
                                     <input wire:model="is_anonymous" id="is_anonymous" type="checkbox"
                                         class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer peer" />
                                     <div class="absolute inset-0 w-full h-full transition-colors border-2 rounded bg-base-100 border-base-content/30 peer-checked:bg-primary peer-checked:border-primary"></div>
                                     <svg class="absolute z-0 w-3.5 h-3.5 text-white transition-opacity opacity-0 peer-checked:opacity-100"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                     </svg>
                                 </div>
                                 <div class="flex flex-col">
                                     <span class="text-sm font-bold transition-colors text-base-content group-hover:text-primary flex items-center gap-1.5">
                                         <x-icon name="o-eye-slash" class="w-4 h-4" /> Anonim
                                     </span>
                                     <span class="text-[10px] mt-0.5 leading-relaxed text-base-content/60">Nama disembunyikan dari publik.</span>
                                 </div>
                             </label>
                         </div>
 
                         {{-- Checkbox Rahasia --}}
                         <div class="p-4 transition-colors border shadow-sm bg-base-200/30 rounded-xl border-base-200 hover:border-warning/30">
                             <label class="flex items-start gap-3 cursor-pointer group">
                                 <div class="relative flex items-center justify-center shrink-0 w-5 h-5 mt-0.5">
                                     <input wire:model="is_private" id="is_private" type="checkbox"
                                         class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer peer" />
                                     <div class="absolute inset-0 w-full h-full transition-colors border-2 rounded bg-base-100 border-base-content/30 peer-checked:bg-warning peer-checked:border-warning"></div>
                                     <svg class="absolute z-0 w-3.5 h-3.5 text-white transition-opacity opacity-0 peer-checked:opacity-100"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                     </svg>
                                 </div>
                                 <div class="flex flex-col">
                                     <span class="text-sm font-bold transition-colors text-base-content group-hover:text-warning flex items-center gap-1.5">
                                         <x-icon name="o-lock-closed" class="w-4 h-4" /> Rahasia
                                     </span>
                                     <span class="text-[10px] mt-0.5 leading-relaxed text-base-content/60">Laporan tidak tampil di feed publik.</span>
                                 </div>
                             </label>
                         </div>
                     </div>
 
                 </div>
             </div>
 
             {{-- Disclaimer Privasi Data --}}
             <div class="mt-4 pt-4 border-t border-base-200">
                 <div class="flex items-start gap-3 p-4 bg-info/10 border border-info/20 rounded-xl text-info/90">
                     <x-icon name="o-shield-check" class="w-6 h-6 shrink-0 mt-0.5" />
                     <div>
                         <h4 class="font-bold text-sm mb-1">Perlindungan Data Pribadi</h4>
                         <p class="text-xs leading-relaxed opacity-90">
                             Identitas Anda (termasuk Nama, No WhatsApp, dan Data Diri) dijamin kerahasiaannya dan hanya digunakan untuk keperluan validasi internal layanan pemerintahan sesuai dengan Undang-Undang Pelindungan Data Pribadi (UU PDP).
                         </p>
                     </div>
                 </div>
             </div>
 
             <x-slot:actions>
                 <div class="flex flex-col sm:flex-row items-center justify-end w-full gap-3 mt-2">
                      <div x-show="uploading" class="w-full sm:w-auto text-right mb-2 sm:mb-0 mr-0 sm:mr-3">
                          <span class="text-xs text-error font-bold animate-pulse flex items-center justify-center sm:justify-end gap-1">
                              <x-icon name="o-arrow-path" class="w-3.5 h-3.5 animate-spin" />
                              Foto sedang diunggah, harap tunggu...
                          </span>
                      </div>
                      <x-button label="Batal" link="{{ route('beranda') }}" class="rounded-xl btn-ghost hover:bg-base-200 w-full sm:w-auto font-bold" />
                      <x-button 
                          type="submit"
                          class="text-white border-none shadow-sm rounded-xl btn-primary bg-primary hover:bg-primary/90 px-8 w-full sm:w-auto font-bold"
                          wire:loading.attr="disabled"
                          wire:loading.class="opacity-50"
                          ::disabled="uploading"
                          spinner="save">
                          <span x-show="uploading" class="flex items-center gap-1">
                              <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" />
                              Mengunggah Foto...
                          </span>
                          <span x-show="!uploading" class="flex items-center gap-1.5">
                              <x-icon name="{{ $isEdit ? 'o-check-circle' : 'o-paper-airplane' }}" class="w-4 h-4" />
                              {{ $isEdit ? 'Simpan Perubahan' : 'Kirim Laporan' }}
                          </span>
                      </x-button>
                 </div>
             </x-slot:actions>
         </x-form>
     </div>
 
     {{-- Modal Sukses Lapor --}}
     <x-modal wire:model="showSuccessModal" persistent class="backdrop-blur-md">
         <div class="text-center p-2 pt-10 sm:pt-14 translate-y-2">
             <div class="w-20 h-20 bg-success/10 text-success rounded-full flex items-center justify-center mx-auto mb-4">
                 <x-icon name="o-check-badge" class="w-12 h-12" />
             </div>
             
             <h3 class="text-2xl font-black text-base-content mb-2">Laporan Terkirim!</h3>
             <p class="text-sm text-base-content/60 mb-6">
                 Laporan Anda telah berhasil masuk ke sistem kami. Tim admin akan segera melakukan verifikasi.
             </p>
 
             <div class="bg-base-200 rounded-2xl p-4 mb-6 border-2 border-dashed border-base-300">
                 <p class="text-[10px] font-black text-base-content/40 uppercase tracking-widest mb-1">Kode Tracking Anda</p>
                 <div class="text-xl font-mono font-black text-primary tracking-tight">{{ $lastTrackingCode }}</div>
                 <p class="text-[10px] mt-2 text-base-content/50 italic">Simpan kode ini untuk memantau progres laporan Anda secara mandiri.</p>
             </div>
 
             <div class="flex flex-col gap-3">
                 @if($waAdminLink)
                 <a href="{{ $waAdminLink }}" target="_blank" 
                    class="btn btn-success text-white font-bold rounded-xl shadow-lg shadow-success/20">
                     <x-icon name="o-chat-bubble-left-right" class="w-5 h-5 mr-2" />
                     Kirim Konfirmasi ke WA Admin
                 </a>
                 @endif

                 <a href="{{ route('print.resi', $lastSavedId ?? 0) }}" target="_blank" 
                    class="btn btn-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20">
                     <x-icon name="o-printer" class="w-5 h-5 mr-2" />
                     Cetak Tanda Terima
                 </a>
                 
                 <x-button label="Kembali ke Beranda" link="{{ route('beranda') }}" 
                           class="btn-ghost font-bold text-base-content/60" />
             </div>
         </div>
     </x-modal>
</div>

<script>
    window.mapComponent = function () {
        return {
            map: null,
            marker: null,
            searchQuery: '',

            initMap() {
                setTimeout(() => {
                    let mapContainer = document.getElementById('map');
                    if (!mapContainer || mapContainer._leaflet_id) return;

                    let defaultLat = -7.4248;
                    let defaultLng = 109.2302;

                    this.map = L.map('map', { attributionControl: false }).setView([defaultLat, defaultLng], 12);

                    L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                        maxZoom: 20,
                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                    }).addTo(this.map);

                    this.getCurrentLocation(false);

                    this.map.on('click', (e) => {
                        this.placeMarker(e.latlng.lat, e.latlng.lng);
                    });

                    setTimeout(() => { this.map.invalidateSize(); }, 300);
                }, 100);
            },

            placeMarker(lat, lng, zoom = null) {
                console.log('Placing marker at:', lat, lng);
                if (this.marker) { this.map.removeLayer(this.marker); }
                this.marker = L.marker([lat, lng]).addTo(this.map);
                if (zoom) { this.map.setView([lat, lng], zoom); }
                
                this.$wire.set('latitude', parseFloat(lat).toFixed(6));
                this.$wire.set('longitude', parseFloat(lng).toFixed(6));
                
                // Call Livewire for reverse geocoding (Proxy to avoid CORS)
                this.$wire.reverseGeocode(lat, lng);
            },

            getCurrentLocation(forceZoom = true) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            let lat = position.coords.latitude;
                            let lng = position.coords.longitude;
                            if(this.map) {
                                this.map.setView([lat, lng], forceZoom ? 16 : 14);
                                this.placeMarker(lat, lng);
                            }
                        },
                        () => { if (forceZoom) alert('Gagal mengambil lokasi.'); }
                    );
                }
            },

            searchLocation() {
                if (!this.searchQuery.trim()) return;
                // Call Livewire for search (Proxy to avoid CORS)
                this.$wire.searchLocation(this.searchQuery);
            }
        };
    };

    document.addEventListener('livewire:navigated', () => {
        window.scrollTo(0, 0);
        const mapEl = document.getElementById('map');
        if (mapEl) {
             setTimeout(() => {
                 const alpineEl = mapEl.closest('[x-data]');
                 if (alpineEl && Alpine) {
                     Alpine.$data(alpineEl).initMap();
                 }
             }, 200);
        }
    });

    window.addEventListener('scroll-to-top', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Auto scroll to top if there are validation errors after submit
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                if (effect && effect.errors && Object.keys(effect.errors).length > 0) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            })
        })
    })
</script>