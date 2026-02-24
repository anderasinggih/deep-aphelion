<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaduanHistory extends Model
{
    /** @use HasFactory<\Database\Factories\PengaduanHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'status_sebelumnya',
        'status_baru',
        'keterangan_admin',
        'foto_bukti',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}