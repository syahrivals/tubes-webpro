<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'dosen_id',
        'hari',
        'jam',
        'semester',
        'credits',
    ];

    /**
     * Relasi ke dosen
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Mahasiswa yang mengambil mata kuliah (pivot: enrollments)
     */
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'enrollments');
    }

    /**
     * Presensi terkait mata kuliah
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Hapus semua data terkait matkul supaya tidak error foreign key
     */
    public function safeDelete()
    {
        // Hapus semua presensi
        $this->presences()->delete();

        // Hapus hubungan mahasiswa (pivot)
        $this->mahasiswas()->detach();

        // Terakhir hapus matkul
        return $this->delete();
    }
}