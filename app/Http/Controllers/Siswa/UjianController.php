<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\DetailJawabanUjian;
use App\Models\JadwalUjian;
use App\Models\JawabanUjian;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    /**
     * Tampilkan daftar ujian yang tersedia / akan datang untuk siswa ini.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil kelas siswa (via pivot kelas_siswa)
        // Kita butuh ID kelas aktif.
        $kelasIds = $user->kelas()->pluck('kelas.id');

        // Cari jadwal ujian untuk kelas siswa tersebut
        // Urutkan jadwal yang terdekat dengan sekarang
        $today = Carbon::today();

        $jadwals = JadwalUjian::with(['ujian.mataPelajaran'])
                    ->whereHas('ujian', function($q) use ($kelasIds) {
                        $q->whereIn('kelas_id', $kelasIds)->where('aktif', true);
                    })
                    ->whereDate('tanggal_ujian', '>=', $today)
                    ->orderBy('tanggal_ujian', 'asc')
                    ->orderBy('jam_mulai', 'asc')
                    ->paginate(10);

        return view('siswa.ujian.index', compact('jadwals'));
    }

    /**
     * Halaman Info / Konfirmasi Mulai Ujian
     */
    public function show($id)
    {
        $jadwal = JadwalUjian::with(['ujian.mataPelajaran', 'pengawasUser'])->findOrFail($id);

        // Validasi Akses Kelas (Simple check)
        // Assume filtered by index logic, strictly we should check detail permission here.

        // Cek Riwayat
        $riwayat = JawabanUjian::where('jadwal_ujian_id', $id)
                    ->where('siswa_id', Auth::id())
                    ->get();

        $sedangMengerjakan = $riwayat->where('status', 'sedang_dikerjakan')->first();
        $sudahSelesai = $riwayat->where('status', 'selesai')->count() > 0;

        return view('siswa.ujian.show', compact('jadwal', 'riwayat', 'sedangMengerjakan', 'sudahSelesai'));
    }

    /**
     * Start Exam Attempt
     */
    public function start($id)
    {
        $jadwal = JadwalUjian::findOrFail($id);
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Validasi Waktu Jadwal
        $start = Carbon::parse($jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_mulai->format('H:i'));
        $end = Carbon::parse($jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_selesai->format('H:i'));

        // Toleransi? Misal boleh masuk telat.
        if ($now < $start) {
            return back()->with('error', 'Ujian belum dimulai.');
        }

        // Jika lewat waktu selesai, tidak bisa mulai BARU.
        // Tapi kalau sedang mengerjakan?
        if ($now > $end) {
             return back()->with('error', 'Waktu ujian telah berakhir.');
        }

        // 2. Cek Existing Session
        $existing = JawabanUjian::where('jadwal_ujian_id', $id)
                    ->where('siswa_id', $user->id)
                    ->where('status', 'sedang_dikerjakan')
                    ->first();

        if ($existing) {
            return redirect()->route('siswa.ujian.kerjakan', $existing->id);
        }

        // 3. Cek max attempt (Biasanya ujian 1x, kecuali diset lain di Ujian Master)
        $ujian = $jadwal->ujian;
        $attemptCount = JawabanUjian::where('jadwal_ujian_id', $id)->where('siswa_id', $user->id)->count();
        // Kalau max_percobaan di master ujian > 0, cek.
        if ($ujian->max_percobaan > 0 && $attemptCount >= $ujian->max_percobaan) {
             return back()->with('error', 'Anda sudah menyelesaikan kesempatan ujian ini.');
        }

        // 4. Create Session
        DB::beginTransaction();
        try {
            $jawabanUjian = JawabanUjian::create([
                'ujian_id' => $ujian->id,
                'jadwal_ujian_id' => $jadwal->id,
                'siswa_id' => $user->id,
                'percobaan_ke' => $attemptCount + 1,
                'waktu_mulai' => $now,
                'status' => 'sedang_dikerjakan'
            ]);

            // Generate Soal (Acak/Urut)
            $soals = $ujian->soalUjian;
            if ($ujian->acak_soal) {
                $soals = $soals->shuffle();
            } else {
                $soals = $soals->sortBy('nomor_soal');
            }

            foreach ($soals as $soal) {
                DetailJawabanUjian::create([
                    'jawaban_ujian_id' => $jawabanUjian->id,
                    'soal_ujian_id' => $soal->id,
                ]);
            }

            DB::commit();
            return redirect()->route('siswa.ujian.kerjakan', $jawabanUjian->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai ujian: ' . $e->getMessage());
        }
    }

    /**
     * Interface CBT
     */
    public function kerjakan($id)
    {
        $jawabanUjian = JawabanUjian::with(['ujian', 'jadwalUjian', 'detailJawaban.soalUjian'])->findOrFail($id);

        if ($jawabanUjian->siswa_id !== Auth::id()) abort(403);
        if ($jawabanUjian->status !== 'sedang_dikerjakan') {
            return redirect()->route('siswa.ujian.show', $jawabanUjian->jadwal_ujian_id);
        }

        // Hitung End Time
        // Logic: End Time adalah min(Waktu+DurasiUjian, WaktuSelesaiJadwal)
        // Ujian durasi di Master (misal 90 menit). Jadwal misal 08:00 - 10:00 (120 menit).
        // Jika siswa mulai 08:00, selesai 09:30.
        // Jika siswa mulai 09:00, selesai 10:00 (terpotong jadwal).

        $waktuMulai = Carbon::parse($jawabanUjian->waktu_mulai);
        $ujianDurasi = $jawabanUjian->ujian->durasi_menit;

        $jadwal = $jawabanUjian->jadwalUjian;
        $jadwalSelesai = Carbon::parse($jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_selesai->format('H:i'));

        $batasDurasi = $waktuMulai->copy()->addMinutes($ujianDurasi);

        $endTime = $batasDurasi->min($jadwalSelesai);

        if (Carbon::now()->greaterThanOrEqualTo($endTime)) {
            // Auto finish
            return $this->finishLogic($jawabanUjian);
        }

        return view('siswa.ujian.kerjakan', compact('jawabanUjian', 'endTime'));
    }

    public function simpanJawaban(Request $request)
    {
        $input = $request->validate([
            'detail_id' => 'required|exists:detail_jawaban_ujian,id',
            'jawaban' => 'nullable',
            'ragu_ragu' => 'nullable|boolean'
        ]);

        $detail = DetailJawabanUjian::with('jawabanUjian')->findOrFail($input['detail_id']);

        if ($detail->jawabanUjian->siswa_id !== Auth::id()) return response()->json(['error' => 'Unauthorized'], 403);
        if ($detail->jawabanUjian->status !== 'sedang_dikerjakan') return response()->json(['error' => 'Selesai'], 400);

        $soal = $detail->soalUjian ?? SoalUjian::find($detail->soal_ujian_id);

        if ($soal->tipe_soal == 'pilihan_ganda') {
            $detail->jawaban_dipilih = $input['jawaban'];
        } else {
            $detail->jawaban_essay = $input['jawaban'];
        }

        if (isset($input['ragu_ragu'])) {
            $detail->ragu_ragu = $input['ragu_ragu'];
        }

        $detail->save();
        return response()->json(['status' => 'success']);
    }

    public function finish($id)
    {
        $jawabanUjian = JawabanUjian::findOrFail($id);
        if ($jawabanUjian->siswa_id !== Auth::id()) abort(403);
        return $this->finishLogic($jawabanUjian);
    }

    private function finishLogic($jawabanUjian)
    {
        if ($jawabanUjian->status !== 'sedang_dikerjakan') {
             return redirect()->route('siswa.ujian.show', $jawabanUjian->jadwal_ujian_id);
        }

        $jawabanUjian->load('detailJawaban.soalUjian');

        $totalNilai = 0;
        $jumlahBenar = 0;
        $jumlahSalah = 0;
        $tidakDijawab = 0;
        $hasEssay = false;

        foreach ($jawabanUjian->detailJawaban as $detail) {
            $soal = $detail->soalUjian;
            if (!$soal) continue;

            if ($soal->tipe_soal == 'essay') {
                $hasEssay = true;
                $detail->benar = false;
                $detail->nilai_diperoleh = 0; // Manual grading later
            } else {
                if (!$detail->jawaban_dipilih) {
                    $tidakDijawab++;
                    $detail->benar = false;
                    $detail->nilai_diperoleh = 0;
                } elseif ($detail->jawaban_dipilih == $soal->kunci_jawaban) {
                    $jumlahBenar++;
                    $detail->benar = true;
                    $detail->nilai_diperoleh = $soal->bobot_nilai;
                    $totalNilai += $soal->bobot_nilai;
                } else {
                    $jumlahSalah++;
                    $detail->benar = false;
                    $detail->nilai_diperoleh = 0;
                }
            }
            $detail->save();
        }

        $jawabanUjian->waktu_selesai = Carbon::now();
        $jawabanUjian->durasi_detik = Carbon::parse($jawabanUjian->waktu_mulai)->diffInSeconds(Carbon::now());
        $jawabanUjian->status = 'selesai';

        // Calculate Score
        $totalBobotSoal = $jawabanUjian->ujian->soalUjian()->sum('bobot_nilai');

        $nilaiSementara = 0;
        if ($totalBobotSoal > 0) {
            $nilaiSementara = ($totalNilai / $totalBobotSoal) * 100;
        }

        if ($hasEssay) {
            $jawabanUjian->catatan_sistem = "Menunggu penilaian essay.";
            // Nilai sementara hanya dari PG
            $jawabanUjian->nilai = $nilaiSementara; // Save temporary score
        } else {
            $jawabanUjian->nilai = $nilaiSementara;
        }

        $jawabanUjian->jumlah_benar = $jumlahBenar;
        $jawabanUjian->jumlah_salah = $jumlahSalah;
        $jawabanUjian->tidak_dijawab = $tidakDijawab;

        // Lulus check (Only valid if no essay / mixed)
        // If essay exists, lulus status might change later.
        $jawabanUjian->lulus = $nilaiSementara >= $jawabanUjian->ujian->nilai_minimal_lulus;

        $jawabanUjian->save();

        return redirect()->route('siswa.ujian.show', $jawabanUjian->jadwal_ujian_id)->with('success', 'Ujian selesai.');
    }
}
