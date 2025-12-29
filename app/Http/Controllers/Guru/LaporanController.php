<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\User;
use App\Services\NilaiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    protected $nilaiService;

    public function __construct(NilaiService $nilaiService)
    {
        $this->nilaiService = $nilaiService;
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
            $selectedJadwal = GuruMengajar::findOrFail($request->jadwal_id);

            if ($selectedJadwal->guru_id !== $guru->id) {
                abort(403);
            }

            $dataNilai = $this->nilaiService->calculateGradesByJadwal($selectedJadwal);
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
