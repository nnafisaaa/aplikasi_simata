<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Ambil profil user yang sedang login
    public function show(Request $request)
    {
        $user = $request->user()->load(['unit', 'profile']); // pastikan relasi profil ada
        return response()->json([
            'status' => 'success',
            'user'   => $user
        ]);
    }

    // Update profil user login
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'username' => 'nullable|string|max:255', // sebelumnya nama_pegawai
            'unit'     => 'nullable|string|max:255',
            'photo'    => 'nullable|image|max:2048', // max 2MB
        ]);

        // Ambil profil atau buat baru
        $profil = $user->profil ?: $user->profil()->create([]);

        // Handle upload foto
        if ($request->hasFile('photo')) {
            if ($profil->photo) {
                Storage::disk('public')->delete($profil->photo);
            }
            $path = $request->file('photo')->store('avatars', 'public');
            $profil->photo = $path;
        }

        // Update data profil
        $profil->nama_pegawai = $request->username ?? $profil->nama_pegawai; // diganti username
        $profil->unit         = $request->unit ?? $profil->unit;
        $profil->save();

        return response()->json([
            'status' => 'success',
            'user'   => $user->load('profile')
        ]);
    }
}
