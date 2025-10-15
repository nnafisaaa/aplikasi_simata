<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ijin;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class IjinController extends Controller
{
    // =========================
    // METHOD CRUD IJIN ASLI
    // =========================

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        $ijin = Ijin::create($validated);

        return response()->json([
            'message' => 'Ijin berhasil disimpan',
            'data' => $ijin->load('unit')
        ], 201);
    }

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

    // =========================
    // METHOD BARU: REKAP LAPORAN
    // =========================

    public function rekap(Request $request)
    {
        $bulan = $request->query('bulan') ?? date('m');
        $tahun = $request->query('tahun') ?? date('Y');

        // Rekap Ijin per unit
        $rekapIjin = Ijin::with('unit', 'user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy('unit_id');

        // Rekap Presensi per unit
        $rekapPresensi = Presensi::with('unit', 'user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy('unit_id');

        return response()->json([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'rekap_ijin' => $rekapIjin,
            'rekap_presensi' => $rekapPresensi,
        ], 200);
    }
}
