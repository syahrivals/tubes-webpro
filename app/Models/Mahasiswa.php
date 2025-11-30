<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'jurusan',
        'angkatan',
        'phone',
        'photo',
    ];

    // Relasi ke user (satu mahasiswa punya satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke mata kuliah (satu mahasiswa bisa mengambil banyak mata kuliah)
    public function matkuls()
    {
        return $this->belongsToMany(Matkul::class, 'enrollments');
    }

    // Relasi ke presensi (satu mahasiswa punya banyak presensi)
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}

