<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Pengaduan extends Model
{
    /** @use HasFactory<\Database\Factories\PengaduanFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_tracking',
        'user_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'tanggal_kejadian',
        'prioritas',
        'harapan_pelapor',
        'foto_bukti',
        'lokasi_kejadian',
        'latitude',
        'longitude',
        'is_anonymous',
        'is_private',
        'status',
        'rating',
        'rating_pelayanan',
        'rating_respon',
        'rating_kompetensi',
        'rating_fasilitas',
        'rating_komentar',
        'foto_penyelesaian',
        'pesan_penutup',
        'catatan_internal',
        'linked_id',
    ];

    protected $casts = [
        'foto_bukti' => 'array',
        'is_anonymous' => 'boolean',
        'is_private' => 'boolean',
        'tanggal_kejadian' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id')->withTrashed();
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class)->withTrashed();
    }

    public function histories()
    {
        return $this->hasMany(PengaduanHistory::class);
    }

    public function dukungans()
    {
        return $this->hasMany(PengaduanDukungan::class);
    }

    public function komentars()
    {
        return $this->hasMany(PengaduanKomentar::class);
    }

    public function linkedReport()
    {
        return $this->belongsTo(Pengaduan::class, 'linked_id');
    }

    public function childReports()
    {
        return $this->hasMany(Pengaduan::class, 'linked_id');
    }

    public function generateWaLink($customMessage = null)
    {
        if (!$this->user || !$this->user->no_wa) return null;

        $labelMap = ['menunggu' => 'Menunggu', 'diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
        $statusLabel = $labelMap[$this->status] ?? $this->status;
        
        $noWa = preg_replace('/[^0-9]/', '', $this->user->no_wa);
        if (str_starts_with($noWa, '0')) {
            $noWa = '62' . substr($noWa, 1);
        }

        $linkDetail = route('pengaduan.feed-detail', $this->kode_tracking);

        if ($customMessage) {
            $pesan = "Yth. {$this->user->name},\n\nLaporan Anda dengan kode {$this->kode_tracking} mengenai \"{$this->judul}\" telah diperbarui.\n\n> *Update Progres:* _{$customMessage}_";
        } else {
            $pesan = "Yth. {$this->user->name},\n\nLaporan Anda dengan kode {$this->kode_tracking} mengenai \"{$this->judul}\" telah diperbarui.\n\n> *Status saat ini:* {$statusLabel}";
            
            if ($this->pesan_penutup) {
                $pesan .= "\n\n*Catatan Admin:* _{$this->pesan_penutup}_";
            }
        }

        $pesan .= "\n\nCek detail selengkapnya di sini:\n{$linkDetail}";
        $pesan .= "\n\nTerima kasih telah berpartisipasi dalam membangun Kecamatan Kembaran.\n\nKembaran Ngadu — Kecamatan Kembaran";
        
        return 'https://wa.me/' . $noWa . '?text=' . rawurlencode($pesan);
    }
}