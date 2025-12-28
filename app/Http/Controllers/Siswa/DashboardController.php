<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $siswa */
        $siswa = auth()->user();
        $kelas = $siswa->kelas()->first();

        // 1. Jadwal Hari Ini
        $jadwalHariIni = collect();
        if ($kelas) {
            $hariIndo = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hariIni = $hariIndo[Carbon::now()->format('l')];

            $jadwalHariIni = \App\Models\GuruMengajar::with(['mataPelajaran', 'guru'])
                                ->where('kelas_id', $kelas->id)
                                ->where('hari', $hariIni)
                                ->orderBy('jam_mulai')
                                ->get();
        }

        // 2. Tugas Deadline Terdekat
        $tugasPending = collect();
        if ($kelas) {
            $tugasPending = \App\Models\Tugas::whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id);
            })
            ->whereDoesntHave('pengumpulanTugas', function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            })
            ->where('aktif', true)
            ->where('tanggal_deadline', '>=', now())
            ->orderBy('tanggal_deadline', 'asc')
            ->take(5)
            ->get();
        }

        // 3. Kuis Aktif
        $kuisAktif = 0;
        if ($kelas) {
             $kuisAktif = \App\Models\Kuis::whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id);
            })
            ->where('aktif', true)
            ->where('tanggal_selesai', '>', now())
            ->where('tanggal_mulai', '<=', now())
            ->count();
        }

        // 4. Statistik Absensi
        $absensi = \App\Models\Absensi::where('siswa_id', $siswa->id)
            ->whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                if ($kelas) $q->where('kelas_id', $kelas->id);
            })
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalPertemuan = $absensi->sum();
        $persenHadir = $totalPertemuan > 0 ? (($absensi['hadir'] ?? 0) / $totalPertemuan) * 100 : 0;

        // 5. Nilai Terbaru
        $nilaiTugas = \App\Models\PengumpulanTugas::with('tugas.pertemuan.guruMengajar.mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('status', 'dinilai')
            ->latest('updated_at')
            ->take(3)
            ->get();

        $nilaiKuis = \App\Models\JawabanKuis::with('kuis.pertemuan.guruMengajar.mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('status', 'selesai')
            ->latest('updated_at')
            ->take(3)
            ->get();

        $nilaiTerbaru = collect();
        foreach($nilaiTugas as $nt) {
            $nilaiTerbaru->push([
                'mapel' => $nt->tugas->pertemuan->guruMengajar->mataPelajaran->nama_mapel,
                'jenis' => 'Tugas',
                'judul' => $nt->tugas->judul,
                'nilai' => $nt->nilai,
                'tanggal' => $nt->updated_at
            ]);
        }
        foreach($nilaiKuis as $nk) {
            $nilaiTerbaru->push([
                'mapel' => $nk->kuis->pertemuan->guruMengajar->mataPelajaran->nama_mapel,
                'jenis' => 'Kuis',
                'judul' => $nk->kuis->nama_kuis,
                'nilai' => $nk->nilai,
                'tanggal' => $nk->updated_at
            ]);
        }
        $nilaiTerbaru = $nilaiTerbaru->sortByDesc('tanggal')->take(5);

        // 6. Ujian Terdekat
        $ujianTerdekat = collect();
        if ($kelas) {
            $ujianTerdekat = \App\Models\JadwalUjian::with('ujian.mataPelajaran')
                ->whereHas('ujian', function($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id)->where('aktif', true);
                })
                ->whereDate('tanggal_ujian', '>=', now())
                ->orderBy('tanggal_ujian')
                ->orderBy('jam_mulai')
                ->take(3)
                ->get();
        }

        // 7. Rata-rata Nilai Akhir
        $avgNilai = \App\Models\NilaiAkhir::where('siswa_id', $siswa->id)->avg('nilai_akhir') ?? 0;

        // 8. Chart Data - Aktivitas Belajar Mingguan
        $weeklyData = [];
        $weekLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weekLabels[] = $date->format('D');

            // Tugas dikumpulkan
            $weeklyData['tugas'][] = \App\Models\PengumpulanTugas::where('siswa_id', $siswa->id)
                ->whereDate('created_at', $date)->count();

            // Kuis dikerjakan
            $weeklyData['kuis'][] = \App\Models\JawabanKuis::where('siswa_id', $siswa->id)
                ->whereDate('created_at', $date)->count();

            // Absensi hadir
            $weeklyData['absensi'][] = \App\Models\Absensi::where('siswa_id', $siswa->id)
                ->where('status', 'hadir')
                ->whereDate('created_at', $date)->count();
        }

        // 9. Chart Data - Distribusi Absensi
        $absensiChart = [
            'labels' => ['Hadir', 'Izin', 'Sakit', 'Alpha'],
            'data' => [
                $absensi['hadir'] ?? 0,
                $absensi['izin'] ?? 0,
                $absensi['sakit'] ?? 0,
                $absensi['alpha'] ?? 0,
            ]
        ];

        return view('siswa.dashboard', compact(
            'kelas',
            'jadwalHariIni',
            'tugasPending',
            'kuisAktif',
            'persenHadir',
            'nilaiTerbaru',
            'ujianTerdekat',
            'avgNilai',
            'weeklyData',
            'weekLabels',
            'absensiChart'
        ));
    }
}

