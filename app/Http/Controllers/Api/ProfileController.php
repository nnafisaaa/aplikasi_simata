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
        $user = $request->user()->load(['unit', 'profil']);
        $profil = $user->profil;
        $photo_url = $profil && $profil->photo ? asset('storage/' . $profil->photo) : null;

        return response()->json([
            'status' => 'success',
            'user'   => $user,
            'photo_url' => $photo_url
        ]);
    }

    // Update profil user login
    public function update(Request $request)
    {
        \Log::info('Profile update request', $request->all());

        $user = $request->user();

        $request->validate([
            'nama_pegawai' => 'nullable|string|max:255',
            'unit_id'      => 'nullable|integer|exists:units,id',
            'photo'        => 'nullable|image|max:2048',
        ]);

        $profil = $user->profil ?: $user->profil()->create([]);

        if ($request->hasFile('photo')) {
            if ($profil->photo) {
                Storage::disk('public')->delete($profil->photo);
            }
            $path = $request->file('photo')->store('avatars', 'public');
            $profil->photo = $path;
        }

        $profil->nama_pegawai = $request->nama_pegawai ?? $profil->nama_pegawai;
        $profil->unit_id      = $request->unit_id ?? $profil->unit_id;
        $profil->save();

        $photo_url = $profil->photo ? asset('storage/' . $profil->photo) : null;

        return response()->json([
            'status' => 'success',
            'user'   => $user->load('profil'),
            'photo_url' => $photo_url
        ]);
    }
}
