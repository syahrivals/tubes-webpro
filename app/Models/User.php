<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Penambahan: role untuk membedakan dosen dan mahasiswa
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

    // Penambahan: Relationship ke Mahasiswa (jika user adalah mahasiswa)
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    // Penambahan: Relationship ke Matkul yang diampu (jika user adalah dosen)
    public function matkuls()
    {
        return $this->hasMany(Matkul::class, 'dosen_id');
    }

    // Penambahan: Helper method untuk cek role
    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }
}
