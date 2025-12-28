<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\Pertemuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembelajaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat jadwal')->only(['index', 'show']); // Jadwal & list pertemuan
        $this->middleware('permission:lihat materi')->only(['pertemuan']); // Detail materi
        $this->middleware('permission:isi absensi')->only(['absenMandiri']);
    }

    /**
     * Menampilkan daftar mata pelajaran yang diikuti siswa.
     * Berdasarkan Kelas yang sedang aktif diikuti siswa.
     */
    public function index()
    {
        $siswa = User::with('kelas')->find(Auth::id());

        // Ambil kelas terakhir/aktif siswa (Asumsi 1 siswa 1 kelas aktif per semester)
        // Logikanya bisa dikembangkan jika siswa punya history kelas.
        // Kita ambil kelas yang paling baru daftarnya (descending ID pivot)
        $kelasSiswa = $siswa->kelas()->orderByPivot('id', 'desc')->first();

        $jadwalPelajaran = collect();
        $kelas = null;

        if ($kelasSiswa) {
            $kelas = $kelasSiswa;
            // Ambil semua jadwal pelajaran untuk kelas ini
            $jadwalPelajaran = GuruMengajar::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran', '2024/2025') // Sebaiknya dinamis
                ->orderBy('hari')
                ->get();
        }

        return view('siswa.pembelajaran.index', compact('jadwalPelajaran', 'kelas'));
    }

    /**
     * Menampilkan detail mata pelajaran (List Pertemuan).
     */
    public function show(GuruMengajar $jadwal)
    {
        // Validasi: Pastikan siswa ini anggota dari kelas jadwal ini
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();
        $isAnggotaKelas = $siswa->kelas()->where('kelas.id', $jadwal->kelas_id)->exists();

        if (!$isAnggotaKelas) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $jadwal->load(['mataPelajaran', 'guru', 'pertemuan' => function($q) {
            // Tampilkan pertemuan yang statusnya berlangsung atau selesai, atau dijadwalkan tapi aktif
            $q->where('aktif', true)
              ->orderBy('pertemuan_ke');
        }]);

        return view('siswa.pembelajaran.show', compact('jadwal'));
    }

    /**
     * Menampilkan detail pertemuan dan materi (Read-only).
     */
    public function pertemuan(Pertemuan $pertemuan)
    {
        // Validasi akses siswa ke pertemuan ini via Jadwal -> Kelas
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();
        $jadwal = $pertemuan->guruMengajar;
        $isAnggotaKelas = $siswa->kelas()->where('kelas.id', $jadwal->kelas_id)->exists();

        if (!$isAnggotaKelas) {
            abort(403, 'Anda tidak memiliki akses ke pertemuan ini.');
        }

        // Load materi, tugas, dll
        // Load materi, tugas, dll
        $pertemuan->load(['materiPembelajaran' => function($q) {
            $q->where('aktif', true)->orderBy('urutan');
        }, 'tugas' => function($q) {
            $q->where('aktif', true);
        }, 'kuis' => function($q) {
            $q->where('aktif', true);
        }, 'guruMengajar.mataPelajaran', 'guruMengajar.guru']);

        return view('siswa.pembelajaran.pertemuan', compact('pertemuan'));
    }
    public function absenMandiri(Pertemuan $pertemuan)
    {
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();

        // Validasi akses pertemuan
        $jadwal = $pertemuan->guruMengajar;
        $isAnggotaKelas = $siswa->kelas()->where('kelas.id', $jadwal->kelas_id)->exists();
        if (!$isAnggotaKelas) abort(403);

        if (!$pertemuan->aktif) return back()->with('error', 'Pertemuan tidak aktif.');

        \App\Models\Absensi::firstOrCreate(
            ['pertemuan_id' => $pertemuan->id, 'siswa_id' => $siswa->id],
            ['status' => 'hadir', 'waktu_absen' => now()]
        );

        return back()->with('success', 'Presensi berhasil dicatat (Hadir).');
    }
}
