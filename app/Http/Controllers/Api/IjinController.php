<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ijin;

class IjinController extends Controller
{
    // Ambil semua data ijin
    public function index()
    {
        // include relasi unit
        return response()->json(Ijin::with('unit')->get(), 200);
    }

    // Simpan data ijin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $ijin = Ijin::create($validated);

        return response()->json([
            'message' => 'Ijin berhasil disimpan',
            'data' => $ijin->load('unit')
        ], 201);
    }

    // Detail ijin
    public function show($id)
    {
        $ijin = Ijin::with('unit')->find($id);

        if (!$ijin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($ijin, 200);
    }

    // Update ijin
    public function update(Request $request, $id)
    {
        $ijin = Ijin::find($id);

        if (!$ijin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
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
        $ijin = Ijin::find($id);

        if (!$ijin) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $ijin->delete();

        return response()->json(['message' => 'Ijin berhasil dihapus'], 200);
    }
}
