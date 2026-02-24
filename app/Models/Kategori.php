<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'sla_hari'];

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}