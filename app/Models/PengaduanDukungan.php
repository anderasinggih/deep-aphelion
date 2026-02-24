<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pengaduan;

class PengaduanDukungan extends Model
{
    /** @use HasFactory<\Database\Factories\PengaduanDukunganFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'pengaduan_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}