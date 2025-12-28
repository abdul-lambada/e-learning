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
        $this->middleware('permission:lihat rekap nilai')->only(['nilai', 'pembelajaran']);
        $this->middleware('permission:lihat rekap absensi')->only(['absensi']);
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
                'kelas.users',
                'mataPelajaran',
                'pertemuan.tugas' => function($q) { $q->where('aktif', true); },
                'pertemuan.kuis' => function($q) { $q->where('aktif', true); }
            ])->findOrFail($request->jadwal_id);

            if ($selectedJadwal->guru_id !== $guru->id) {
                abort(403);
            }

            // Ambil Komponen Nilai untuk Mapel ini
            $komponen = \App\Models\KomponenNilai::where('mata_pelajaran_id', $selectedJadwal->mata_pelajaran_id)
                ->where('aktif', true)
                ->first();

            // Default bobot jika tidak ada komponen yang diatur
            $bobot = $komponen ? [
                'tugas' => $komponen->bobot_tugas / 100,
                'kuis' => $komponen->bobot_kuis / 100,
                'ujian' => $komponen->bobot_ujian / 100,
                'absensi' => $komponen->bobot_absensi / 100,
                'pendahuluan' => $komponen->bobot_pendahuluan / 100,
            ] : [
                'tugas' => 0.6,
                'kuis' => 0.4,
                'ujian' => 0,
                'absensi' => 0,
                'pendahuluan' => 0,
            ];

            // Proses Data Nilai per Siswa
            foreach ($selectedJadwal->kelas->users as $siswa) {
                // 1. Nilai Tugas
                $nilaiTugas = [];
                foreach ($selectedJadwal->pertemuan as $pertemuan) {
                    foreach ($pertemuan->tugas as $tugas) {
                        $pengumpulan = $tugas->pengumpulanTugas->where('siswa_id', $siswa->id)->first();
                        $nilaiTugas[] = $pengumpulan ? $pengumpulan->nilai : 0;
                    }
                }
                $avgTugas = count($nilaiTugas) > 0 ? array_sum($nilaiTugas) / count($nilaiTugas) : 0;

                // 2. Nilai Kuis
                $nilaiKuis = [];
                foreach ($selectedJadwal->pertemuan as $pertemuan) {
                    foreach ($pertemuan->kuis as $kuis) {
                        $attempt = $kuis->jawabanKuis->where('siswa_id', $siswa->id)->where('status', 'selesai')->sortByDesc('nilai')->first();
                        $nilaiKuis[] = $attempt ? $attempt->nilai : 0;
                    }
                }
                $avgKuis = count($nilaiKuis) > 0 ? array_sum($nilaiKuis) / count($nilaiKuis) : 0;

                // 3. Nilai Ujian
                $totalUjian = 0;
                $ujianCount = 0;
                // Ambil ujian yang terkait dengan Mapel dan Kelas ini
                $ujianIds = \App\Models\Ujian::where('mata_pelajaran_id', $selectedJadwal->mata_pelajaran_id)
                                ->where('kelas_id', $selectedJadwal->kelas_id)
                                ->pluck('id');

                $jadwalUjians = \App\Models\JadwalUjian::whereIn('ujian_id', $ujianIds)->get();

                foreach($jadwalUjians as $ju) {
                    $jujian = \App\Models\JawabanUjian::where('jadwal_ujian_id', $ju->id)->where('siswa_id', $siswa->id)->where('status', 'selesai')->first();
                    if($jujian) {
                        $totalUjian += $jujian->nilai;
                        $ujianCount++;
                    }
                }
                $avgUjian = $ujianCount > 0 ? $totalUjian / $ujianCount : 0;

                // 4. Nilai Absensi
                $pertemuanIds = \App\Models\Pertemuan::where('guru_mengajar_id', $selectedJadwal->id)->pluck('id');
                $totalHadir = \App\Models\Absensi::whereIn('pertemuan_id', $pertemuanIds)->where('siswa_id', $siswa->id)->where('status', 'hadir')->count();
                $persenHadir = $pertemuanIds->count() > 0 ? ($totalHadir / $pertemuanIds->count()) * 100 : 0;

                // Hitung Nilai Akhir Berdasarkan Bobot
                $nilaiAkhir = ($avgTugas * $bobot['tugas']) +
                              ($avgKuis * $bobot['kuis']) +
                              ($avgUjian * $bobot['ujian']) +
                              ($persenHadir * $bobot['absensi']);

                $dataNilai[] = [
                    'siswa' => $siswa,
                    'avg_tugas' => $avgTugas,
                    'avg_kuis' => $avgKuis,
                    'avg_ujian' => $avgUjian,
                    'persen_hadir' => $persenHadir,
                    'nilai_akhir' => $nilaiAkhir,
                    'bobot' => $bobot
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
            $selectedJadwal = GuruMengajar::with(['kelas.users', 'mataPelajaran'])->findOrFail($request->jadwal_id);

            if ($selectedJadwal->guru_id !== $guru->id) abort(403);

            // Ambil semua pertemuan di jadwal ini
            $pertemuanIds = \App\Models\Pertemuan::where('guru_mengajar_id', $selectedJadwal->id)->pluck('id');

            // Ambil semua record absensi relevant
            $absensis = \App\Models\Absensi::whereIn('pertemuan_id', $pertemuanIds)->get();

            foreach ($selectedJadwal->kelas->users as $siswa) {
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

    public function pembelajaran(Request $request)
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Ambil data pertemuan (Jurnal Mengajar)
        $jurnal = \App\Models\Pertemuan::whereHas('guruMengajar', function($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })
        ->with(['guruMengajar.kelas', 'guruMengajar.mataPelajaran'])
        ->withCount(['absensi as hadir_count' => function($q) {
             $q->where('status', 'hadir');
        }])
        ->orderBy('tanggal_pertemuan', 'desc')
        ->limit(50)
        ->get();

        return view('guru.laporan.pembelajaran', compact('jurnal'));
    }
}
