<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KalenderAkademik;
use App\Models\Ijin;
use App\Models\Presensi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek role user
        if (in_array($user->role, ['tu', 'kanit'])) {
            // Ambil semua data sesuai unit
            $kalender = KalenderAkademik::where('unit_id', $user->unit_id)->get();
            $ijin = Ijin::where('unit_id', $user->unit_id)->get();
            $presensi = Presensi::where('unit_id', $user->unit_id)->get();

            // Gabungkan semua data jadi satu koleksi
            $dataGabungan = collect()
                ->merge($kalender)
                ->merge($ijin)
                ->merge($presensi);

            // Filter supaya hanya 1 record per unit
            $data = $dataGabungan->unique('unit_id')->values();

            // Hitung total masing-masing jenis data
            $totalKalender = $kalender->count();
            $totalIjin = $ijin->count();
            $totalPresensi = $presensi->count();
        } else {
            // Kosong untuk role lain
            $data = collect();
            $totalKalender = $totalIjin = $totalPresensi = 0;
        }

        return view('dashboard.index', compact(
            'user',
            'data',
            'totalKalender',
            'totalIjin',
            'totalPresensi'
        ));
    }
}
