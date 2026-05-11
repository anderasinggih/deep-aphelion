<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($user) {
            // Jika ini soft delete (bukan force delete)
            if (!$user->isForceDeleting()) {
                $suffix = '.deleted.' . time();
                
                // Ubah email dan NIK agar bisa dipakai lagi oleh orang lain
                $user->email = $user->email . $suffix;
                
                if ($user->nik) {
                    // Karena NIK terbatas 16 karakter, kita harus hati-hati.
                    // Tapi di migration NIK adalah string(16). 
                    // Kita akan hapus saja NIK-nya atau ubah jadi null jika dibolehkan,
                    // atau sekedar tambahkan suffix jika kolomnya kita perlebar nanti.
                    // Untuk sekarang, kita set null saja agar NIK tersebut bebas dipakai lagi.
                    $user->nik = null;
                }
                
                $user->save();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nik',
        'no_wa',
        'role',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class , 'user_id');
    }

    public function disposisis()
    {
        return $this->hasMany(Pengaduan::class , 'petugas_id');
    }

    public function dukungans()
    {
        return $this->hasMany(PengaduanDukungan::class);
    }

    public function komentars()
    {
        return $this->hasMany(PengaduanKomentar::class);
    }

    /**
     * Get initials from user name.
     */
    public function getAvatarInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($part) => substr($part, 0, 1))
            ->take(2)
            ->join('') ?: '?';
    }

    /**
     * Get a consistent avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=FFFFFF&background=0284c7&bold=true";
    }

    /**
     * Check if the user is a superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }
}