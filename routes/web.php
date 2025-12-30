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
// Redirect root to login or dashboard based on role
Route::get('/', function () {
    if (auth()->check()) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isGuru()) return redirect()->route('guru.dashboard');
        if ($user->isSiswa()) return redirect()->route('siswa.dashboard');
    }
    return redirect()->route('login');
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



    Route::post('/notifications/{id}/read', function ($id) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');

    // Diskusi / Forum Routes
    Route::get('pertemuan/{pertemuan}/diskusi', [\App\Http\Controllers\Shared\DiskusiController::class, 'index'])->name('diskusi.index');
    Route::post('pertemuan/{pertemuan}/diskusi', [\App\Http\Controllers\Shared\DiskusiController::class, 'store'])->name('diskusi.store');
    Route::delete('diskusi/{diskusi}', [\App\Http\Controllers\Shared\DiskusiController::class, 'destroy'])->name('diskusi.destroy');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // User Management
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::resource('users', UserController::class)->parameters(['users' => 'user']);

        // Kelas Management
        Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class)->parameters(['kelas' => 'kelas']);

        // Mata Pelajaran Management
        Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\MataPelajaranController::class)->parameters(['mata-pelajaran' => 'mataPelajaran']);

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

        // Pembelajaran Monitoring
        Route::resource('materi', \App\Http\Controllers\Admin\MateriController::class)->only(['index', 'destroy'])->parameters(['materi' => 'materi']);
        Route::resource('tugas', \App\Http\Controllers\Admin\TugasController::class)->only(['index', 'destroy'])->parameters(['tugas' => 'tugas']);

        // Evaluasi Monitoring
        Route::get('kuis', [\App\Http\Controllers\Admin\EvaluasiController::class, 'kuis'])->name('kuis.index');
        Route::get('ujian', [\App\Http\Controllers\Admin\EvaluasiController::class, 'ujian'])->name('ujian.index');

        // Monitoring Lainnya
        Route::get('absensi', [\App\Http\Controllers\Admin\AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('nilai', [\App\Http\Controllers\Admin\NilaiController::class, 'index'])->name('nilai.index');

        // Laporan
        Route::get('laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    });

    // Guru Routes
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

        // Manajemen Pembelajaran

        Route::get('/jadwal', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/{jadwal}', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'show'])->name('jadwal.show');
        Route::get('/jadwal/{jadwal}/analytics', [\App\Http\Controllers\Guru\JadwalSayaController::class, 'analytics'])->name('jadwal.analytics');

        // Pertemuan (Resource tanpa index, karena diakses via Jadwal)
        Route::resource('pertemuan', \App\Http\Controllers\Guru\PertemuanController::class)
            ->except(['index'])
            ->parameters(['pertemuan' => 'pertemuan']);
        Route::post('pertemuan/{pertemuan}/absensi', [\App\Http\Controllers\Guru\PertemuanController::class, 'simpanAbsensi'])->name('pertemuan.absensi');
        Route::get('absensi/verifikasi', [\App\Http\Controllers\Guru\AbsensiVerifikasiController::class, 'index'])->name('absensi.verifikasi.index');
        Route::post('absensi/verifikasi/{id}', [\App\Http\Controllers\Guru\AbsensiVerifikasiController::class, 'verifikasi'])->name('absensi.verifikasi.update');

        // Materi (Resource tanpa index & show, karena diakses via Pertemuan)
        Route::resource('materi', \App\Http\Controllers\Guru\MateriController::class)
            ->except(['index', 'show'])
            ->parameters(['materi' => 'materi']);

        // Pendahuluan
        Route::get('pendahuluan', [\App\Http\Controllers\Guru\PendahuluanController::class, 'index'])->name('pendahuluan.index');
        Route::get('pendahuluan/mapel/{mataPelajaran}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'show'])->name('pendahuluan.show');
        Route::get('pendahuluan/create/{mataPelajaran}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'create'])->name('pendahuluan.create');
        Route::post('pendahuluan', [\App\Http\Controllers\Guru\PendahuluanController::class, 'store'])->name('pendahuluan.store');
        Route::get('pendahuluan/{pendahuluan}/edit', [\App\Http\Controllers\Guru\PendahuluanController::class, 'edit'])->name('pendahuluan.edit');
        Route::put('pendahuluan/{pendahuluan}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'update'])->name('pendahuluan.update');
        Route::delete('pendahuluan/{pendahuluan}', [\App\Http\Controllers\Guru\PendahuluanController::class, 'destroy'])->name('pendahuluan.destroy');

        // Ujian
        Route::resource('ujian', \App\Http\Controllers\Guru\UjianController::class)->parameters(['ujian' => 'ujian']);
        Route::resource('ujian.soal', \App\Http\Controllers\Guru\SoalUjianController::class)->except(['index', 'show']);
        Route::resource('ujian.jadwal', \App\Http\Controllers\Guru\JadwalUjianController::class)->only(['create', 'store', 'destroy']);

        // Hasil & Koreksi Ujian
        Route::get('ujian/hasil/{jadwal}', [\App\Http\Controllers\Guru\HasilUjianController::class, 'index'])->name('ujian.hasil.index');
        Route::get('ujian/koreksi/{jawaban}', [\App\Http\Controllers\Guru\HasilUjianController::class, 'show'])->name('ujian.hasil.show');
        Route::put('ujian/koreksi/{jawaban}', [\App\Http\Controllers\Guru\HasilUjianController::class, 'update'])->name('ujian.hasil.update');


        // Tugas
        Route::resource('tugas', \App\Http\Controllers\Guru\TugasController::class)->parameters(['tugas' => 'tugas']);
        Route::post('tugas/nilai/{pengumpulan}', [\App\Http\Controllers\Guru\TugasController::class, 'nilai'])->name('tugas.nilai');

        Route::resource('kuis', \App\Http\Controllers\Guru\KuisController::class)->parameters(['kuis' => 'kuis']);
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



        // Pengaturan Nilai
        Route::resource('komponen-nilai', \App\Http\Controllers\Guru\KomponenNilaiController::class)->parameters(['komponen-nilai' => 'komponenNilai']);
    });

    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/leaderboard', [\App\Http\Controllers\Siswa\LeaderboardController::class, 'index'])->name('leaderboard');
        Route::get('/kalender', [\App\Http\Controllers\Siswa\CalendarController::class, 'index'])->name('kalender');

        // Informasi Kelas
        Route::get('/kelas', [\App\Http\Controllers\Siswa\KelasController::class, 'index'])->name('kelas.index');

        // Ujian
        Route::get('/ujian/hasil', [\App\Http\Controllers\Siswa\UjianController::class, 'hasil'])->name('ujian.hasil');

        // Notifikasi
        Route::get('/notifications', [\App\Http\Controllers\Siswa\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Siswa\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

        // Bantuan
        Route::get('/bantuan', [\App\Http\Controllers\Siswa\BantuanController::class, 'index'])->name('bantuan.index');

        // Absensi
        Route::get('/absensi/hari-ini', [\App\Http\Controllers\Siswa\AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/store', [\App\Http\Controllers\Siswa\AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/riwayat', [\App\Http\Controllers\Siswa\AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
        Route::get('/absensi/scan', [\App\Http\Controllers\Siswa\AbsensiController::class, 'scan'])->name('absensi.scan');
        Route::post('/absensi/scan/submit', [\App\Http\Controllers\Siswa\AbsensiController::class, 'scanSubmit'])->name('absensi.scan.submit');

        // Pembelajaran
        Route::get('/pembelajaran', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'index'])->name('pembelajaran.index');
        Route::get('/pembelajaran/{jadwal}', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'show'])->name('pembelajaran.show');
        Route::get('/pertemuan/{pertemuan}', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'pertemuan'])->name('pembelajaran.pertemuan');
        Route::post('/pertemuan/{pertemuan}/absen', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'absenMandiri'])->name('pembelajaran.absen');
        Route::post('/pertemuan/{pertemuan}/catatan', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'saveNote'])->name('pembelajaran.catatan');
        Route::post('/materi/{materi}/learned', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'markAsLearned'])->name('pembelajaran.mark_learned');
        Route::post('/bookmark/toggle', [\App\Http\Controllers\Siswa\PembelajaranController::class, 'toggleBookmark'])->name('pembelajaran.bookmark.toggle');
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
        Route::get('nilai/cetak', [\App\Http\Controllers\Siswa\NilaiController::class, 'cetak'])->name('nilai.cetak');
    });
});
