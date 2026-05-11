<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama', 'icon', 'deskripsi', 'sla_hari'];

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}