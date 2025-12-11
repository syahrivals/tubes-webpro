<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'matkul_id',
        'token',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
