<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Simpan presensi
     * jenis_presensi = nama kegiatan
     * status = otomatis (datang/pulang)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'jenis_presensi' => 'required|string|max:255', // nama kegiatan
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'jarak' => 'nullable|numeric',
        ]);

        // 🔹 Ambil user login
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        // 🔹 Cek apakah sudah presensi datang hari ini
        $sudahDatang = Presensi::where('user_id', $user->id)
            ->where('tanggal', $validated['tanggal'])
            ->where('status', 'datang')
            ->exists();

        // 🔹 Tentukan status otomatis
        $status = $sudahDatang ? 'pulang' : 'datang';

        // 🔹 Cegah duplikasi presensi (user + tanggal + status)
        $duplikat = Presensi::where('user_id', $user->id)
            ->where('tanggal', $validated['tanggal'])
            ->where('status', $status)
            ->exists();

        if ($duplikat) {
            return response()->json([
                'message' => "Presensi $status sudah tercatat hari ini.",
            ], 422);
        }

        // 🔹 Simpan presensi baru
        $presensi = Presensi::create([
            'user_id' => $user->id,
            'unit_id' => $validated['unit_id'],
            'jenis_presensi' => $validated['jenis_presensi'],
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'jarak' => $validated['jarak'] ?? null,
            'status' => $status, // langsung disimpan di tabel presensi
        ]);

        return response()->json([
            'message' => "Presensi $status berhasil disimpan.",
            'data' => $presensi->load('unit', 'user'),
        ], 201);
    }

    /**
     * Laporan presensi per bulan/tahun (khusus user login)
     */
    public function laporan(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:' . date('Y'),
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User belum login'], 401);
        }

        $query = Presensi::with('unit')
            ->where('user_id', $user->id)
            ->orderBy('tanggal', 'asc');

        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $data = $query->get();

        // 🔹 Format laporan
        $laporan = [];
        foreach ($data as $item) {
            $tgl = $item->tanggal;

            if (!isset($laporan[$tgl])) {
                $laporan[$tgl] = [
                    'tanggal' => $tgl,
                    'user' => $user->name,
                    'unit' => $item->unit->nama_unit ?? null,
                    'kegiatan' => $item->jenis_presensi,
                    'datang' => null,
                    'pulang' => null,
                ];
            }

            if ($item->status === 'datang') {
                $laporan[$tgl]['datang'] = $item->waktu;
            } elseif ($item->status === 'pulang') {
                $laporan[$tgl]['pulang'] = $item->waktu;
            }
        }

        return response()->json(array_values($laporan), 200);
    }
}
