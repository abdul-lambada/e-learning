<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalSayaController extends Controller
{
    /**
     * Menampilkan daftar jadwal mengajar guru yang sedang login.
     */
    public function index()
    {
        $guruId = Auth::id();
        $ta = \App\Models\PengaturanAkademik::active();
        $tahunAjaran = $ta ? $ta->tahun_ajaran : date('Y') . '/' . (date('Y') + 1);

        $jadwal = GuruMengajar::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->orderBy('hari')
            ->get();

        return view('guru.jadwal.index', compact('jadwal'));
    }

    /**
     * Menampilkan detail jadwal (List Pertemuan).
     */
    public function show(GuruMengajar $jadwal)
    {
        // Pastikan guru yang login adalah pemilik jadwal ini
        if ($jadwal->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        $jadwal->load(['kelas', 'mataPelajaran', 'pertemuan' => function($q) {
            $q->orderBy('pertemuan_ke');
        }]);

        return view('guru.jadwal.show', compact('jadwal'));
    }
    /**
     * Menampilkan dashboard analitik pembelajaran.
     */
    public function analytics(GuruMengajar $jadwal)
    {
        // Pastikan guru yang login adalah pemilik jadwal
        if ($jadwal->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // 1. Data Siswa & KKM
        $siswaKelas = $jadwal->kelas->users;
        
        // Ambil komponen nilai sesuai tahun ajaran jadwal
        $komponenNilai = \App\Models\KomponenNilai::where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
            ->where('tahun_ajaran', $jadwal->tahun_ajaran)
            ->first();
            
        $kkm = $komponenNilai ? $komponenNilai->kkm : 75;

        // 2. Statistik Tugas
        // Ambil semua tugas di mapel ini
        $tugasList = \App\Models\Tugas::whereHas('pertemuan', function($q) use ($jadwal) {
            $q->where('guru_mengajar_id', $jadwal->id);
        })->get();

        $tugasStats = [];
        $rataRataTugas = [];
        $labelsTugas = [];

        foreach ($tugasList as $tugas) {
            $nilaiTugas = \App\Models\PengumpulanTugas::where('tugas_id', $tugas->id)->pluck('nilai')->filter();
            if ($nilaiTugas->count() > 0) {
                $avg = $nilaiTugas->avg();
                $rataRataTugas[] = round($avg, 2);
                $labelsTugas[] = "Tugas " . substr($tugas->judul_tugas, 0, 10) . '...';
            }
        }

        // 3. Early Warning System
        // Siswa dengan rata-rata nilai di bawah KKM
        $siswaBerisiko = [];
        foreach ($siswaKelas as $siswa) {
            $totalNilai = 0;
            $countNilai = 0;

            // Hitung nilai tugas
            foreach ($tugasList as $tugas) {
                $pengumpulan = \App\Models\PengumpulanTugas::where('tugas_id', $tugas->id)
                    ->where('siswa_id', $siswa->id)
                    ->first();

                if ($pengumpulan && $pengumpulan->nilai) {
                    $totalNilai += $pengumpulan->nilai;
                    $countNilai++;
                }
            }

            $currentAvg = $countNilai > 0 ? ($totalNilai / $countNilai) : 0;

            if ($currentAvg > 0 && $currentAvg < $kkm) {
                $siswaBerisiko[] = [
                    'nama' => $siswa->nama_lengkap,
                    'rata_rata' => round($currentAvg, 2),
                    'tugas_pending' => $tugasList->count() - $countNilai
                ];
            }
        }

        return view('guru.jadwal.analytics', compact(
            'jadwal',
            'kkm',
            'rataRataTugas',
            'labelsTugas',
            'siswaBerisiko',
            'siswaKelas'
        ));
    }
}
