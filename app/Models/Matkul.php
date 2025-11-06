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

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mahasiswas(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'enrollments');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}

