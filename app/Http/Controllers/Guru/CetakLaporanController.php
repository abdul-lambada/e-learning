<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\KomponenNilai;
use App\Models\PengaturanAkademik;
use App\Models\PengaturanAplikasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CetakLaporanController extends Controller
{
    public function cetakNilai(GuruMengajar $guruMengajar)
    {
        $akademik = PengaturanAkademik::active();
        $settings = PengaturanAplikasi::getSettings();

        // Logical weights (similar to LaporanController)
        $weights = KomponenNilai::where('guru_mengajar_id', $guruMengajar->id)->first();
        if (!$weights) {
            $weights = (object)[
                'bobot_tugas' => 30,
                'bobot_kuis' => 20,
                'bobot_ujian' => 40,
                'bobot_absensi' => 10,
            ];
        }

        $bobot = [
            'tugas' => $weights->bobot_tugas,
            'kuis' => $weights->bobot_kuis,
            'ujian' => $weights->bobot_ujian,
            'absensi' => $weights->bobot_absensi,
        ];

        $data_nilai = [];
        $siswas = $guruMengajar->kelas->users;

        foreach ($siswas as $siswa) {
            $rataTugas = $siswa->pengumpulanTugas()
                ->whereHas('tugas', fn($q) => $q->where('guru_mengajar_id', $guruMengajar->id))
                ->avg('nilai') ?? 0;

            // Kuis related to this GuruMengajar (via Pertemuan)
            $rataKuis = \App\Models\JawabanKuis::where('siswa_id', $siswa->id)
                ->whereHas('kuis.pertemuan', fn($q) => $q->where('guru_mengajar_id', $guruMengajar->id))
                ->avg('nilai') ?? 0;

            // Ujian related to this GuruMengajar (via Mapel & Kelas di Ujian)
            $rataUjian = \App\Models\JawabanUjian::where('siswa_id', $siswa->id)
                ->whereHas('jadwalUjian.ujian', fn($q) => $q->where('mata_pelajaran_id', $guruMengajar->mata_pelajaran_id)
                                                             ->where('kelas_id', $guruMengajar->kelas_id))
                ->avg('nilai') ?? 0;

            $kehadiran = \App\Models\Absensi::where('siswa_id', $siswa->id)
                ->where('guru_mengajar_id', $guruMengajar->id)
                ->where('status', 'hadir')
                ->count();

            $totalPertemuan = \App\Models\Pertemuan::where('guru_mengajar_id', $guruMengajar->id)->count() ?: 1;
            $persenAbsen = ($kehadiran / $totalPertemuan) * 100;

            $nilaiAkhir = ($rataTugas * ($bobot['tugas'] / 100)) +
                          ($rataKuis * ($bobot['kuis'] / 100)) +
                          ($rataUjian * ($bobot['ujian'] / 100)) +
                          ($persenAbsen * ($bobot['absensi'] / 100));

            $data_nilai[] = [
                'nama' => $siswa->nama_lengkap,
                'nis' => $siswa->nis,
                'tugas' => $rataTugas,
                'kuis' => $rataKuis,
                'ujian' => $rataUjian,
                'absensi' => $persenAbsen,
                'akhir' => $nilaiAkhir,
            ];
        }

        $pdf = Pdf::loadView('guru.laporan.pdf_nilai', [
            'data_nilai' => $data_nilai,
            'bobot' => $bobot,
            'guruMengajar' => $guruMengajar,
            'activeAkademik' => $akademik,
            'appSettings' => $settings,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Nilai_' . $guruMengajar->kelas->nama_kelas . '_' . $guruMengajar->mapel->nama_mapel . '.pdf');
    }
}
