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

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Forum Routes
    Route::get('/forum', [\App\Http\Controllers\ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/topik/{topik}', [\App\Http\Controllers\ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/topik', [\App\Http\Controllers\ForumController::class, 'store'])->name('forum.store');
    Route::post('/forum/topik/{topik}/balas', [\App\Http\Controllers\ForumController::class, 'reply'])->name('forum.reply');
    // Perpustakaan Routes
    Route::get('/perpustakaan', [\App\Http\Controllers\PerpustakaanController::class, 'index'])->name('perpustakaan.index');
    Route::post('/perpustakaan', [\App\Http\Controllers\PerpustakaanController::class, 'store'])->name('perpustakaan.store');
    Route::delete('/perpustakaan/{materi}', [\App\Http\Controllers\PerpustakaanController::class, 'destroy'])->name('perpustakaan.destroy');

    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');

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

        // Pengaturan Akademik
        Route::get('pengaturan-akademik', [\App\Http\Controllers\Admin\PengaturanAkademikController::class, 'index'])->name('pengaturan-akademik.index');
        Route::post('pengaturan-akademik', [\App\Http\Controllers\Admin\PengaturanAkademikController::class, 'store'])->name('pengaturan-akademik.store');
        Route::patch('pengaturan-akademik/{akademik}/activate', [\App\Http\Controllers\Admin\PengaturanAkademikController::class, 'activate'])->name('pengaturan-akademik.activate');
        Route::delete('pengaturan-akademik/{akademik}', [\App\Http\Controllers\Admin\PengaturanAkademikController::class, 'destroy'])->name('pengaturan-akademik.destroy');

        // Pengaturan Aplikasi / Sekolah
        Route::get('pengaturan-sekolah', [\App\Http\Controllers\Admin\PengaturanAplikasiController::class, 'index'])->name('pengaturan-sekolah.index');
        Route::patch('pengaturan-sekolah', [\App\Http\Controllers\Admin\PengaturanAplikasiController::class, 'update'])->name('pengaturan-sekolah.update');

        // Audit Log
        Route::get('audit-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-log.index');
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
        Route::get('absensi/verifikasi', [\App\Http\Controllers\Guru\AbsensiVerifikasiController::class, 'index'])->name('absensi.verifikasi.index');
        Route::post('absensi/verifikasi/{id}', [\App\Http\Controllers\Guru\AbsensiVerifikasiController::class, 'verifikasi'])->name('absensi.verifikasi.update');

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
        Route::resource('ujian.soal', \App\Http\Controllers\Guru\SoalUjianController::class)->except(['index', 'show']);
        Route::resource('ujian.jadwal', \App\Http\Controllers\Guru\JadwalUjianController::class)->only(['create', 'store', 'destroy']);

        // Hasil & Koreksi Ujian
        Route::get('ujian/hasil/{jadwal}', [\App\Http\Controllers\Guru\HasilUjianController::class, 'index'])->name('ujian.hasil.index');
        Route::get('ujian/koreksi/{jawaban}', [\App\Http\Controllers\Guru\HasilUjianController::class, 'show'])->name('ujian.hasil.show');
        Route::put('ujian/koreksi/{jawaban}', [\App\Http\Controllers\Guru\HasilUjianController::class, 'update'])->name('ujian.hasil.update');


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
        Route::get('laporan/nilai/{guruMengajar}/cetak', [\App\Http\Controllers\Guru\CetakLaporanController::class, 'cetakNilai'])->name('laporan.nilai.cetak');
        Route::get('laporan/absensi', [\App\Http\Controllers\Guru\LaporanController::class, 'absensi'])->name('laporan.absensi');
        Route::get('laporan/pembelajaran', [\App\Http\Controllers\Guru\LaporanController::class, 'pembelajaran'])->name('laporan.pembelajaran');

        // Wali Kelas
        Route::get('wali-kelas', [\App\Http\Controllers\Guru\WaliKelasController::class, 'index'])->name('wali-kelas.index');
        Route::get('wali-kelas/{id}', [\App\Http\Controllers\Guru\WaliKelasController::class, 'show'])->name('wali-kelas.show');
        Route::get('wali-kelas/{kelasId}/siswa/{siswaId}', [\App\Http\Controllers\Guru\WaliKelasController::class, 'showSiswa'])->name('wali-kelas.siswa.show');

        // Pengaturan Nilai
        Route::resource('komponen-nilai', \App\Http\Controllers\Guru\KomponenNilaiController::class);
    });

    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

        // Pembelajaran
        Route::get('/pembelajaran', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'index'])->name('pembelajaran.index');
        Route::get('/pembelajaran/{jadwal}', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'show'])->name('pembelajaran.show');
        Route::get('/pertemuan/{pertemuan}', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'pertemuan'])->name('pembelajaran.pertemuan');
        Route::post('/pertemuan/{pertemuan}/absen', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'absenMandiri'])->name('pembelajaran.absen');
        Route::get('/pendahuluan/{jadwal}', [\App\Http\Controllers\Siswa\PendahuluanController::class, 'show'])->name('pendahuluan.show');


        // Tugas Siswa
        Route::get('/tugas', [\App\Http\Controllers\Siswa\TugasController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [\App\Http\Controllers\Siswa\TugasController::class, 'show'])->name('tugas.show');
        Route::post('/tugas', [\App\Http\Controllers\Siswa\TugasController::class, 'store'])->name('tugas.store');

        // Kuis & Ujian
        // Kuis
        Route::get('kuis', [\App\Http\Controllers\Siswa\KuisController::class, 'index'])->name('kuis.index');
        Route::get('kuis/{kuis}', [\App\Http\Controllers\Siswa\KuisController::class, 'show'])->name('kuis.show');
        Route::post('kuis/{kuis}/start', [\App\Http\Controllers\Siswa\KuisController::class, 'start'])->name('kuis.start');
        Route::get('kuis/do/{jawabanKuis}', [\App\Http\Controllers\Siswa\KuisController::class, 'kerjakan'])->name('kuis.kerjakan');
        Route::post('kuis/do/simpan', [\App\Http\Controllers\Siswa\KuisController::class, 'simpanJawaban'])->name('kuis.simpan');
        Route::post('kuis/do/{jawabanKuis}/finish', [\App\Http\Controllers\Siswa\KuisController::class, 'finish'])->name('kuis.finish');

        // Ujian (Exam)
        Route::get('ujian', [\App\Http\Controllers\Siswa\UjianController::class, 'index'])->name('ujian.index');
        Route::get('ujian/{jadwal}', [\App\Http\Controllers\Siswa\UjianController::class, 'show'])->name('ujian.show');
        Route::post('ujian/{jadwal}/start', [\App\Http\Controllers\Siswa\UjianController::class, 'start'])->name('ujian.start');

        Route::get('ujian/do/{jawabanUjian}', [\App\Http\Controllers\Siswa\UjianController::class, 'kerjakan'])->name('ujian.kerjakan');
        Route::post('ujian/do/simpan', [\App\Http\Controllers\Siswa\UjianController::class, 'simpanJawaban'])->name('ujian.simpan');
        Route::post('ujian/do/{jawabanUjian}/finish', [\App\Http\Controllers\Siswa\UjianController::class, 'finish'])->name('ujian.finish');
        Route::get('nilai', [\App\Http\Controllers\Siswa\NilaiController::class, 'index'])->name('nilai.index');
    });
});

