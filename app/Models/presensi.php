<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',         // ambil dari user login
        'unit_id',
        'jenis_presensi',  // nama kegiatan / jenis kegiatan
        'tanggal',
        'waktu',
        'jarak',
        'status',          // datang / pulang langsung di sini
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
