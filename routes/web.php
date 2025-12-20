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
        Route::get('/siswa', [\App\Http\Controllers\Guru\SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/jadwal', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/{jadwal}', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'show'])->name('jadwal.show');

        // Pertemuan (Resource tanpa index, karena diakses via Jadwal)
        Route::resource('pertemuan', \App\Http\Controllers\Guru\PertemuanController::class)->except(['index']);
        Route::post('pertemuan/{pertemuan}/absensi', [\App\Http\Controllers\Guru\PertemuanController::class, 'simpanAbsensi'])->name('pertemuan.absensi');

        // Materi (Resource tanpa index & show, karena diakses via Pertemuan)
        Route::resource('materi', \App\Http\Controllers\Guru\MateriController::class)->except(['index', 'show']);

        // Pendahuluan
        Route::get('pendahuluan', [\App\Http\Controllers\Guru\PendahuluanController::class, 'index'])->name('pendahuluan.index');
        Route::get('pendahuluan/mapel/{mataPelajaran}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'show'])->name('pendahuluan.show');
        Route::get('pendahuluan/create/{mataPelajaran}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'create'])->name('pendahuluan.create');
        Route::post('pendahuluan', [\App\Http\Controllers\Guru\PendahuluanController::class, 'store'])->name('pendahuluan.store');
        Route::get('pendahuluan/{pendahuluan}/edit', [\App\Http\Controllers\Guru\PendahuluanController::class, 'edit'])->name('pendahuluan.edit');
        Route::put('pendahuluan/{pendahuluan}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'update'])->name('pendahuluan.update');
        Route::delete('pendahuluan/{pendahuluan}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'destroy'])->name('pendahuluan.destroy');

        // Ujian
        Route::resource('ujian', \App\Http\Controllers\Guru\UjianController::class);

        // Tugas
        Route::resource('tugas', \App\Http\Controllers\Guru\TugasController::class);
        Route::post('tugas/nilai/{pengumpulan}', [\App\Http\Controllers\Guru\TugasController::class, 'nilai'])->name('tugas.nilai');

        Route::resource('kuis', \App\Http\Controllers\Guru\KuisController::class);
        Route::resource('kuis.soal', \App\Http\Controllers\Guru\SoalKuisController::class)->shallow();

        // Kuis Hasil & Review
        Route::get('kuis/{kuis}/hasil', [\App\Http\Controllers\Guru\KuisController::class, 'hasil'])->name('kuis.hasil');
        Route::get('kuis/review/{jawabanKuis}', [\App\Http\Controllers\Guru\KuisController::class, 'review'])->name('kuis.review');
        Route::post('kuis/review/{jawabanKuis}/simpan', [\App\Http\Controllers\Guru\KuisController::class, 'simpanKoreksi'])->name('kuis.simpan_koreksi');

        // Laporan
        Route::get('laporan/nilai', [\App\Http\Controllers\Guru\LaporanController::class, 'nilai'])->name('laporan.nilai');
        Route::get('laporan/absensi', [\App\Http\Controllers\Guru\LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('laporan/pembelajaran', [\App\Http\Controllers\Guru\LaporanController::class, 'pembelajaran'])->name('laporan.pembelajaran');
    });

    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

        // Pembelajaran
        Route::get('/pembelajaran', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'index'])->name('pembelajaran.index');
        Route::get('/pembelajaran/{jadwal}', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'show'])->name('pembelajaran.show');
        Route::get('/pertemuan/{pertemuan}', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'pertemuan'])->name('pembelajaran.pertemuan');
        Route::post('/pertemuan/{pertemuan}/absen', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'absenMandiri'])->name('pembelajaran.absen');


        // Tugas Siswa
        Route::get('/tugas/{tugas}', [\App\Http\Controllers\Siswa\TugasController::class, 'show'])->name('tugas.show');
        Route::post('/tugas', [\App\Http\Controllers\Siswa\TugasController::class, 'store'])->name('tugas.store');

        // Kuis & Ujian
        Route::get('kuis/{kuis}', [\App\Http\Controllers\Siswa\KuisController::class, 'show'])->name('kuis.show');
        Route::post('kuis/{kuis}/start', [\App\Http\Controllers\Siswa\KuisController::class, 'start'])->name('kuis.start');
        Route::get('ujian/{jawabanKuis}', [\App\Http\Controllers\Siswa\KuisController::class, 'kerjakan'])->name('kuis.kerjakan');
        Route::post('ujian/simpan', [\App\Http\Controllers\Siswa\KuisController::class, 'simpanJawaban'])->name('kuis.simpan');
        Route::post('ujian/{jawabanKuis}/finish', [\App\Http\Controllers\Siswa\KuisController::class, 'finish'])->name('kuis.finish');
    });
});
