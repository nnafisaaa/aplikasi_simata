<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Ijin;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class RekapanController extends Controller
{
    /**
     * Daftar unit untuk dipilih
     */
    public function index()
    {
        $user = Auth::user();

        // Admin lihat semua unit, TU/Kanit lihat unit sendiri
        $units = $user->role === 'admin' 
            ? Unit::all() 
            : Unit::where('id', $user->unit_id)->get();

        return view('rekapan.index', compact('units'));
    }

    /**
     * Detail rekap presensi + ijin
     */
    public function show($unit_id, Request $request)
    {
        $user = Auth::user();

        // pastikan TU/Kanit hanya bisa akses unit sendiri
        if (in_array($user->role, ['tu','kanit']) && $user->unit_id != $unit_id) {
            abort(403, "Anda tidak memiliki akses ke unit ini.");
        }

        $bulan = $request->query('bulan') ?? date('m');
        $tahun = $request->query('tahun') ?? date('Y');

        // ambil unit
        $unit = Unit::findOrFail($unit_id);

        // ===============================
        // Ambil data presensi dari frontend/API
        // ===============================
        $presensi = Presensi::with('user', 'unit')
            ->where('unit_id', $unit->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // ===============================
        // Ambil data ijin dari frontend/API
        // ===============================
        $ijin = Ijin::with('user', 'unit')
            ->where('unit_id', $unit->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // ===============================
        // Total per bulan
        // ===============================
        $total_presensi = $presensi->count();
        $total_ijin = $ijin->count();

        return view('rekapan.show', compact(
            'unit',
            'bulan',
            'tahun',
            'presensi',
            'ijin',
            'total_presensi',
            'total_ijin'
        ));
    }
}
