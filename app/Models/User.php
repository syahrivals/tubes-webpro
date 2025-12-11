<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Proper Eloquent casts declaration
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function matkuls()
    {
        return $this->hasMany(Matkul::class, 'dosen_id');
    }

    public function isDosen()
    {
        return $this->role == 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role == 'mahasiswa';
    }
}
