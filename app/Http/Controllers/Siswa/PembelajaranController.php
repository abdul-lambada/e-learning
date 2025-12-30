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
            $ta = \App\Models\PengaturanAkademik::active();
            $tahunAjaran = $ta ? $ta->tahun_ajaran : date('Y') . '/' . (date('Y') + 1);

            $jadwalPelajaran = GuruMengajar::with(['mataPelajaran', 'guru', 'pertemuan.materiPembelajaran'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran', $tahunAjaran)
                ->orderBy('hari')
                ->get();

            // Hitung progress per mapel
            foreach ($jadwalPelajaran as $j) {
                $totalMateri = 0;
                $materiSelesai = 0;

                foreach ($j->pertemuan as $p) {
                    $totalMateri += $p->materiPembelajaran->count();
                    foreach ($p->materiPembelajaran as $m) {
                        $isLearned = \App\Models\ProgresMateri::where('user_id', $siswa->id)
                            ->where('materi_id', $m->id)
                            ->exists();
                        if ($isLearned) $materiSelesai++;
                    }
                }

                $j->progress_percent = $totalMateri > 0 ? round(($materiSelesai / $totalMateri) * 100) : 0;
                $j->materi_selesai = $materiSelesai;
                $j->total_materi = $totalMateri;
            }
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

        return view('siswa.pembelajaran.pertemuan_mobile', compact('pertemuan'));
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

    public function markAsLearned(Request $request, \App\Models\MateriPembelajaran $materi)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        \App\Models\ProgresMateri::updateOrCreate(
            ['user_id' => $user->id, 'materi_id' => $materi->id],
            ['selesai' => true, 'completed_at' => now()]
        );

        // Berikan poin bonus untuk belajar?
        $user->awardPoints(1, "Mempelajari materi: {$materi->judul_materi}");

        // Cek Badge: Pengejar Ilmu (5 materi)
        $learnedCount = \App\Models\ProgresMateri::where('user_id', $user->id)->count();
        if ($learnedCount >= 5) {
            $user->awardBadge('pengejar-ilmu');
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Materi ditandai sebagai selesai dipelajari.');
    }

    public function saveNote(Request $request, Pertemuan $pertemuan)
    {
        $request->validate([
            'konten' => 'required|string',
        ]);

        \App\Models\CatatanPertemuan::updateOrCreate(
            ['user_id' => auth()->id(), 'pertemuan_id' => $pertemuan->id],
            ['konten' => $request->konten]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Catatan disimpan']);
        }

        return back()->with('success', 'Catatan berhasil disimpan.');
    }
    public function toggleBookmark(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:materi,soal_kuis,soal_ujian',
        ]);

        $typeMap = [
            'materi' => \App\Models\MateriPembelajaran::class,
            'soal_kuis' => \App\Models\SoalKuis::class,
            'soal_ujian' => \App\Models\SoalUjian::class,
        ];

        $modelClass = $typeMap[$request->type];

        $bookmark = \App\Models\Bookmark::where('user_id', auth()->id())
            ->where('bookmarkable_id', $request->id)
            ->where('bookmarkable_type', $modelClass)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $status = 'removed';
        } else {
            \App\Models\Bookmark::create([
                'user_id' => auth()->id(),
                'bookmarkable_id' => $request->id,
                'bookmarkable_type' => $modelClass,
            ]);
            $status = 'added';
        }

        return response()->json(['success' => true, 'status' => $status]);
    }
}
