<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'matkul_id',
    ];

    // Relasi ke mahasiswa (satu enrollment untuk satu mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // Relasi ke mata kuliah (satu enrollment untuk satu mata kuliah)
    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }
}

