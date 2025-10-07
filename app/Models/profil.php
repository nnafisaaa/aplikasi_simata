<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $table = 'profils'; // sesuaikan jika nama tabel berbeda

    protected $fillable = [
        'user_id',
        'nama_pegawai',
        'unit_id',
        'photo',
        // tambahkan field lain jika ada
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}