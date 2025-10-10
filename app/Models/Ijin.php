<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ijin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',     // ✅ ganti 'nama' jadi 'user_id'
        'unit_id',
        'tanggal',
        'keterangan',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel units
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
