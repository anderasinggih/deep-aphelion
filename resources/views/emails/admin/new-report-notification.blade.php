<x-mail::message>
# Laporan Baru Masuk

Halo Tim Admin,

Telah masuk laporan pengaduan baru dari warga dengan detail sebagai berikut:

**Detail Laporan:**
- **Kode Tracking:** {{ $pengaduan->kode_tracking }}
- **Judul:** {{ $pengaduan->judul }}
- **Kategori:** {{ $pengaduan->kategori->nama ?? '-' }}
- **Prioritas:** {{ ucfirst($pengaduan->prioritas) }}
- **Lokasi:** {{ $pengaduan->lokasi_kejadian }}
- **Tanggal Masuk:** {{ $pengaduan->created_at->isoFormat('D MMMM YYYY HH:mm') }} WIB

**Deskripsi:**
{{ Str::limit($pengaduan->deskripsi, 200) }}

<x-mail::button :url="route('admin.pengaduan.detail', $pengaduan->kode_tracking)">
Lihat Detail Laporan
</x-mail::button>

Silakan segera lakukan verifikasi dan tindak lanjut terhadap laporan tersebut.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
