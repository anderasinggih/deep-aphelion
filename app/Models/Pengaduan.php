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
        return $this->belongsTo(Kategori::class);
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
}