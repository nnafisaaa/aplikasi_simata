<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MapsController extends Controller
{
    public function getRoute(Request $request)
    {
        $startLat = $request->query('startLat');
        $startLng = $request->query('startLng');
        $endLat   = $request->query('endLat');
        $endLng   = $request->query('endLng');

        // Validasi input
        if (!$startLat || !$startLng || !$endLat || !$endLng) {
            return response()->json(['error' => 'Parameter koordinat tidak lengkap'], 400);
        }

        // Panggil API gratis dari OSRM (Open Source Routing Machine)
        // Format: route/v1/driving/{lon1},{lat1};{lon2},{lat2}
        $url = "https://router.project-osrm.org/route/v1/driving/{$startLng},{$startLat};{$endLng},{$endLat}?overview=full&geometries=geojson";

        $response = Http::get($url);
        $data = $response->json();

        // Kalau rute tidak ditemukan
        if (!isset($data['routes'][0])) {
            return response()->json(['error' => 'Rute tidak ditemukan'], 404);
        }

        $route = $data['routes'][0];
        $duration = $route['duration']; 
        $distance = $route['distance']; 

        // Jam masuk sekolah/kantor → 06:50:59
        $jamMasuk = Carbon::createFromTime(6, 50, 59);

        // ETA = waktu sekarang + durasi perjalanan
        $eta = now()->addSeconds($duration);

        // Tentukan status (Telat / Tepat Waktu)
        $status = $eta->greaterThan($jamMasuk) ? 'Telat' : 'Tepat Waktu';

        return response()->json([
            'jarak'     => round($distance / 1000, 2) . ' km', // ubah meter → km
            'durasi'    => gmdate("H:i:s", $duration),         // ubah detik → jam:menit:detik
            'perkiraan_sampai' => $eta->format('H:i:s'),
            'status'    => $status,
            'rute'      => $route['geometry'], 
        ]);
    }
}
