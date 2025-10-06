<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\LaporanPresensi;
use App\Models\LaporanIjin;

class RekapanController extends Controller
{
    // daftar unit untuk dipilih
    public function index()
    {
        $units = Unit::all();
        return view('rekapan.index', compact('units'));
    }

    // detail rekap presensi + ijin
    public function show($unit_id, Request $request)
    {
        $bulan = $request->query('bulan') ?? date('m');
        $tahun = $request->query('tahun') ?? date('Y');

        // ambil unit berdasarkan ID
        $unit = Unit::findOrFail($unit_id);

        // ambil data laporan presensi berdasarkan unit_id
        $presensi = LaporanPresensi::with('unit')
            ->where('unit_id', $unit->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        // ambil data laporan ijin berdasarkan unit_id
        $ijin = LaporanIjin::with('unit')
            ->where('unit_id', $unit->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('rekapan.show', compact('unit', 'bulan', 'tahun', 'presensi', 'ijin'));
    }
}
