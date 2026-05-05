@php
    $logo = asset('storage/assets/logobanyumas.png');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Laporan - {{ $pengaduan->kode_tracking }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background-color: #fff;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }
        .kop-teks {
            text-align: center;
            flex-grow: 1;
        }
        .kop-teks h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-teks h1 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .kop-teks p { margin: 2px 0; font-size: 12px; }

        .judul-tt {
            text-align: center;
            text-decoration: underline;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
            font-size: 14px;
        }
        .label { width: 150px; font-weight: bold; }
        .titik-dua { width: 10px; }

        .tracking-box {
            border: 2px solid #000;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            background-color: #f9f9f9;
        }
        .tracking-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .barcode-placeholder {
            margin-top: 10px;
            font-size: 10px;
            color: #666;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd {
            text-align: center;
            width: 250px;
        }
        .ttd p { margin: 0; font-size: 14px; }
        .ttd .space { height: 70px; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
        
        .btn-print {
            background-color: #1a56db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; padding: 20px;">
        <button class="btn-print" onclick="window.print()">CETAK SEKARANG</button>
        <p style="font-size: 12px; color: #666;">Gunakan menu "Save as PDF" di dialog print jika ingin menyimpan sebagai file.</p>
    </div>

    <div class="kop-surat">
        <img src="{{ $logo }}" class="logo" alt="Logo Daerah">
        <div class="kop-teks">
            <h2>Pemerintah Kabupaten Banyumas</h2>
            <h1>Kecamatan Kembaran</h1>
            <p>Jl. Raya Kembaran No. 1, Kembaran, Banyumas, Jawa Tengah 53182</p>
            <p>Email: kecamatan.kembaran@banyumaskab.go.id | Website: kembaran.banyumaskab.go.id</p>
        </div>
    </div>

    <div class="judul-tt">Tanda Terima Laporan Pengaduan</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pelapor</td>
            <td class="titik-dua">:</td>
            <td>{{ $pengaduan->user->name }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="titik-dua">:</td>
            <td>{{ $pengaduan->user->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lapor</td>
            <td class="titik-dua">:</td>
            <td>{{ $pengaduan->created_at->format('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td class="titik-dua">:</td>
            <td>{{ $pengaduan->kategori->nama }}</td>
        </tr>
        <tr>
            <td class="label">Judul Laporan</td>
            <td class="titik-dua">:</td>
            <td><strong>{{ $pengaduan->judul }}</strong></td>
        </tr>
        <tr>
            <td class="label">Lokasi Kejadian</td>
            <td class="titik-dua">:</td>
            <td>{{ $pengaduan->lokasi_kejadian }}</td>
        </tr>
    </table>

    <div class="tracking-box">
        <p style="margin: 0 0 10px 0; font-weight: bold; font-size: 12px;">SIMPAN KODE TRACKING INI UNTUK MENGECEK PROGRES LAPORAN:</p>
        <div class="tracking-code">{{ $pengaduan->kode_tracking }}</div>
        <div class="barcode-placeholder">
            {{-- Simple SVG Barcode (Code 128 style representation) --}}
            <svg width="200" height="40">
                @for($i=0; $i<40; $i++)
                    <rect x="{{ $i*5 }}" y="0" width="{{ rand(1, 4) }}" height="40" fill="black" />
                @endfor
            </svg>
            <br>
            *Scan kode ini atau masukkan secara manual di website Kembaran Ngadu
        </div>
    </div>

    <p style="font-size: 13px; line-height: 1.5;">
        <strong>Catatan:</strong> Bukti ini merupakan tanda terima sah bahwa laporan Anda telah terdaftar dalam sistem Kembaran Ngadu. 
        Petugas kami akan melakukan verifikasi lapangan maksimal dalam 3x24 jam kerja. Anda dapat memantau perkembangan tindak lanjut 
        secara real-time melalui website dengan memasukkan kode tracking di atas.
    </p>

    <div class="footer">
        <div class="ttd">
            <p>Kembaran, {{ now()->isoFormat('D MMMM YYYY') }}</p>
            <p>{{ $ttd['jabatan'] }}</p>
            
            @if(!empty($ttd['file']))
                <img src="{{ asset('storage/' . $ttd['file']) }}" alt="Tanda Tangan" style="height: 70px; margin: 10px auto; display: block;">
            @else
                <div class="space"></div>
            @endif

            <p style="text-decoration: underline;"><strong>{{ !empty($ttd['nama']) ? $ttd['nama'] : '( ________________________ )' }}</strong></p>
            <p style="font-size: 11px;">Dicetak secara otomatis oleh sistem</p>
        </div>
    </div>

    <script>
        // Auto print if opened with ?autoprint=1
        if (window.location.search.indexOf('autoprint=1') > -1) {
            window.print();
        }
    </script>
</body>
</html>
