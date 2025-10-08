<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'imei',
        'unit_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed', // otomatis hash saat create/update
    ];

    // Relationship to Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relationship to Profile
    public function profil()
    {
        return $this->hasOne(\App\Models\Profil::class);
    }

// Simpan user baru ke database
public static function createUser($request)
{
    return self::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role'     => 'user',
        'imei'     => $request->imei,
        'unit_id'  => $request->unit_id,
        'name'     => $request->name,
    ]);
}
}
