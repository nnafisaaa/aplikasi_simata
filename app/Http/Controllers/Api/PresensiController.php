<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;

class PresensiController extends Controller
{
    // Simpan presensi (datang atau pulang)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_presensi' => 'required|in:datang,pulang',
            'nama' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id', // relasi ke tabel units
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'jarak' => 'nullable|numeric',
        ]);

        $presensi = Presensi::create($validated);

        return response()->json([
            'message' => 'Presensi berhasil disimpan',
            'data' => $presensi->load('unit') // include unit
        ], 201);
    }

    // Ambil laporan presensi per bulan & tahun
    public function laporan(Request $request)
    {
        $bulan = $request->query('bulan'); // 1 - 12
        $tahun = $request->query('tahun'); // ex: 2025

        $query = Presensi::with('unit'); // ikut load relasi unit

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // Format laporan jadi tanggal -> datang & pulang
        $laporan = [];
        foreach ($data as $presensi) {
            $tgl = $presensi->tanggal;

            if (!isset($laporan[$tgl])) {
                $laporan[$tgl] = [
                    'tanggal' => $tgl,
                    'unit' => $presensi->unit ? $presensi->unit->nama_unit : null,
                    'datang' => null,
                    'pulang' => null,
                ];
            }

            if ($presensi->jenis_presensi === 'datang') {
                $laporan[$tgl]['datang'] = $presensi->waktu;
            } else {
                $laporan[$tgl]['pulang'] = $presensi->waktu;
            }
        }

        return response()->json(array_values($laporan), 200);
    }
}
