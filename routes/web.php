<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', UserController::class);

        // Kelas Management
        Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class);

        // Mata Pelajaran Management
        Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\MataPelajaranController::class);

        // Jadwal Pelajaran (Guru Mengajar)
        Route::resource('jadwal-pelajaran', \App\Http\Controllers\Admin\JadwalPelajaranController::class)->parameters(['jadwal-pelajaran' => 'jadwalPelajaran']);
    });

    // Guru Routes
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

        // Manajemen Pembelajaran
        Route::get('/jadwal', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/{jadwal}', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'show'])->name('jadwal.show');

        // Pertemuan (Resource tanpa index, karena diakses via Jadwal)
        Route::resource('pertemuan', \App\Http\Controllers\Guru\PertemuanController::class)->except(['index']);

        // Materi (Resource tanpa index & show, karena diakses via Pertemuan)
        Route::resource('materi', \App\Http\Controllers\Guru\MateriController::class)->except(['index', 'show']);
    });

    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    });
});
