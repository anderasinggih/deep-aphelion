<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Pengaduan\StatusUpdateNotification;

class PengaduanManager extends Component
{
    use WithPagination, WithFileUploads;
 
    public function mount()
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'petugas']), 403);
    }

    public $search = '';
    public $statusFilter = '';
    public $kategoriFilter = '';
    public $startDate = '';
    public $endDate = '';
    public $orderBy = 'latest';

    public $selectedPengaduanId = null;

    // Update Status Modal State
    public $updateModal = false;
    public $update_status = '';
    public $update_foto;
    public $update_keterangan = '';

    public function goToDetail($kode_tracking)
    {
        return $this->redirect(route('admin.pengaduan.detail', $kode_tracking), navigate: true);
    }

    // Referral / Link Properties
    public $linkModal = false;
    public $searchLinkedQuery = '';
    public $linkedReports = [];
    public $pengaduanToLink = null;

    public function openLinkModal($id)
    {
        $this->pengaduanToLink = Pengaduan::findOrFail($id);
        $this->searchLinkedQuery = '';
        $this->linkedReports = [];
        $this->linkModal = true;
    }

    public function updatedSearchLinkedQuery()
    {
        if (strlen($this->searchLinkedQuery) < 3 || !$this->pengaduanToLink) {
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
        $pengaduan = $this->pengaduanToLink;

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
            'keterangan_admin' => 'Laporan dirujuk ke laporan selesai (' . $targetReport->kode_tracking . ')',
            'foto_bukti' => $targetReport->foto_penyelesaian,
        ]);

        // Kirim Notifikasi Internal (Database)
        if ($pengaduan->user) {
            $pengaduan->user->notify(new StatusUpdateNotification($pengaduan));
        }

        // Kirim Email Update Status ke Pelapor
        if ($pengaduan->user && $pengaduan->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($pengaduan->user->email)->send(new \App\Mail\Pengaduan\StatusUpdate($pengaduan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email update status (Manager): ' . $e->getMessage());
            }
        }

        $this->linkModal = false;
        $this->pengaduanToLink = null;
        session()->flash('success', 'Laporan berhasil dirujuk dan diselesaikan.');
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
            'keterangan_admin' => $this->update_keterangan,
            'foto_bukti' => $path,
        ]);

        $this->reset('updateModal', 'update_status', 'update_foto', 'update_keterangan', 'selectedPengaduanId');
        session()->flash('success', 'Status laporan berhasil diperbarui.');
    }

    public function exportCsv()
    {
        $query = Pengaduan::with(['user', 'kategori'])->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_tracking', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
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
        $csvFileName = 'laporan-pengaduan-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Kode Tracking', 'Tanggal Masuk', 'Pelapor', 'Judul Laporan', 
            'Kategori', 'Lokasi', 'Status', 'Rating IKM', 'Komentar Pelayanan'
        ];

        $callback = function() use($pengaduans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pengaduans as $p) {
                fputcsv($file, [
                    $p->kode_tracking,
                    $p->created_at->format('Y-m-d H:i'),
                    $p->user->name ?? 'Anonim',
                    $p->judul,
                    $p->kategori->nama ?? '-',
                    $p->lokasi_kejadian,
                    $p->status,
                    $p->rating ?? '-',
                    $p->rating_komentar ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $query = Pengaduan::with(['user', 'kategori']);

        // Sorting Logic: Selalu taruh 'Ditolak' di paling bawah
        $query->orderByRaw("CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END ASC");

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
            $query->withCount('dukungans')->orderBy('dukungans_count', 'desc');
        } else {
            $query->latest();
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_tracking', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
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