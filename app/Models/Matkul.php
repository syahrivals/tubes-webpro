<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Matkul extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'dosen_id',
        'semester',
        'credits',
    ];

    // Relasi ke dosen (user yang mengajar)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relasi ke mahasiswa (banyak mahasiswa mengambil mata kuliah ini)
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'enrollments');
    }

    // Relasi ke presensi (banyak presensi untuk mata kuliah ini)
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}

