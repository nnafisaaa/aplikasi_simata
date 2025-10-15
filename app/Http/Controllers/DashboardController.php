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

        if (in_array($user->role, ['tu', 'kanit'])) {
            // Ambil semua data sesuai unit
            $kalender = KalenderAkademik::where('unit_id', $user->unit_id)->get();
            $ijin = Ijin::where('unit_id', $user->unit_id)->get();
            $presensi = Presensi::where('unit_id', $user->unit_id)->get();

            // Gabungkan semua data
            $dataGabungan = $kalender->merge($ijin)->merge($presensi);

            // ✅ Filter supaya hanya 1 record per unit
            $data = $dataGabungan->unique('unit_id')->values();
        } else {
            $data = collect(); // kosong untuk role lain
        }

        return view('dashboard.index', compact('user', 'data'));
    }
}
