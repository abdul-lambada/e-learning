<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\JawabanKuis;
use App\Models\DetailJawabanKuis;
use App\Models\SoalKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KuisController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat kuis')->only(['index', 'show']);
        $this->middleware('permission:kerjakan kuis')->only(['start', 'kerjakan', 'simpanJawaban', 'finish']);
    }

    /**
     * Tampilkan daftar kuis untuk siswa.
     */
    public function index()
    {
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();
        $kelasIds = $siswa->kelas()->pluck('kelas.id');

        $kuisList = Kuis::with(['pertemuan.guruMengajar.mataPelajaran', 'pertemuan.guruMengajar.guru'])
            ->whereHas('pertemuan.guruMengajar', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            })
            ->where('aktif', true)
            ->latest()
            ->paginate(15);

        // Info pengerjaan
        foreach ($kuisList as $k) {
            $k->pernah_mengerjakan = JawabanKuis::where('kuis_id', $k->id)
                                ->where('siswa_id', $siswa->id)
                                ->exists();

            $k->skor_terakhir = JawabanKuis::where('kuis_id', $k->id)
                                ->where('siswa_id', $siswa->id)
                                ->where('status', 'selesai')
                                ->orderBy('id', 'desc')
                                ->first()?->nilai;
        }

        return view('siswa.kuis.index', compact('kuisList'));
    }

    // Halaman Pre-Exam (Info Kuis)
    public function show($id)
    {
        $kuis = Kuis::with('pertemuan.guruMengajar.kelas')->findOrFail($id);

        // Logika akses: Pastikan siswa ada di kelas yang sama
        // (Sederhana: Cek apakah user bisa akses pertemuan ini)
        // Implementasi detail bisa menggunakan Gate/Policy, di sini kita skip dulu demi kecepatan

        // Cek Riwayat Pengerjaan
        $riwayat = JawabanKuis::where('kuis_id', $id)
                    ->where('siswa_id', Auth::id())
                    ->get();

        $sisaPercobaan = $kuis->max_percobaan - $riwayat->count();
        $sedangMengerjakan = $riwayat->where('status', 'sedang_dikerjakan')->first();

        return view('siswa.kuis.show', compact('kuis', 'riwayat', 'sisaPercobaan', 'sedangMengerjakan'));
    }

    // Mulai Mengerjakan (Start Attempt)
    public function start($id)
    {
        $kuis = Kuis::findOrFail($id);
        $user = Auth::user();

        // 1. Cek apakah ada sesi yang sedang berlangsung (Resume)
        $existing = JawabanKuis::where('kuis_id', $id)
                    ->where('siswa_id', $user->id)
                    ->where('status', 'sedang_dikerjakan')
                    ->first();

        if ($existing) {
            return redirect()->route('siswa.kuis.kerjakan', $existing->id);
        }

        // 2. Cek kuota percobaan & Tanggal
        $attemptCount = JawabanKuis::where('kuis_id', $id)->where('siswa_id', $user->id)->count();
        if ($attemptCount >= $kuis->max_percobaan) {
            return redirect()->route('siswa.kuis.show', $id)->with('error', 'Kuota percobaan habis.');
        }

        $now = Carbon::now();
        if ($now < $kuis->tanggal_mulai || $now > $kuis->tanggal_selesai) {
             return redirect()->route('siswa.kuis.show', $id)->with('error', 'Kuis tidak tersedia saat ini.');
        }

        // 3. Buat Sesi Baru (Transaction)
        DB::beginTransaction();
        try {
            $jawabanKuis = JawabanKuis::create([
                'kuis_id' => $kuis->id,
                'siswa_id' => $user->id,
                'percobaan_ke' => $attemptCount + 1,
                'waktu_mulai' => $now,
                'status' => 'sedang_dikerjakan'
            ]);

            // 4. Siapkan Soal (Acak jika perlu)
            $soals = $kuis->soalKuis;
            if ($kuis->acak_soal) {
                $soals = $soals->shuffle();
            } else {
                $soals = $soals->sortBy('nomor_soal');
            }

            // 5. Pre-populate DetailJawabanKuis
            foreach ($soals as $soal) {
                DetailJawabanKuis::create([
                    'jawaban_kuis_id' => $jawabanKuis->id,
                    'soal_kuis_id' => $soal->id,
                ]);
            }

            DB::commit();
            return redirect()->route('siswa.kuis.kerjakan', $jawabanKuis->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai kuis: ' . $e->getMessage());
        }
    }

    // Halaman Ujian (The CBT Interface)
    public function kerjakan($jawabanKuisId)
    {
        $jawabanKuis = JawabanKuis::with(['kuis', 'detailJawaban.soalKuis'])->findOrFail($jawabanKuisId);

        // Security Check
        if ($jawabanKuis->siswa_id !== Auth::id()) abort(403);
        if ($jawabanKuis->status !== 'sedang_dikerjakan') {
            return redirect()->route('siswa.kuis.show', $jawabanKuis->kuis_id);
        }

        // Hitung Sisa Waktu
        $waktuMulai = Carbon::parse($jawabanKuis->waktu_mulai);
        $batasWaktuDurasi = $waktuMulai->copy()->addMinutes($jawabanKuis->kuis->durasi_menit);
        $batasWaktuKuis = Carbon::parse($jawabanKuis->kuis->tanggal_selesai);

        // Batas waktu adalah mana yang lebih cepat: durasi atau deadline kuis
        $endTime = $batasWaktuDurasi->min($batasWaktuKuis);

        // Jika waktu sudah habis saat di-load
        if (Carbon::now()->greaterThanOrEqualTo($endTime)) {
            // Auto submit? Or redirect to handle finish
             return $this->finishLogic($jawabanKuis);
        }

        return view('siswa.kuis.kerjakan', compact('jawabanKuis', 'endTime'));
    }

    // AJAX: Simpan Jawaban Per Soal
    public function simpanJawaban(Request $request)
    {
        $input = $request->validate([
            'detail_id' => 'required|exists:detail_jawaban_kuis,id',
            'jawaban' => 'nullable', // Bisa string (essay) atau char (PG)
        ]);

        $detail = DetailJawabanKuis::with('jawabanKuis')->findOrFail($input['detail_id']);

        // Security Check owner
        if ($detail->jawabanKuis->siswa_id !== Auth::id()) return response()->json(['error' => 'Unauthorized'], 403);
        if ($detail->jawabanKuis->status !== 'sedang_dikerjakan') return response()->json(['error' => 'Ujian selesai'], 400);

        // Update Jawaban
        // Cek tipe soal untuk simpan ke kolom yang benar
        $soal = $detail->soalKuis; // Perlu load relation di query atas or lazy load
        if (!$soal) $soal = SoalKuis::find($detail->soal_kuis_id); // Fallback

        if ($soal->tipe_soal == 'pilihan_ganda') {
            $detail->jawaban_dipilih = $input['jawaban'];
        } else {
            $detail->jawaban_essay = $input['jawaban'];
        }

        $detail->save();

        return response()->json(['status' => 'success']);
    }

    // Submit Finish
    public function finish($jawabanKuisId)
    {
        $jawabanKuis = JawabanKuis::findOrFail($jawabanKuisId);
        if ($jawabanKuis->siswa_id !== Auth::id()) abort(403);

        return $this->finishLogic($jawabanKuis);
    }

    // Logic Hitung Nilai & Finalisasi
    private function finishLogic($jawabanKuis)
    {
        if ($jawabanKuis->status !== 'sedang_dikerjakan') {
             return redirect()->route('siswa.kuis.show', $jawabanKuis->kuis_id);
        }

        $jawabanKuis->load('detailJawaban.soalKuis');

        $totalNilai = 0;
        $jumlahBenar = 0;
        $jumlahSalah = 0;
        $tidakDijawab = 0;
        $hasEssay = false;

        foreach ($jawabanKuis->detailJawaban as $detail) {
            $soal = $detail->soalKuis;
            if (!$soal) continue;

            if ($soal->tipe_soal == 'essay') {
                $hasEssay = true;
                // Essay nilai manual nanti
                $detail->nilai_diperoleh = 0;
                $detail->benar = false;
            } else {
                // Pilihan Ganda
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

        // Update Header
        $jawabanKuis->waktu_selesai = Carbon::now();
        $jawabanKuis->durasi_detik = Carbon::parse($jawabanKuis->waktu_mulai)->diffInSeconds(Carbon::now());
        $jawabanKuis->status = 'selesai';

        // Hitung Nilai Akhir (Skala 100)
        // Rumus: (Total Skor Peroleh / Total Bobot Kuis) * 100
        // Atau: Jika bobot kuis 100, langsung sum.
        // Kita pakai sum bobot soale_kuis dulu.

        $totalBobotSoal = $jawabanKuis->kuis->soalKuis()->sum('bobot_nilai');

        if ($hasEssay) {
            // Jika ada essay, status nilai mungkin "Menunggu Penilaian" atau nilai sementara
            // Kita simpan nilai PG dulu
        }

        // Kalkulasi Nilai Skala 0-100
        if ($totalBobotSoal > 0) {
            $nilaiAkhir = ($totalNilai / $totalBobotSoal) * 100;
        } else {
            $nilaiAkhir = 0;
        }

        $jawabanKuis->nilai = $nilaiAkhir;
        $jawabanKuis->jumlah_benar = $jumlahBenar;
        $jawabanKuis->jumlah_salah = $jumlahSalah;
        $jawabanKuis->tidak_dijawab = $tidakDijawab;

        // Cek Lulus
        $jawabanKuis->lulus = $nilaiAkhir >= $jawabanKuis->kuis->nilai_minimal_lulus;

        $jawabanKuis->save();

        // Award Points
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $poinCompletion = 10;
        $user->awardPoints($poinCompletion, "Menyelesaikan kuis: " . ($jawabanKuis->kuis->judul_kuis ?? $jawabanKuis->kuis->nama_kuis));

        if ($nilaiAkhir >= 100) {
            $user->awardPoints(10, "Nilai sempurna pada kuis: " . ($jawabanKuis->kuis->judul_kuis ?? $jawabanKuis->kuis->nama_kuis));
            $user->awardBadge('quiz-master');
        }

        return redirect()->route('siswa.kuis.show', $jawabanKuis->kuis_id)->with('success', 'Ujian selesai! Terima kasih.');
    }
}
