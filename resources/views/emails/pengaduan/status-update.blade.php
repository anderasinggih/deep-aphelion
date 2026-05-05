<x-mail::message>
# Update Status Laporan Pengaduan

Yth. **{{ $pengaduan->user->name }}**,

Kami menginformasikan perkembangan terbaru mengenai laporan pengaduan Anda dengan kode tracking:
<x-mail::panel>
**{{ $pengaduan->kode_tracking }}**
</x-mail::panel>

Status saat ini:
<x-mail::panel>
## **{{ $statusLabel }}**
</x-mail::panel>

**Pesan Sistem:**
{{ $statusMessage }}

@if($pengaduan->pesan_penutup && $pengaduan->status === 'selesai')
**Keterangan Penyelesaian:**
_{{ $pengaduan->pesan_penutup }}_
@endif

@if($pengaduan->status === 'selesai')
---
⭐ **BAGAIMANA PELAYANAN KAMI?** ⭐

Kami telah berupaya menindaklanjuti laporan Anda. Penilaian dari Anda sangat berarti untuk evaluasi dan perbaikan kinerja instansi kami. 

Silakan klik tombol di bawah ini untuk melihat hasil pengerjaan (foto bukti) dan memberikan **Rating Bintang**:

<x-mail::button :url="$actionUrl" color="success">
Cek Hasil & Beri Penilaian
</x-mail::button>

@else

Silakan klik tombol di bawah ini untuk melihat detail progres dan riwayat laporan Anda:

<x-mail::button :url="$actionUrl" color="primary">
{{ $actionLabel }}
</x-mail::button>

@endif

@if($pengaduan->status !== 'selesai' && $pengaduan->status !== 'ditolak')
> [!IMPORTANT]
> **Himbauan Keamanan & Layanan:**
> Mohon untuk memantau kotak masuk (inbox) atau folder spam email Anda secara berkala untuk mendapatkan informasi terbaru mengenai laporan Anda. Petugas kami mungkin akan menghubungi Anda jika diperlukan data tambahan.
@endif

Terima kasih atas partisipasi aktif Anda dalam menjaga kualitas layanan publik di Kecamatan Kembaran.

Hormat kami,
**Admin Kembaran Ngadu**
Kantor Kecamatan Kembaran

<x-mail::subcopy>
Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan tautan berikut ke browser Anda:
[{{ $actionUrl }}]({{ $actionUrl }})
</x-mail::subcopy>
</x-mail::message>
