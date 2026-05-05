<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pengaduan;
use App\Models\PengaduanHistory;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Pengaduan\StatusUpdateNotification;

class PengaduanDetail extends Component
{
    use WithFileUploads;

    public Pengaduan $pengaduan;

    public $updateModal = false;
    public $update_status = '';
    public $update_foto;
    public $update_keterangan = '';
    public $catatan_internal = '';

    // Referral / Link Properties
    public $linkModal = false;
    public $searchLinkedQuery = '';
    public $linkedReports = [];

    public function mount($kode_tracking)
    {
        $this->pengaduan = Pengaduan::with(['user', 'kategori', 'histories.user', 'linkedReport'])->where('kode_tracking', $kode_tracking)->firstOrFail();
        $this->catatan_internal = $this->pengaduan->catatan_internal;
    }

    public function updatedSearchLinkedQuery()
    {
        if (strlen($this->searchLinkedQuery) < 3) {
            $this->linkedReports = [];
            return;
        }

        $this->linkedReports = Pengaduan::where('status', 'selesai')
            ->whereNull('linked_id') // Hanya bisa merujuk ke laporan yang selesai "asli" (bukan rujukan juga)
            ->where('id', '!=', $this->pengaduan->id)
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

        $oldStatus = $this->pengaduan->status;
        
        $this->pengaduan->linked_id = $targetReport->id;
        $this->pengaduan->status = 'selesai';
        $this->pengaduan->foto_penyelesaian = $targetReport->foto_penyelesaian;
        $this->pengaduan->pesan_penutup = "Masalah pada laporan ini telah diselesaikan sebelumnya dengan merujuk pada laporan: " . $targetReport->kode_tracking . ". Seluruh keterangan penyelesaian ini adalah hasil tindak lanjut dari laporan referensi tersebut. \n\n" . $targetReport->pesan_penutup;
        $this->pengaduan->save();

        PengaduanHistory::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => 'selesai',
            'keterangan_admin' => 'Laporan dirujuk ke laporan selesai (' . $targetReport->kode_tracking . ')',
            'foto_bukti' => $targetReport->foto_penyelesaian,
        ]);

        // Kirim Notifikasi Internal (Database)
        if ($this->pengaduan->user) {
            $this->pengaduan->user->notify(new StatusUpdateNotification($this->pengaduan));
        }

        // Kirim Email Update Status ke Pelapor
        if ($this->pengaduan->user && $this->pengaduan->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($this->pengaduan->user->email)->send(new \App\Mail\Pengaduan\StatusUpdate($this->pengaduan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email update status (Detail): ' . $e->getMessage());
            }
        }

        $this->linkModal = false;
        session()->flash('success', 'Laporan berhasil dirujuk dan diselesaikan.');
        $this->pengaduan->refresh();
    }

    public function saveCatatanInternal()
    {
        $this->validate([
            'catatan_internal' => 'nullable|string|max:2000'
        ]);

        $this->pengaduan->catatan_internal = $this->catatan_internal;
        $this->pengaduan->save();

        session()->flash('success_catatan', 'Catatan internal berhasil disimpan.');
    }

    public function openUpdateStatusModal($newStatus)
    {
        $this->update_status = $newStatus;
        $this->updateModal = true;
    }

    public function saveStatusUpdate()
    {
        $rules = [
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

        $oldStatus = $this->pengaduan->getOriginal('status') ?? $this->pengaduan->status;
        $this->pengaduan->status = $this->update_status;
        
        if ($this->update_status === 'selesai' || $this->update_status === 'ditolak') {
            $this->pengaduan->pesan_penutup = $this->update_keterangan;
            if ($this->update_status === 'selesai') {
                $this->pengaduan->foto_penyelesaian = $path;
            }
        }
        
        $this->pengaduan->save();

        // Kirim Notifikasi Internal (Database)
        if ($this->pengaduan->user) {
            $this->pengaduan->user->notify(new StatusUpdateNotification($this->pengaduan));
        }

        // Kirim Email Update Status ke Pelapor
        if ($this->pengaduan->user && $this->pengaduan->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($this->pengaduan->user->email)->send(new \App\Mail\Pengaduan\StatusUpdate($this->pengaduan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email update status (Detail): ' . $e->getMessage());
            }
        }

        PengaduanHistory::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $oldStatus,
            'status_baru' => $this->update_status,
            'keterangan_admin' => $this->update_keterangan,
            'foto_bukti' => $path,
        ]);

        // Generate WA notification link
        $labelMap = ['menunggu' => 'Menunggu', 'diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
        $statusLabel = $labelMap[$this->update_status] ?? $this->update_status;
        $noWa = preg_replace('/[^0-9]/', '', $this->pengaduan->user->no_wa ?? '');
        if ($noWa && str_starts_with($noWa, '0')) {
            $noWa = '62' . substr($noWa, 1);
        }

        if ($noWa) {
            $pesan = "Yth. {$this->pengaduan->user->name},\n\nLaporan Anda dengan kode *{$this->pengaduan->kode_tracking}* mengenai \"_{$this->pengaduan->judul}_\" telah diperbarui.\n\nStatus saat ini: *{$statusLabel}*";
            if ($this->update_keterangan) {
                $pesan .= "\nKeterangan: _{$this->update_keterangan}_";
            }
            $pesan .= "\n\nKecamatan Kembaran — Kembaran Ngadu";
            $this->waLink = 'https://wa.me/' . $noWa . '?text=' . rawurlencode($pesan);
        }

        $this->reset('updateModal', 'update_status', 'update_foto', 'update_keterangan');
        session()->flash('success', 'Status laporan berhasil diperbarui.');
        $this->pengaduan->refresh();
    }

    public $waLink = null;

    public function clearWaLink()
    {
        $this->waLink = null;
    }

    public function render()
    {
        return view('livewire.admin.pengaduan-detail')->layout('layouts.app');
    }
}