<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanIjin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'unit_id',
        'bulan',
        'tahun',
        'total_ijin',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
