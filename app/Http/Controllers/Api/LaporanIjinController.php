<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ijin;
use App\Models\LaporanIjin;

class LaporanIjinController extends Controller
{
    // Generate laporan ijin per bulan & tahun
    public function generate(Request $request)
    {
        $bulan = $request->query('bulan') ?? date('m');
        $tahun = $request->query('tahun') ?? date('Y');

        $rekap = Ijin::select('nama', 'unit_id')
            ->selectRaw("COUNT(*) as total_ijin")
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('nama', 'unit_id')
            ->get();

        foreach ($rekap as $r) {
            LaporanIjin::updateOrCreate(
                [
                    'nama' => $r->nama,
                    'unit_id' => $r->unit_id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'total_ijin' => $r->total_ijin,
                ]
            );
        }

        return response()->json([
            'message' => 'Laporan Ijin berhasil direkap',
            'data' => $rekap
        ], 200);
    }

    // Ambil semua laporan ijin
    public function index()
    {
        return response()->json(
            LaporanIjin::with('unit')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get(),
            200
        );
    }
}
