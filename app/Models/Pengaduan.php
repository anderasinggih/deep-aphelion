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
        'user_id',
        'petugas_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'foto_bukti',
        'lokasi_kejadian',
        'latitude',
        'longitude',
        'is_anonymous',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class , 'petugas_id');
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
}