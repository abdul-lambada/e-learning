<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JawabanUjian;
use App\Models\JadwalUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat ujian')->only(['index', 'hasil']);
    }

    /**
     * Tampilkan jadwal ujian siswa.
     * (Fitur ini mungkin sudah ada atau digabung dgn index, tapi kita fokus ke Hasil Ujian skrg)
     */
    public function index()
    {
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();

        // Ambil kelas dimana siswa terdaftar (pivot kelas_siswa)
        // Asumsi siswa hanya punya kelas aktif di tahun ajaran ini
        $kelasIds = $siswa->kelas()->pluck('kelas.id');

        // Ambil jadwal ujian yang:
        // 1. Ujiannya buat kelas siswa tersebut
        // 2. Ujiannya aktif
        // 3. Diurutkan tanggal terdekat
        $jadwals = JadwalUjian::with(['ujian.mataPelajaran'])
            ->whereHas('ujian', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds)
                  ->where('aktif', true);
            })
            ->whereDate('tanggal_ujian', '>=', now()->subDays(1)) // Tampilkan yg hari ini atau ke depan, plus toleransi 1 hari ke belakang buat cek status selesai
            ->orderBy('tanggal_ujian', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(9);

        return view('siswa.ujian.index', compact('jadwals'));
    }

    /**
     * Tampilkan riwayat hasil ujian siswa.
     */
    public function hasil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil riwayat jawaban ujian yang sudah selesai
        // Relasi: JawabanUjian -> JadwalUjian -> Ujian -> MataPelajaran
        $riwayatUjian = JawabanUjian::where('siswa_id', $user->id)
            ->where('status', 'selesai')
            ->with(['jadwalUjian.ujian.mataPelajaran', 'jadwalUjian.ujian'])
            ->orderBy('waktu_selesai', 'desc')
            ->paginate(10);

        return view('siswa.ujian.hasil', compact('riwayatUjian'));
    }
}
