<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    {{-- Header UI (Hidden during print) --}}
    <div class="flex flex-col gap-4 mb-8 print:hidden sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Laporan Eksekutif</h1>
            <p class="mt-1 text-sm text-base-content/70">Generate laporan statistik untuk pimpinan</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <x-select wire:model.live="period" :options="[
                ['id' => 'this_month', 'name' => 'Bulan Ini'],
                ['id' => 'last_month', 'name' => 'Bulan Lalu'],
                ['id' => 'this_year', 'name' => 'Tahun Ini'],
                ['id' => 'custom', 'name' => 'Kustom Tanggal'],
            ]" class="rounded-xl select-sm" />
            
            @if($period === 'custom')
                <x-input type="date" wire:model.live="startDate" class="input-sm rounded-xl" />
                <x-input type="date" wire:model.live="endDate" class="input-sm rounded-xl" />
            @endif

            <x-button label="Cetak / Simpan PDF" icon="o-printer" @click="window.print()" class="btn-primary rounded-xl btn-sm font-bold shadow-md" />
        </div>
    </div>

    {{-- The Report Paper --}}
    <div class="bg-white text-slate-900 p-8 sm:p-12 shadow-xl rounded-2xl min-h-[1000px] print:shadow-none print:p-0 print:m-0 print:rounded-none mx-auto max-w-[900px] border border-slate-200 print:border-0 report-paper">
        
        {{-- Kop Surat Resmi --}}
        <table class="w-full border-b-4 border-double border-slate-950 mb-6">
            <tr>
                <td class="w-20 pb-4">
                    @php
                        $appLogo = \App\Models\Setting::get('app_logo');
                    @endphp
                    <img src="{{ $appLogo ? asset('storage/' . $appLogo) : asset('storage/assets/logobanyumas.png') }}" class="w-16 h-16 object-contain mx-auto">
                </td>
                <td class="text-center pb-4 pr-10">
                    <h2 class="text-sm font-bold uppercase tracking-widest leading-tight">Pemerintah Kabupaten Banyumas</h2>
                    <h3 class="text-2xl font-black uppercase leading-tight">{{ \App\Models\Setting::get('instansi_nama') ?? 'Kecamatan Kembaran' }}</h3>
                    <p class="text-[10px] font-medium mt-1">{{ \App\Models\Setting::get('instansi_alamat') ?? 'Jl. Raya Kembaran No. 1, Banyumas, Jawa Tengah' }}</p>
                    <p class="text-[9px] font-bold">Email: {{ \App\Models\Setting::get('instansi_email') ?? '-' }} | Website: kembaran-ngadu.id</p>
                </td>
                <td class="w-20 pb-4 text-right">
                    <img src="{{ asset('storage/assets/logokominfo.png') }}" class="w-16 h-16 object-contain mx-auto">
                </td>
            </tr>
        </table>

        <div class="text-center mb-8">
            <h1 class="text-xl font-black uppercase border-b-2 border-slate-800 inline-block px-4 pb-1">Laporan Ringkasan Pengaduan Masyarakat</h1>
            <p class="text-xs font-bold mt-2 text-slate-600 uppercase">Periode: {{ $data['period_label'] }}</p>
        </div>

        {{-- Section 1: Ringkasan Statistik --}}
        <div class="mb-10">
            <h4 class="text-sm font-black mb-4 flex items-center gap-2 text-slate-800 uppercase tracking-wide">
                <span class="w-1.5 h-4 bg-slate-800 rounded-full"></span>
                I. Ringkasan Statistik Utama
            </h4>
            <div class="grid grid-cols-4 gap-4">
                <div class="p-4 border border-slate-300 rounded-xl text-center">
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Total Aduan</div>
                    <div class="text-3xl font-black text-slate-900">{{ $data['stats']['total'] }}</div>
                </div>
                <div class="p-4 border border-slate-300 rounded-xl text-center">
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Selesai</div>
                    <div class="text-3xl font-black text-green-600">{{ $data['stats']['selesai'] }}</div>
                </div>
                <div class="p-4 border border-slate-300 rounded-xl text-center">
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Dalam Proses</div>
                    <div class="text-3xl font-black text-blue-600">{{ $data['stats']['diproses'] + $data['stats']['menunggu'] }}</div>
                </div>
                <div class="p-4 border border-slate-300 rounded-xl text-center">
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Rata Rating</div>
                    <div class="text-3xl font-black text-yellow-600">{{ number_format($data['stats']['rata_rating'], 1) }}</div>
                </div>
            </div>
        </div>

        {{-- Section 2: Analisis Kepuasan --}}
        <div class="grid grid-cols-2 gap-10 mb-10">
            <div>
                <h4 class="text-sm font-black mb-4 flex items-center gap-2 text-slate-800 uppercase tracking-wide">
                    <span class="w-1.5 h-4 bg-slate-800 rounded-full"></span>
                    II. Distribusi Rating Kepuasan
                </h4>
                <div class="space-y-3">
                    @foreach(range(5, 1) as $i)
                        @php
                            $count = $data['ratingDistribution'][$i] ?? 0;
                            $total = array_sum($data['ratingDistribution']);
                            $percent = $total > 0 ? ($count / $total) * 100 : 0;
                            $colorMap = [5 => 'bg-green-600', 4 => 'bg-green-400', 3 => 'bg-yellow-400', 2 => 'bg-orange-400', 1 => 'bg-red-500'];
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold w-4">{{ $i }}★</span>
                            <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                                <div class="h-full {{ $colorMap[$i] }}" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-400 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-sm font-black mb-4 flex items-center gap-2 text-slate-800 uppercase tracking-wide">
                    <span class="w-1.5 h-4 bg-slate-800 rounded-full"></span>
                    III. Tren & Performa
                </h4>
                <p class="text-xs leading-relaxed text-slate-700 italic">
                    Berdasarkan data periode ini, tingkat penyelesaian pengaduan mencapai 
                    <span class="font-bold">{{ $data['stats']['total'] > 0 ? round(($data['stats']['selesai'] / $data['stats']['total']) * 100) : 0 }}%</span>.
                    Rata-rata warga memberikan nilai kepuasan <span class="font-bold">{{ number_format($data['stats']['rata_rating'], 1) }} dari 5.0</span>, 
                    yang menunjukkan tingkat kepercayaan publik yang 
                    <span class="font-bold">{{ $data['stats']['rata_rating'] >= 4 ? 'Sangat Baik' : ($data['stats']['rata_rating'] >= 3 ? 'Cukup Baik' : 'Perlu Evaluasi') }}</span>.
                </p>
            </div>
        </div>

        {{-- Section 3: Leaderboard Kategori --}}
        <div class="mb-10">
            <h4 class="text-sm font-black mb-4 flex items-center gap-2 text-slate-800 uppercase tracking-wide">
                <span class="w-1.5 h-4 bg-slate-800 rounded-full"></span>
                IV. Top 5 Kategori Terbanyak
            </h4>
            <table class="w-full text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-xs uppercase font-black">
                        <th class="border border-slate-300 p-2 text-center">Rank</th>
                        <th class="border border-slate-300 p-2">Kategori</th>
                        <th class="border border-slate-300 p-2 text-center">Total Aduan</th>
                        <th class="border border-slate-300 p-2 text-center">Selesai</th>
                        <th class="border border-slate-300 p-2 text-center">Waktu Respons</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($data['categoryPerformance'] as $index => $kat)
                    <tr>
                        <td class="border border-slate-300 p-2 text-center font-bold">{{ $index + 1 }}</td>
                        <td class="border border-slate-300 p-2 font-medium">{{ $kat->nama }}</td>
                        <td class="border border-slate-300 p-2 text-center">{{ $kat->total_laporan }}</td>
                        <td class="border border-slate-300 p-2 text-center font-bold text-green-700">{{ $kat->laporan_selesai }}</td>
                        <td class="border border-slate-300 p-2 text-center">{{ round($kat->avg_resolution_time) }} jam</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Appendix: Daftar Laporan --}}
        <div class="mb-10 page-break-before">
            <h4 class="text-sm font-black mb-4 flex items-center gap-2 text-slate-800 uppercase tracking-wide">
                <span class="w-1.5 h-4 bg-slate-800 rounded-full"></span>
                V. Lampiran Detail Pengaduan
            </h4>
            <table class="w-full text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-[10px] uppercase font-black">
                        <th class="border border-slate-300 p-2">Kode</th>
                        <th class="border border-slate-300 p-2">Tanggal</th>
                        <th class="border border-slate-300 p-2">Pelapor</th>
                        <th class="border border-slate-300 p-2">Judul Laporan</th>
                        <th class="border border-slate-300 p-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-[10px]">
                    @foreach($data['laporanList']->take(20) as $lap)
                    <tr>
                        <td class="border border-slate-300 p-2 font-bold">{{ $lap->kode_tracking }}</td>
                        <td class="border border-slate-300 p-2">{{ $lap->created_at->format('d/m/y') }}</td>
                        <td class="border border-slate-300 p-2">{{ $lap->user->name ?? 'Anonim' }}</td>
                        <td class="border border-slate-300 p-2 font-medium truncate max-w-[200px]">{{ $lap->judul }}</td>
                        <td class="border border-slate-300 p-2 text-center uppercase font-bold">{{ $lap->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($data['laporanList']->count() > 20)
                <p class="text-[10px] italic mt-2 text-slate-400">* Menampilkan 20 dari {{ $data['laporanList']->count() }} laporan total.</p>
            @endif
        </div>

        {{-- Signature Section --}}
        <div class="mt-20 flex justify-end px-10">
            <div class="text-center">
                @php
                    $settings = \App\Models\Setting::whereIn('key', ['ttd_jabatan', 'ttd_nama', 'ttd_file'])->pluck('value', 'key');
                @endphp
                <p class="text-xs font-bold uppercase mb-4">
                    Kembaran, {{ now()->isoFormat('D MMMM YYYY') }}<br>
                    {{ $settings['ttd_jabatan'] ?? 'Camat Kembaran' }}
                </p>
                
                @if(isset($settings['ttd_file']) && $settings['ttd_file'])
                    <img src="{{ asset('storage/' . $settings['ttd_file']) }}" alt="Tanda Tangan" class="h-20 mx-auto my-2 object-contain">
                @else
                    <div class="h-20"></div>
                @endif

                <div class="border-b border-slate-800 w-48 mx-auto"></div>
                <p class="text-xs font-bold mt-1 font-black underline">{{ $settings['ttd_nama'] ?? '(............................................)' }}</p>
                <p class="text-[10px] font-bold mt-0.5 text-slate-500">NIP. ............................................</p>
            </div>
        </div>
    </div>

    <style>
        .report-paper {
            font-family: 'Times New Roman', Times, serif !important;
        }
        @media print {
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .px-4, .py-8 {
                padding: 0 !important;
            }
            .max-w-7xl, .max-w-\[900px\] {
                max-width: 100% !important;
                width: 100% !important;
            }
            .shadow-xl, .border {
                box-shadow: none !important;
                border-color: #cbd5e1 !important;
            }
            .page-break-before {
                page-break-before: always;
            }
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</div>
