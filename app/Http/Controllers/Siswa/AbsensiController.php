<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pertemuan;
use App\Models\GuruMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman absensi hari ini.
     * Menampilkan daftar pertemuan hari ini dan status absensi pengguna.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');
        $dayName = Carbon::now()->isoFormat('dddd');

        // Ambil kelas siswa
        $kelasIds = $user->kelas()->pluck('kelas.id');

        // Cari Pertemuan Hari Ini untuk kelas siswa, yang status pertemuannya 'mulai' atau 'selesai'
        // Kita asumsikan pertemuan dibuat otomatis atau manual oleh guru.
        // Jika tidak ada tabel pertemuan spesifik per tanggal, mungkin logic ini perlu disesuaikan dengan jadwal mingguan.
        // Tapi di sistem ini ada tabel `pertemuan` (jurnal mengajar), jadi kita cek record di sana.

        $pertemuanHariIni = Pertemuan::whereDate('tanggal_pertemuan', $today)
            ->whereHas('guruMengajar', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            })
            ->with(['guruMengajar.mataPelajaran', 'guruMengajar.guru'])
            ->get();

        // Cek status absensi untuk setiap pertemuan
        foreach ($pertemuanHariIni as $pertemuan) {
            $pertemuan->absensi_saya = Absensi::where('pertemuan_id', $pertemuan->id)
                ->where('siswa_id', $user->id)
                ->first();
        }

        return view('siswa.absensi.index', compact('pertemuanHariIni', 'today', 'dayName'));
    }

    /**
     * Simpan absensi siswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'status' => 'required|in:hadir,izin,sakit',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pertemuan = Pertemuan::findOrFail($request->pertemuan_id);

        // Validasi: Apakah pertemuan statusnya 'mulai'?
        // Jika status selesai, mungkin siswa telat atau tidak bisa absen lagi.
        if ($pertemuan->status != 'mulai') {
             return back()->with('error', 'Sesi absensi untuk pertemuan ini sudah ditutup atau belum dibuka.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi: Apakah siswa anggota kelas pertemuan ini?
        $isMyClass = $user->kelas()->where('kelas.id', $pertemuan->guruMengajar->kelas_id)->exists();
        if (!$isMyClass) abort(403);

        Absensi::updateOrCreate(
            [
                'pertemuan_id' => $pertemuan->id,
                'siswa_id' => $user->id
            ],
            [
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'waktu_absen' => now()
            ]
        );

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function riwayat()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $absensi = Absensi::where('siswa_id', $user->id)
            ->with(['pertemuan.guruMengajar.mataPelajaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Hitung statistik sederhana
        $statistik = [
            'hadir' => Absensi::where('siswa_id', $user->id)->where('status', 'hadir')->count(),
            'izin' => Absensi::where('siswa_id', $user->id)->where('status', 'izin')->count(),
            'sakit' => Absensi::where('siswa_id', $user->id)->where('status', 'sakit')->count(),
            'alpha' => Absensi::where('siswa_id', $user->id)->where('status', 'alpha')->count(),
        ];

        return view('siswa.absensi.riwayat', compact('absensi', 'statistik'));
    }
    public function scan()
    {
        return view('siswa.absensi.scan');
    }

    public function scanSubmit(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Minimalist logic: QR contains MEETING_ID
        // In real app, it should be a signed token.
        $pertemuanId = $request->qr_code;
        $pertemuan = Pertemuan::find($pertemuanId);

        if (!$pertemuan) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak valid atau pertemuan tidak ditemukan.']);
        }

        if (!$pertemuan->aktif) {
            return response()->json(['success' => false, 'message' => 'Sesi absensi untuk pertemuan ini belum dibuka atau sudah ditutup.']);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi anggota kelas
        $isMyClass = $user->kelas()->where('kelas.id', $pertemuan->guruMengajar->kelas_id)->exists();
        if (!$isMyClass) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas untuk mata pelajaran ini.']);
        }

        Absensi::updateOrCreate(
            ['pertemuan_id' => $pertemuan->id, 'siswa_id' => $user->id],
            ['status' => 'hadir', 'waktu_absen' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Absensi berhasil! Selamat belajar.']);
    }
}
