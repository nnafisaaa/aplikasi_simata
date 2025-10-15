<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\DataController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\RekapanController;


Route::get('/', function () {
    return view('welcome');
});




Route::get('/ping', fn () => 'pong');

Route::get('/check-db', function () {
    try {
        return 'Connected to DB: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'Connection error: ' . $e->getMessage();
    }
});

/*
|--------------------------------------------------------------------------
| Login & Logout
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard sesuai Role
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Data & Video
|--------------------------------------------------------------------------
*/
Route::get('/api/data', [DataController::class, 'getData']);
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');

/*
|--------------------------------------------------------------------------
| Inventaris CRUD
|--------------------------------------------------------------------------
*/
Route::prefix('inventaris')->middleware('auth')->group(function () {
    Route::get('/', [InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/create', [InventarisController::class, 'create'])->name('inventaris.create');
    Route::post('/', [InventarisController::class, 'store'])->name('inventaris.store');
    Route::get('/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
    Route::put('/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
    Route::delete('/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Panel (hanya admin role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User CRUD (plural)
    Route::resource('/admin/users', UserController::class);
    Route::resource('/admin/videos', VideoController::class);
    

    // Unit CRUD
    Route::get('/admin/units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('/admin/units', [UnitController::class, 'store'])->name('units.store');
});



Route::prefix('doa')->group(function () {
    Route::get('/', [DoaController::class, 'indexWeb'])->name('doa.index');       // daftar doa (Blade)
    Route::get('/create', [DoaController::class, 'createWeb'])->name('doa.create'); // form tambah doa
    Route::post('/', [DoaController::class, 'storeWeb'])->name('doa.store');      // simpan doa baru
    Route::get('/{id}/edit', [DoaController::class, 'editWeb'])->name('doa.edit');// form edit doa
    Route::put('/{id}', [DoaController::class, 'updateWeb'])->name('doa.update'); // update doa
    Route::delete('/{id}', [DoaController::class, 'destroyWeb'])->name('doa.destroy'); // hapus doa
});


Route::prefix('kalender')->group(function () {
    Route::get('/', [KalenderAkademikController::class, 'indexWeb'])->name('kalender.index');       // daftar kalender (Blade)
    Route::get('/create', [KalenderAkademikController::class, 'createWeb'])->name('kalender.create'); // form tambah kalender
    Route::post('/', [KalenderAkademikController::class, 'storeWeb'])->name('kalender.store');       // simpan kalender baru
    Route::get('/{id}/edit', [KalenderAkademikController::class, 'editWeb'])->name('kalender.edit'); // form edit kalender
    Route::put('/{id}', [KalenderAkademikController::class, 'updateWeb'])->name('kalender.update');  // update kalender
    Route::delete('/{id}', [KalenderAkademikController::class, 'destroyWeb'])->name('kalender.destroy'); // hapus kalender
});

Route::prefix('rekapan')->group(function () {
    // daftar semua unit
    Route::get('/', [RekapanController::class, 'index'])->name('rekapan.index');

    // detail rekapan per unit
    Route::get('/{unit_id}', [RekapanController::class, 'show'])->name('rekapan.show');
});


