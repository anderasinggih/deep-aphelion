<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaduanRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'ip_address',
        'rating_pelayanan',
        'rating_respon',
        'rating_kompetensi',
        'rating_fasilitas',
        'rating',
        'rating_komentar',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
