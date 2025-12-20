<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat laporan');
    }

    public function nilai(Request $request)
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Ambil daftar kelas yang diajar guru ini
        $daftarKelas = GuruMengajar::with(['mataPelajaran', 'kelas'])
                        ->where('guru_id', $guru->id)
                        ->get();

        $selectedJadwal = null;
        $dataNilai = [];

        if ($request->has('jadwal_id')) {
            $selectedJadwal = GuruMengajar::with([
                'kelas.siswa',
                'mataPelajaran',
                'tugas' => function($q) { $q->where('aktif', true); },
                'kuis' => function($q) { $q->where('aktif', true); }
            ])->findOrFail($request->jadwal_id);

            if ($selectedJadwal->guru_id !== $guru->id) {
                abort(403);
            }

            // Proses Data Nilai per Siswa
            foreach ($selectedJadwal->kelas->siswa as $siswa) {
                $nilaiTugas = [];
                foreach ($selectedJadwal->tugas as $tugas) {
                    $pengumpulan = $tugas->pengumpulanTugas->where('siswa_id', $siswa->id)->first();
                    $nilaiTugas[] = $pengumpulan ? $pengumpulan->nilai : 0;
                }

                $nilaiKuis = [];
                foreach ($selectedJadwal->kuis as $kuis) {
                    // Ambil nilai tertinggi jika ada multiple attempt (atau attempt terakhir yg selesai)
                    $attempt = $kuis->jawabanKuis->where('siswa_id', $siswa->id)->where('status', 'selesai')->sortByDesc('nilai')->first();
                    $nilaiKuis[] = $attempt ? $attempt->nilai : 0;
                }

                // Hitung Rata-rata
                $avgTugas = count($nilaiTugas) > 0 ? array_sum($nilaiTugas) / count($nilaiTugas) : 0;
                $avgKuis = count($nilaiKuis) > 0 ? array_sum($nilaiKuis) / count($nilaiKuis) : 0;

                // Nilai Akhir (Contoh: 60% Tugas, 40% Kuis)
                $nilaiAkhir = ($avgTugas * 0.6) + ($avgKuis * 0.4);

                $dataNilai[] = [
                    'siswa' => $siswa,
                    'tugas' => $nilaiTugas,
                    'avg_tugas' => $avgTugas,
                    'kuis' => $nilaiKuis,
                    'avg_kuis' => $avgKuis,
                    'nilai_akhir' => $nilaiAkhir
                ];
            }
        }

        return view('guru.laporan.nilai', compact('daftarKelas', 'selectedJadwal', 'dataNilai'));
    }

    public function absensi(Request $request)
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        $daftarKelas = GuruMengajar::with(['mataPelajaran', 'kelas'])
                        ->where('guru_id', $guru->id)
                        ->get();

        $selectedJadwal = null;
        $dataAbsensi = [];

        if ($request->has('jadwal_id')) {
            $selectedJadwal = GuruMengajar::with(['kelas.siswa', 'mataPelajaran'])->findOrFail($request->jadwal_id);

            if ($selectedJadwal->guru_id !== $guru->id) abort(403);

            // Ambil semua pertemuan di jadwal ini
            $pertemuanIds = \App\Models\Pertemuan::where('guru_mengajar_id', $selectedJadwal->id)->pluck('id');

            // Ambil semua record absensi relevant
            $absensis = \App\Models\Absensi::whereIn('pertemuan_id', $pertemuanIds)->get();

            foreach ($selectedJadwal->kelas->siswa as $siswa) {
                $siswaAbsen = $absensis->where('siswa_id', $siswa->id);

                $dataAbsensi[] = [
                    'siswa' => $siswa,
                    'hadir' => $siswaAbsen->where('status', 'hadir')->count(),
                    'izin' => $siswaAbsen->where('status', 'izin')->count(),
                    'sakit' => $siswaAbsen->where('status', 'sakit')->count(),
                    'alpha' => $siswaAbsen->where('status', 'alpha')->count(),
                    'total_pertemuan' => $pertemuanIds->count()
                ];
            }
        }

        return view('guru.laporan.absensi', compact('daftarKelas', 'selectedJadwal', 'dataAbsensi'));
    }
}
