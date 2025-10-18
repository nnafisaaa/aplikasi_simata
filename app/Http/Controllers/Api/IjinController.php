<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ijin;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;

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
    $unit_id = $request->query('unit_id'); // ambil unit_id dari query

    // Jika unit_id tidak dikirim, kembalikan error
    if (!$unit_id) {
        return response()->json([
            'message' => 'Parameter unit_id wajib dikirim.'
        ], 400);
    }

    // Ambil data unit
    $unit = Unit::findOrFail($unit_id);

    // ===============================
    // Ambil data ijin sesuai unit
    // ===============================
    $rekapIjin = Ijin::with('user', 'unit')
        ->where('unit_id', $unit->id)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->orderBy('tanggal', 'asc')
        ->get();

    // ===============================
    // Ambil data presensi sesuai unit
    // ===============================
    $rekapPresensi = Presensi::with('user', 'unit')
        ->where('unit_id', $unit->id)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->orderBy('tanggal', 'asc')
        ->get();

    // ===============================
    // Hitung total per bulan
    // ===============================
    $total_ijin = $rekapIjin->count();
    $total_presensi = $rekapPresensi->count();

    // ===============================
    // Response JSON
    // ===============================
    return response()->json([
        'unit' => $unit->nama_unit ?? null,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'total_ijin' => $total_ijin,
        'total_presensi' => $total_presensi,
        'rekap_ijin' => $rekapIjin,
        'rekap_presensi' => $rekapPresensi,
    ], 200);
}

// FUNCTION BARU: STATISTIK UNTUK FRONTEND
    // =========================

    public function statistik(Request $request)
    {
        $user = Auth::user();
        $bulan = $request->query('bulan') ?? date('m');
        $tahun = $request->query('tahun') ?? date('Y');

        $totalIjin = Ijin::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        $totalPresensi = Presensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        $datang = Presensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereNotNull('jam_masuk')
            ->count();

        $pulang = Presensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereNotNull('jam_pulang')
            ->count();

        $hadir = max($totalPresensi - $totalIjin, 0);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'nama' => $user->name,
            ],
            'periode' => date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)),
            'statistik' => [
                'presensi_total' => $totalPresensi,
                'datang' => $datang,
                'pulang' => $pulang,
                'ijin' => $totalIjin,
                'hadir' => $hadir,
            ]
        ], 200);
    }
}
