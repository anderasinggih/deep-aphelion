<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Laporan - {{ $settings['instansi_nama'] ?? 'Kecamatan Kembaran' }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; background: white; }
        .kop-table { width: 100%; border-bottom: 4px double #000; margin-bottom: 16px; }
        .kop-logo { width: 80px; }
        .kop-text { text-align: center; padding: 0 16px; }
        .kop-text h1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
        .kop-text h2 { font-size: 16pt; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin: 4px 0; }
        .kop-text p { font-size: 9pt; margin: 2px 0; }
        .judul-doc { text-align: center; margin: 14px 0; }
        .judul-doc h3 { font-size: 13pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin: 0; }
        .filter-info { font-size: 9pt; background: #f5f5f5; border: 1px solid #ddd; padding: 6px 12px; margin-bottom: 14px; }
        table.data-table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        table.data-table th { background: #e8e8e8; font-weight: bold; border: 1px solid #888; padding: 5px 6px; text-align: left; }
        table.data-table td { border: 1px solid #999; padding: 4px 6px; vertical-align: top; }
        table.data-table tr:nth-child(even) td { background: #fafafa; }
        .badge-status { font-weight: bold; text-transform: uppercase; font-size: 8pt; }
        .ttd-area { margin-top: 32px; display: flex; justify-content: flex-end; }
        .ttd-box { text-align: center; width: 220px; }
        .ttd-line { border-bottom: 1px solid #000; margin: 48px 16px 4px; }
        .no-print { display: none; }
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            @page { size: A4 landscape; margin: 1.2cm; }
        }
        @media screen {
            body { background: #e5e7eb; padding: 20px; }
            .print-container { background: white; max-width: 1200px; margin: 0 auto; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .no-print { display: flex; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="print-container">

    {{-- Kop Surat Resmi --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ isset($settings['app_logo']) ? asset('storage/' . $settings['app_logo']) : asset('storage/assets/logobanyumas.png') }}" alt="Logo" style="width:72px; height:72px; object-fit:contain;"
                    onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/4/4e/Lambang_Kabupaten_Banyumas.png'">
            </td>
            <td class="kop-text">
                <h1>Pemerintah Kabupaten Banyumas</h1>
                <h2>{{ $settings['instansi_nama'] ?? 'Kecamatan Kembaran' }}</h2>
                <p>{{ $settings['instansi_alamat'] ?? 'Jl. Kyai Kembar No. 17, Kembaran, Kabupaten Banyumas' }}</p>
                <p>Telepon: {{ $settings['instansi_telepon'] ?? '-' }} &nbsp;|&nbsp; Email: {{ $settings['instansi_email'] ?? '-' }}</p>
            </td>
            <td class="kop-logo" style="text-align: right;">
                @if(isset($settings['app_logo_sekunder']))
                    <img src="{{ asset('storage/' . $settings['app_logo_sekunder']) }}" alt="Logo Sekunder" style="width:72px; height:72px; object-fit:contain;">
                @else
                     <img src="{{ asset('storage/assets/logokominfo.png') }}" alt="Kominfo" style="width:72px; height:72px; object-fit:contain;">
                @endif
            </td>
        </tr>
    </table>

    {{-- Judul Dokumen --}}
    <div class="judul-doc">
        <h3>Laporan Rekapitulasi Data Pengaduan Masyarakat</h3>
        <p style="font-size:10pt; margin-top:4px;">
            Dicetak: {{ now()->isoFormat('D MMMM YYYY') }} &nbsp;&nbsp;|&nbsp;&nbsp; Oleh: {{ auth()->user()->name }}
        </p>
    </div>

    {{-- Info Filter --}}
    @php
        $labelStatus = ['menunggu'=>'Menunggu','diproses'=>'Sedang Diproses','selesai'=>'Selesai','ditolak'=>'Ditolak'];
    @endphp
    <div class="filter-info">
        <strong>Filter Aktif:</strong>
        Status: {{ isset($filterInfo['status']) && $filterInfo['status'] ? ($labelStatus[$filterInfo['status']] ?? $filterInfo['status']) : 'Semua' }}
        &nbsp;|&nbsp;
        Periode: {{ isset($filterInfo['start_date']) && $filterInfo['start_date'] ? \Carbon\Carbon::parse($filterInfo['start_date'])->isoFormat('D MMMM YYYY') : 'Awal' }}
        &nbsp;&ndash;&nbsp;
        {{ isset($filterInfo['end_date']) && $filterInfo['end_date'] ? \Carbon\Carbon::parse($filterInfo['end_date'])->isoFormat('D MMMM YYYY') : now()->isoFormat('D MMMM YYYY') }}
        &nbsp;|&nbsp;
        <strong>Jumlah Data: {{ $pengaduans->count() }} laporan</strong>
    </div>

    {{-- Tabel Data --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:32px; text-align:center;">No</th>
                <th style="width:90px;">Kode Tracking</th>
                <th style="width:70px;">Tgl Masuk</th>
                <th style="width:120px;">Pelapor / NIK</th>
                <th style="width:90px;">Kategori</th>
                <th>Judul &amp; Lokasi Kejadian</th>
                <th style="width:70px; text-align:center;">Status</th>
                <th style="width:120px;">Keterangan / Hasil</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduans as $index => $pengaduan)
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td style="font-family: monospace; font-size:8pt;">{{ $pengaduan->kode_tracking }}</td>
                <td>
                    {{ $pengaduan->created_at->format('d/m/Y') }}<br>
                    <span style="font-size:8pt; color:#555;">{{ $pengaduan->created_at->format('H:i') }} WIB</span>
                </td>
                <td>
                    <span style="font-weight:bold;">{{ $pengaduan->is_anonymous ? 'ANONIM' : $pengaduan->user->name }}</span>
                    @if(!$pengaduan->is_anonymous)
                    <br><span style="font-size:8pt; color:#555;">{{ $pengaduan->user->nik ?? '-' }}</span>
                    @endif
                </td>
                <td>{{ $pengaduan->kategori->nama ?? '-' }}</td>
                <td>
                    <div style="font-weight:bold; margin-bottom:2px;">{{ $pengaduan->judul }}</div>
                    <div style="font-size:8pt; color:#555;">{{ $pengaduan->lokasi_kejadian }}</div>
                </td>
                <td style="text-align:center;" class="badge-status">
                    @php
                        $color = '#555';
                        if($pengaduan->status == 'selesai') $color = '#15803d';
                        if($pengaduan->status == 'diproses') $color = '#1d4ed8';
                        if($pengaduan->status == 'menunggu') $color = '#a16207';
                        if($pengaduan->status == 'ditolak') $color = '#b91c1c';
                    @endphp
                    <span style="color: {{ $color }}">{{ strtoupper($pengaduan->status) }}</span>
                </td>
                <td style="font-size:8pt;">
                    @if($pengaduan->status === 'selesai' && $pengaduan->pesan_penutup)
                        {{ Str::limit($pengaduan->pesan_penutup, 80) }}
                    @elseif($pengaduan->status === 'ditolak')
                        @php $lastHistory = $pengaduan->histories->where('status_baru','ditolak')->last(); @endphp
                        {{ $lastHistory ? Str::limit($lastHistory->keterangan_admin, 80) : '-' }}
                    @elseif($pengaduan->status === 'diproses')
                        Tengah ditindaklanjuti.
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:20px; color:#777; font-style:italic;">
                    Tidak ada data laporan yang sesuai kriteria filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd-area">
        <div class="ttd-box">
            <p>Kembaran, {{ now()->isoFormat('D MMMM YYYY') }}</p>
            <p style="font-weight:bold; margin-top:4px;">{{ $settings['ttd_jabatan'] ?? 'Camat Kembaran' }}</p>
            
            @if(isset($settings['ttd_file']))
                <img src="{{ asset('storage/' . $settings['ttd_file']) }}" alt="Tanda Tangan" style="height: 70px; margin: 10px auto; display: block;">
            @else
                <div class="ttd-line"></div>
            @endif

            <p style="font-weight:bold; text-decoration: underline;">{{ $settings['ttd_nama'] ?? '(............................................)' }}</p>
        </div>
    </div>

</div>

{{-- Tombol Layar (tidak dicetak) --}}
<div class="no-print" style="justify-content:center; gap:12px; padding:20px; max-width:1000px; margin:0 auto;">
    <x-button label="Tutup" onclick="window.close()" class="btn-ghost" />
    <x-button label="🖨 Cetak Dokumen" onclick="window.print()" class="btn-primary text-white" />
</div>

</body>
</html>
