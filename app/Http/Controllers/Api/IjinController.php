<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ijin;
use Illuminate\Support\Facades\Auth;

class IjinController extends Controller
{
    // Ambil semua data ijin milik user login
    public function index()
    {
        $user = Auth::user();

        return response()->json(
            Ijin::with('unit')
                ->where('user_id', $user->id)
                ->get(),
            200
        );
    }

    // Simpan data ijin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id(); // ambil id user yang login

        $ijin = Ijin::create($validated);

        return response()->json([
            'message' => 'Ijin berhasil disimpan',
            'data' => $ijin->load('unit')
        ], 201);
    }

    // Detail ijin
    public function show($id)
    {
        $user = Auth::user();
        $ijin = Ijin::with('unit')
            ->where('user_id', $user->id)
            ->find($id);

        if (!$ijin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($ijin, 200);
    }

    // Update ijin
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $ijin = Ijin::where('user_id', $user->id)->find($id);

        if (!$ijin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $ijin->update($validated);

        return response()->json([
            'message' => 'Ijin berhasil diperbarui',
            'data' => $ijin->load('unit')
        ], 200);
    }

    // Hapus ijin
    public function destroy($id)
    {
        $user = Auth::user();
        $ijin = Ijin::where('user_id', $user->id)->find($id);

        if (!$ijin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $ijin->delete();

        return response()->json(['message' => 'Ijin berhasil dihapus'], 200);
    }
}
