# Kembaran Ngadu 📢

[![Laravel v12.x](https://img.shields.io/badge/Laravel-v12.x-red.svg)](https://laravel.com)
[![Livewire v3.x](https://img.shields.io/badge/Livewire-v3.x-blue.svg)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v3.x-38bdf8.svg)](https://tailwindcss.com)
[![Locust](https://img.shields.io/badge/Load_Testing-Locust-green.svg)](https://locust.io)

**Kembaran Ngadu** adalah platform digital resmi berbasis web (Responsive & Mobile-First) yang dirancang khusus untuk warga Kecamatan Kembaran. Platform ini memfasilitasi komunikasi dua arah yang transparan antara masyarakat dan pemerintah daerah untuk mempercepat penanganan masalah infrastruktur, pelayanan publik, serta memberikan sarana umpan balik yang akuntabel.

> 📊 **Dokumentasi Visual**: Anda dapat melihat diagram sistem lengkap (Use Case, ERD, Activity, dan Sequence Diagram) berbasis Mermaid.js di berkas [DIAGRAMS.md](file:///Volumes/LVNPC/KULIAH/TA/PROJECT/KEMBARAN%20NGADU/kembaranngadu/DIAGRAMS.md).

---

## 🚀 Fitur Utama

### 👥 Peran Warga / Pelapor
*   **Registrasi Akun Praktis**: Pendaftaran menggunakan validasi NIK (16 digit), nomor WhatsApp, dan email.
*   **Feed Pengaduan Publik**: Eksplorasi laporan yang dipublikasikan secara transparan, filter berdasarkan kategori, dan pencarian kode tracking.
*   **Sistem Dukungan (Upvote)**: Dukung laporan warga lain untuk menaikkan urgensi penanganan (laporan dengan ≥ 50 dukungan otomatis ditandai **"Mendesak"**).
*   **Komentar Interaktif**: Ruang diskusi di setiap detail laporan dengan fitur balasan bertingkat (*nested reply*).
*   **Formulir Laporan Modern**:
    *   **Geo-tagging**: Penentuan lokasi presisi secara otomatis menggunakan integrasi peta interaktif **Leaflet Maps**.
    *   **Kompresi Gambar Otomatis**: Gambar dikompresi di sisi client (~35KB) menggunakan **HTML5 Canvas API** sebelum diunggah untuk menghemat kuota dan mempercepat proses.
    *   **Kendali Privasi**: Fitur opsional untuk mengirimkan laporan secara Anonim atau Privat (hanya dapat dilihat oleh pelapor dan admin).
*   **Notifikasi Real-Time**: Pemberitahuan instan via ikon bel di web dan pengiriman tautan progres otomatis via WhatsApp.
*   **Sistem Penilaian Multi-User (Multi-User Rating)**:
    *   Warga dapat menilai kinerja penanganan setelah laporan diubah statusnya menjadi **Selesai**.
    *   Penilaian terbagi menjadi 4 metrik: **Pelayanan, Respon, Kompetensi,** dan **Fasilitas**.
    *   *Auto-Popup Rating* yang muncul otomatis setelah 15 detik saat mengunjungi detail laporan yang selesai (kecuali sudah memberikan rating).
    *   Daftar ulasan dilengkapi dengan pagination client-side (3 item) yang responsif menggunakan **Alpine.js**.

### 💼 Peran Admin / Petugas Kecamatan
*   **Dashboard Interaktif**: Statistik real-time laporan masuk vs selesai, grafik distribusi rating kepuasan warga, daftar umpan balik terbaru, serta monitoring SLA.
*   **Manajemen Pengguna**: Pengelolaan data petugas dan akun warga dengan sistem keamanan *Soft Delete* (untuk melindungi integritas data historis).
*   **Manajemen Kategori & SLA**: Pengaturan batas waktu penyelesaian laporan (SLA hari) per kategori masalah (misal: Jalan, Sampah, Listrik).
*   **Alur Kerja & Status Terintegrasi**:
    *   Workflow terstruktur: `Menunggu` ➡️ `Diproses` ➡️ `Selesai` / `Ditolak`.
    *   Unggah bukti foto penyelesaian (opsional) beserta pesan penutup dari petugas.
*   **Audit Trail Lengkap**: Pencatatan otomatis setiap riwayat perubahan status laporan (petugas, waktu, dan alasan perubahan).
*   **Executive Report**: Fitur ekspor laporan kinerja bulanan/tahunan yang dinamis, mengambil data performa dari model `PengaduanRating`.

---

## 🛠️ Tech Stack & Optimasi

*   **Framework Utama**: Laravel v12.x (PHP 8.2+)
*   **Frontend**: Livewire v3.x, Alpine.js, & MaryUI (Blade-based components)
*   **Styling**: Tailwind CSS
*   **Database**: MySQL v8.0
*   **Assets Bundler**: Vite
*   **Image Optimization**: HTML5 Canvas API (Client-side) & GD Library (Server-side) untuk performa tinggi pada koneksi mobile.
*   **Performance Testing**: Locust (Simulasi hingga 500 pengguna aktif)

---

## 📦 Panduan Instalasi & Penggunaan Lokal

### 1. Prasyarat Sistem
Pastikan perangkat Anda sudah terinstal:
*   PHP >= 8.2
*   Composer
*   MySQL / MariaDB
*   Node.js & NPM
*   Python & Pip (hanya untuk load testing dengan Locust)

### 2. Kloning & Pengaturan Awal
```bash
# Clone repository ini (jika belum berada di direktori project)
cd kembaranngadu

# Instal dependensi PHP (Composer)
composer install

# Instal dependensi Javascript (NPM)
npm install

# Salin konfigurasi environment
cp .env.example .env
```

### 3. Konfigurasi Database
Buka file `.env` dan sesuaikan pengaturan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kembaran_ngadu
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seed Database
Jalankan perintah berikut untuk membuat tabel database beserta data awal:
```bash
# Generate application key
php artisan key:generate

# Jalankan migrasi database
php artisan migrate

# Hubungkan direktori storage link
php artisan storage:link
```

### 5. Menjalankan Aplikasi
Buka dua terminal berbeda untuk menjalankan server PHP dan kompilasi asset:

**Terminal 1 (Laravel Dev Server):**
```bash
php artisan serve
```

**Terminal 2 (Vite Assets Watcher):**
```bash
npm run dev
```
Akses aplikasi melalui browser di [http://localhost:8000](http://localhost:8000).

---

## 📊 Pengujian Performa (Load Testing)

Untuk menguji ketahanan server dalam menangani lonjakan pengunjung (misal: 500 user serentak), Anda dapat menggunakan skrip pengujian performa Locust yang sudah disediakan:

1.  Instal Locust di sistem Anda:
    ```bash
    pip install locust
    ```
2.  Jalankan Locust menggunakan berkas `locustfile.py`:
    ```bash
    locust -f locustfile.py
    ```
3.  Buka web panel Locust di browser Anda: [http://localhost:8089](http://localhost:8089)
4.  Masukkan konfigurasi pengujian:
    *   **Number of users**: `500`
    *   **Spawn rate**: `10` atau `20` (jumlah user baru yang bergabung per detik)
    *   **Host**: `http://localhost:8000` (atau URL server produksi Anda)
5.  Klik **"Start swarming"** untuk memulai simulasi pengujian beban.

