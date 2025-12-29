<?php

namespace App\Services;

use App\Models\GuruMengajar;
use App\Models\KomponenNilai;
use App\Models\Ujian;
use App\Models\JadwalUjian;
use App\Models\JawabanUjian;
use App\Models\Pertemuan;
use App\Models\Absensi;
use Illuminate\Support\Collection;

class NilaiService
{
    /**
     * Calculate all grades for a specific class schedule (Jadwal).
     *
     * @param GuruMengajar $jadwal
     * @return array
     */
    public function calculateGradesByJadwal(GuruMengajar $jadwal)
    {
        $jadwal->load([
            'kelas.users',
            'mataPelajaran',
            'pertemuan.tugas' => function($q) { $q->where('aktif', true); },
            'pertemuan.kuis' => function($q) { $q->where('aktif', true); }
        ]);

        $komponen = KomponenNilai::where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
            ->where('aktif', true)
            ->first();

        $bobot = $this->getBobot($komponen);
        $dataNilai = [];

        foreach ($jadwal->kelas->users as $siswa) {
            $avgTugas = $this->calculateAvgTugas($jadwal, $siswa);
            $avgKuis = $this->calculateAvgKuis($jadwal, $siswa);
            $avgUjian = $this->calculateAvgUjian($jadwal, $siswa);
            $persenHadir = $this->calculatePersenHadir($jadwal, $siswa);

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

        return $dataNilai;
    }

    protected function getBobot($komponen)
    {
        if ($komponen) {
            return [
                'tugas' => $komponen->bobot_tugas / 100,
                'kuis' => $komponen->bobot_kuis / 100,
                'ujian' => $komponen->bobot_ujian / 100,
                'absensi' => $komponen->bobot_absensi / 100,
                'pendahuluan' => $komponen->bobot_pendahuluan / 100,
            ];
        }

        return [
            'tugas' => 0.6,
            'kuis' => 0.4,
            'ujian' => 0,
            'absensi' => 0,
            'pendahuluan' => 0,
        ];
    }

    protected function calculateAvgTugas($jadwal, $siswa)
    {
        $nilaiTugas = [];
        foreach ($jadwal->pertemuan as $pertemuan) {
            foreach ($pertemuan->tugas as $tugas) {
                $pengumpulan = $tugas->pengumpulanTugas->where('siswa_id', $siswa->id)->first();
                $nilaiTugas[] = $pengumpulan ? $pengumpulan->nilai : 0;
            }
        }
        return count($nilaiTugas) > 0 ? array_sum($nilaiTugas) / count($nilaiTugas) : 0;
    }

    protected function calculateAvgKuis($jadwal, $siswa)
    {
        $nilaiKuis = [];
        foreach ($jadwal->pertemuan as $pertemuan) {
            foreach ($pertemuan->kuis as $kuis) {
                $attempt = $kuis->jawabanKuis->where('siswa_id', $siswa->id)
                    ->where('status', 'selesai')
                    ->sortByDesc('nilai')
                    ->first();
                $nilaiKuis[] = $attempt ? $attempt->nilai : 0;
            }
        }
        return count($nilaiKuis) > 0 ? array_sum($nilaiKuis) / count($nilaiKuis) : 0;
    }

    protected function calculateAvgUjian($jadwal, $siswa)
    {
        $ujianIds = Ujian::where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
            ->where('kelas_id', $jadwal->kelas_id)
            ->pluck('id');

        $jadwalUjians = JadwalUjian::whereIn('ujian_id', $ujianIds)->get();

        $totalUjian = 0;
        $ujianCount = 0;
        foreach ($jadwalUjians as $ju) {
            $jujian = JawabanUjian::where('jadwal_ujian_id', $ju->id)
                ->where('siswa_id', $siswa->id)
                ->where('status', 'selesai')
                ->first();
            if ($jujian) {
                $totalUjian += $jujian->nilai;
                $ujianCount++;
            }
        }
        return $ujianCount > 0 ? $totalUjian / $ujianCount : 0;
    }

    protected function calculatePersenHadir($jadwal, $siswa)
    {
        $pertemuanIds = Pertemuan::where('guru_mengajar_id', $jadwal->id)->pluck('id');
        $totalHadir = Absensi::whereIn('pertemuan_id', $pertemuanIds)
            ->where('siswa_id', $siswa->id)
            ->where('status', 'hadir')
            ->count();
        return $pertemuanIds->count() > 0 ? ($totalHadir / $pertemuanIds->count()) * 100 : 0;
    }
}
