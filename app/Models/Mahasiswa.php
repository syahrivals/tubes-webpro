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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matkuls()
    {
        return $this->belongsToMany(Matkul::class, 'enrollments');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}

