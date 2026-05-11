<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Notifications\Pengaduan\StatusUpdateNotification;

class PengaduanManager extends Component
{
    use WithPagination, WithFileUploads;
 
    // WA Blast
    public bool $waBlastModal = false;
    public int $waBlastCurrentIndex = 0;
    public array $waBlastQueue = [];

    public function mount()
    {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'admin', 'petugas']), 403);
    }

    #[Url]
    public $search = '';
    public $statusFilter = '';
    public $kategoriFilter = '';
    public $startDate = '';
    public $endDate = '';
    public $orderBy = 'smart';
    
    // Bulk Selection
    public $bulkMode = false;
    public $selectedIds = [];
    public $selectAll = false;

    public $selectedPengaduanId = null;
    public bool $filtersOpen = false;

    // Update Status Modal State
    public $updateModal = false;
    public $update_status = '';
    public $update_foto;
    public $update_keterangan = '';
    public $waLink = null;
    public $updatedKodeTracking = null;

    public function goToDetail($kode_tracking)
    {
        return $this->redirect(route('admin.pengaduan.detail', $kode_tracking), navigate: true);
    }

    // Referral / Link Properties
    public $linkModal = false;
    public $searchLinkedQuery = '';
    public $linkedReports = [];
    public $pengaduanToLink = null;

    public function openLinkModal($id = null)
    {
        if ($id) {
            $this->selectedIds = [$id];
        }

        if (empty($this->selectedIds)) {
            session()->flash('error', 'Pilih minimal satu laporan untuk dirujuk.');
            return;
        }

        $this->searchLinkedQuery = '';
        $this->linkedReports = [];
        $this->linkModal = true;
    }

    public function updatedSearchLinkedQuery()
    {
        if (strlen($this->searchLinkedQuery) < 3) {
            $this->linkedReports = [];
            return;
        }

        $this->linkedReports = Pengaduan::where('status', 'selesai')
            ->whereNull('linked_id') // Hanya bisa merujuk ke laporan yang selesai "asli" (bukan rujukan juga)
            ->where('id', '!=', $this->pengaduanToLink->id)
            ->where(function($q) {
                $q->where('judul', 'like', '%' . $this->searchLinkedQuery . '%')
                  ->orWhere('kode_tracking', 'like', '%' . $this->searchLinkedQuery . '%');
            })
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function linkToReport($targetId)
    {
        $targetReport = Pengaduan::findOrFail($targetId);
        $count = 0;

        foreach ($this->selectedIds as $id) {
            $pengaduan = Pengaduan::find($id);
            if (!$pengaduan || $pengaduan->status === 'selesai' || $pengaduan->status === 'ditolak') continue;

            $oldStatus = $pengaduan->status;
            
            $pengaduan->linked_id = $targetReport->id;
            $pengaduan->status = 'selesai';
            $pengaduan->foto_penyelesaian = $targetReport->foto_penyelesaian;
            $pengaduan->pesan_penutup = "Masalah pada laporan ini telah diselesaikan sebelumnya dengan merujuk pada laporan: " . $targetReport->kode_tracking . ". Seluruh keterangan penyelesaian ini adalah hasil tindak lanjut dari laporan referensi tersebut. \n\n" . $targetReport->pesan_penutup;
            $pengaduan->save();

            PengaduanHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'user_id' => auth()->id(),
                'status_sebelumnya' => $oldStatus,
                'status_baru' => 'selesai',
                'keterangan_admin' => 'Laporan dirujuk ke laporan selesai (' . $targetReport->kode_tracking . ') [Bulk Action]',
                'foto_bukti' => $targetReport->foto_penyelesaian,
            ]);

            // Kirim Notifikasi Internal (Database)
            if ($pengaduan->user) {
                $pengaduan->user->notify(new \App\Notifications\Pengaduan\StatusUpdateNotification($pengaduan));
            }

            $count++;
        }

        $this->linkModal = false;
        $this->selectedIds = [];
        $this->selectAll = false;
        session()->flash('success', $count . ' laporan berhasil dirujuk dan diselesaikan.');
    }

    public function toggleAll()
    {
        if (!$this->bulkMode) return;

        if ($this->selectAll) {
            $this->selectedIds = [];
            $this->selectAll = false;
        } else {
            $this->selectedIds = $this->getFilteredIds()
                ->map(fn($id) => (string)$id)
                ->toArray();
            $this->selectAll = true;
        }
    }

    public function toggleSelection($id)
    {
        $id = (string)$id;
        if (in_array($id, $this->selectedIds)) {
            $this->selectedIds = array_diff($this->selectedIds, [$id]);
        } else {
            $this->selectedIds[] = $id;
        }

        // Sync SelectAll status
        $totalVisible = $this->getFilteredIds()->count();
        $this->selectAll = count($this->selectedIds) === $totalVisible && $totalVisible > 0;
    }

    protected function getFilteredIds()
    {
        $query = Pengaduan::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_tracking', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi_kejadian', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('no_wa', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('kategori', function ($kq) {
                      $kq->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->kategoriFilter) {
            $query->where('kategori_id', $this->kategoriFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return $query->pluck('id');
    }

    public function startWaBlast()
    {
        if (empty($this->selectedIds)) {
            session()->flash('error', 'Pilih minimal satu laporan.');
            return;
        }

        $this->waBlastQueue = [];
        $reports = Pengaduan::whereIn('id', $this->selectedIds)->with('user')->get();

        foreach ($reports as $report) {
            $link = $report->generateWaLink();
            if ($link) {
                $this->waBlastQueue[] = [
                    'id' => $report->id,
                    'kode' => $report->kode_tracking,
                    'nama' => $report->user->name ?? 'Warga',
                    'link' => $link
                ];
            }
        }

        if (empty($this->waBlastQueue)) {
            session()->flash('error', 'Tidak ada nomor WhatsApp yang valid dari laporan yang dipilih.');
            return;
        }

        $this->waBlastCurrentIndex = 0;
        $this->waBlastModal = true;
    }

    public function nextWaBlast()
    {
        if ($this->waBlastCurrentIndex < count($this->waBlastQueue) - 1) {
            $this->waBlastCurrentIndex++;
        } else {
            $this->waBlastModal = false;
            session()->flash('success', 'Seluruh antrean WhatsApp berhasil diproses.');
        }
    }

    public function openUpdateStatusModal($id, $newStatus)
    {
        $this->selectedPengaduanId = $id;
        $this->update_status = $newStatus;
        $this->updateModal = true;
    }

    public function saveStatusUpdate()
    {
        $rules = [
            'selectedPengaduanId' => 'required|exists:pengaduans,id',
            'update_status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'update_keterangan' => 'nullable|string',
            'update_foto' => 'nullable|image|max:5120',
        ];

        if ($this->update_status === 'selesai' || $this->update_status === 'ditolak') {
            $rules['update_keterangan'] = 'required|string|min:5';
        }
        if ($this->update_status === 'selesai') {
            $rules['update_foto'] = 'required|image|max:5120';
        }

        $this->validate($rules);

        $pengaduan = Pengaduan::findOrFail($this->selectedPengaduanId);
        $path = null;

        if ($this->update_foto) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($this->update_foto->getRealPath());
            
            // Resize to 1200px max width while maintaining aspect ratio
            $image->scale(width: 1200);
            
            $filename = 'bukti_selesai/' . $this->update_foto->hashName();
            $encoded = $image->toJpeg(75); // Compress to 75% quality
            
            Storage::disk('public')->put($filename, (string) $encoded);
            $path = $filename;
        }

        $oldStatus = $pengaduan->status;
        $isUpdateOnly = ($oldStatus === $this->update_status);
        
        $pengaduan->status = $this->update_status;
        
        // Simpan pesan penutup jika status selesai/ditolak
        if ($this->update_status === 'selesai' || $this->update_status === 'ditolak') {
            $pengaduan->pesan_penutup = $this->update_keterangan;
        }

        $pengaduan->save();

        // Kirim Notifikasi Internal (Database)
        if ($pengaduan->user) {
            $pengaduan->user->notify(new StatusUpdateNotification($pengaduan));
        }

        // Kirim Email Update Status ke Pelapor
        if ($pengaduan->user && $pengaduan->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($pengaduan->user->email)->send(new \App\Mail\Pengaduan\StatusUpdate($pengaduan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email update status: ' . $e->getMessage());
            }
        }

        PengaduanHistory::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $this->update_status,
            'keterangan_admin' => $isUpdateOnly ? '[Update Progress] ' . $this->update_keterangan : $this->update_keterangan,
            'foto_bukti' => $path,
        ]);


        $this->waLink = $pengaduan->generateWaLink($isUpdateOnly ? $this->update_keterangan : null);
        $this->updatedKodeTracking = $pengaduan->kode_tracking;

        $this->reset('updateModal', 'update_status', 'update_foto', 'update_keterangan', 'selectedPengaduanId');
        session()->flash('success', 'Status laporan berhasil diperbarui.');
    }
    public function clearWaLink()
    {
        $this->waLink = null;
        $this->updatedKodeTracking = null;
    }

    public function exportExcel()
    {
        $query = Pengaduan::with(['user', 'kategori'])->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_tracking', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi_kejadian', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('no_wa', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('kategori', function ($kq) {
                      $kq->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        if ($this->kategoriFilter) {
            $query->where('kategori_id', $this->kategoriFilter);
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $pengaduans = $query->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $columns = [
            'Kode Tracking', 'Tanggal Masuk', 'Pelapor', 'Judul Laporan', 
            'Kategori', 'Lokasi', 'Status', 'Rating IKM', 'Komentar Pelayanan'
        ];
        
        // Header
        foreach ($columns as $i => $title) {
            $sheet->setCellValue([$i + 1, 1], $title);
        }
        
        // Data
        foreach ($pengaduans as $rowIndex => $p) {
            $sheet->setCellValue([1, $rowIndex + 2], $p->kode_tracking);
            $sheet->setCellValue([2, $rowIndex + 2], $p->created_at->format('Y-m-d H:i'));
            $sheet->setCellValue([3, $rowIndex + 2], $p->user->name ?? 'Anonim');
            $sheet->setCellValue([4, $rowIndex + 2], $p->judul);
            $sheet->setCellValue([5, $rowIndex + 2], $p->kategori->nama ?? '-');
            $sheet->setCellValue([6, $rowIndex + 2], $p->lokasi_kejadian);
            $sheet->setCellValue([7, $rowIndex + 2], ucfirst($p->status));
            $sheet->setCellValue([8, $rowIndex + 2], $p->rating ?? '-');
            $sheet->setCellValue([9, $rowIndex + 2], $p->rating_komentar ?? '-');
        }
        
        // Styling - Rapikan
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDEEAF6'); // Light blue background for header

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-pengaduan-' . now()->format('Ymd_His') . '.xlsx';
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'kategoriFilter', 'startDate', 'endDate', 'orderBy']);
    }

    public function forceDelete($id)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        
        $pengaduan = Pengaduan::withTrashed()->findOrFail($id);
        
        // Delete related files
        if ($pengaduan->foto_bukti) {
            foreach ($pengaduan->foto_bukti as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }
        if ($pengaduan->foto_penyelesaian) {
            Storage::disk('public')->delete($pengaduan->foto_penyelesaian);
        }

        // Clean up relations
        $pengaduan->histories()->delete();
        $pengaduan->dukungans()->delete();
        $pengaduan->komentars()->delete();
        
        $pengaduan->forceDelete();

        session()->flash('success', 'Laporan #' . $pengaduan->kode_tracking . ' telah dihapus permanen dari sistem.');
    }

    public function render()
    {
        $query = Pengaduan::with(['user', 'kategori'])->withCount('dukungans');

        if ($this->orderBy === 'latest') {
            $query->latest();
        } elseif ($this->orderBy === 'oldest') {
            $query->oldest();
        } elseif ($this->orderBy === 'priority') {
            $query->orderByRaw("CASE 
                WHEN prioritas = 'tinggi' THEN 1 
                WHEN prioritas = 'sedang' THEN 2 
                WHEN prioritas = 'rendah' THEN 3 
                ELSE 4 END ASC")
                ->latest();
        } elseif ($this->orderBy === 'upvotes') {
            $query->orderBy('dukungans_count', 'desc');
        } elseif ($this->orderBy === 'smart') {
            // SMART SORTING: Priority & Support first, then status-based
            $query->orderByRaw("CASE 
                WHEN status = 'menunggu' AND (dukungans_count >= 50 OR prioritas = 'tinggi') THEN 1
                WHEN status = 'menunggu' THEN 2
                WHEN status = 'diproses' THEN 3
                WHEN status = 'selesai' THEN 4
                WHEN status = 'ditolak' THEN 5
                ELSE 6 END ASC")
            ->latest();
        } else {
            $query->latest();
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_tracking', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi_kejadian', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('no_wa', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('kategori', function ($kq) {
                      $kq->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        if ($this->kategoriFilter) {
            $query->where('kategori_id', $this->kategoriFilter);
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return view('livewire.admin.pengaduan-manager', [
            'pengaduans' => $query->paginate(15),
            'kategoris' => \App\Models\Kategori::all()
        ])->layout('layouts.app');
    }
}