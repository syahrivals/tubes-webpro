<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'matkul_id',
        'mahasiswa_id',
        'tanggal',
        'status',
        'note',
        'recorded_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke mata kuliah (satu presensi untuk satu mata kuliah)
    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    // Relasi ke mahasiswa (satu presensi untuk satu mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // Relasi ke user yang mencatat presensi (dosen)
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

