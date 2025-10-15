<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller import
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\Api\IjinController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\LaporanPresensiController;
use App\Http\Controllers\Api\LaporanIjinController;  
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\VideoController;


Route::post('/login', [AuthController::class, 'loginAplikasi']);


Route::get('/berita', [BeritaController::class, 'getNews']);


Route::post('/register', [AuthController::class, 'registerAplikasi']);



// =========================
// CRUD Kalender Akademik
// =========================
Route::get('/kalender-akademik', [KalenderAkademikController::class, 'index']);
Route::post('/kalender-akademik', [KalenderAkademikController::class, 'store']);
Route::get('/kalender-akademik/{id}', [KalenderAkademikController::class, 'show']);
Route::put('/kalender-akademik/{id}', [KalenderAkademikController::class, 'update']);
Route::delete('/kalender-akademik/{id}', [KalenderAkademikController::class, 'destroy']);


// =========================
// ROUTE API VIDEO 
// =========================
Route::get('/videos', [VideoController::class, 'index']);          
Route::post('/videos', [VideoController::class, 'store']);         
Route::get('/videos/{id}', [VideoController::class, 'show']);      
Route::put('/videos/{id}', [VideoController::class, 'update']);    
Route::delete('/videos/{id}', [VideoController::class, 'destroy']); 


// =========================
// CRUD Unit
// =========================
Route::get('/units', [UnitController::class, 'index']);
Route::post('/units', [UnitController::class, 'store']);
Route::get('/units/{id}', [UnitController::class, 'show']);
Route::put('/units/{id}', [UnitController::class, 'update']);
Route::delete('/units/{id}', [UnitController::class, 'destroy']);

// =========================
// Quran & Prayer Time
// =========================
Route::get('/quran/surat', [QuranController::class, 'listSurat']);
Route::get('/quran/surat/{nomor}', [QuranController::class, 'detailSurat']);
Route::get('/prayer-times', [PrayerTimeController::class, 'getTimes']);

// =========================
// Data dan Maps
// =========================
Route::get('/data', [DataController::class, 'getData']);
Route::get('/maps/route', [MapsController::class, 'getRoute']);

// =========================
// Doa
// =========================
Route::get('/doa', [DoaController::class, 'index']);
Route::get('/doa/{id}', [DoaController::class, 'show']);
Route::post('/doa', [DoaController::class, 'store']);

// =========================
// Ijin CRUD
// =========================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ijins', [IjinController::class, 'index']);
    Route::post('/ijins', [IjinController::class, 'store']);
    Route::get('/ijins/{id}', [IjinController::class, 'show']);
    Route::put('/ijins/{id}', [IjinController::class, 'update']);
    Route::delete('/ijins/{id}', [IjinController::class, 'destroy']);
});

// =========================
// Presensi CRUD
// =========================
Route::middleware('auth:sanctum')->post('/presensi', [PresensiController::class, 'store']);


// =========================
// LAPORAN PRESENSI & IJIN
// =========================

// PRESENSI
Route::get('/laporan/presensi', [LaporanPresensiController::class, 'index']);
Route::post('/laporan/presensi/generate', [LaporanPresensiController::class, 'generate']);

// IJIN
Route::get('/laporan/ijin', [LaporanIjinController::class, 'index']);
Route::post('/laporan/ijin/generate', [LaporanIjinController::class, 'generate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
});

