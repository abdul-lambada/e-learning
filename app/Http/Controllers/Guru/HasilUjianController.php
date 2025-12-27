<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\JadwalUjian;
use App\Models\JawabanUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HasilUjianController extends Controller
{
    private function checkAccess($ujian_id)
    {
        $ujian = Ujian::findOrFail($ujian_id);
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $ujian->mata_pelajaran_id)
                        ->where('kelas_id', $ujian->kelas_id)
                        ->exists();
        if (!$isTeaching) abort(403, 'Akses ditolak.');
        return $ujian;
    }

    public function index(JadwalUjian $jadwal)
    {
        $this->checkAccess($jadwal->ujian_id);

        $peserta = JawabanUjian::with(['siswa', 'detailJawaban'])
                    ->where('jadwal_ujian_id', $jadwal->id)
                    ->orderBy('nilai', 'desc')
                    ->get();

        return view('guru.ujian.hasil.index', compact('jadwal', 'peserta'));
    }

    public function show(JawabanUjian $jawaban)
    {
        $this->checkAccess($jawaban->ujian_id);

        $jawaban->load(['detailJawaban.soalUjian', 'siswa', 'ujian']);

        return view('guru.ujian.hasil.koreksi', compact('jawaban'));
    }

    public function update(Request $request, JawabanUjian $jawaban)
    {
        $this->checkAccess($jawaban->ujian_id);

        $request->validate([
            'nilai_essay' => 'array',
            'nilai_essay.*' => 'numeric|min:0',
            'catatan_pengawas' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Update nilai per soal essay
            if ($request->has('nilai_essay')) {
                foreach ($request->nilai_essay as $detailId => $nilai) {
                    $detail = $jawaban->detailJawaban()->find($detailId);
                    if ($detail && $detail->soalUjian->tipe_soal == 'essay') {
                        // Validasi max nilai sesuai bobot
                        if ($nilai > $detail->soalUjian->bobot_nilai) {
                            $nilai = $detail->soalUjian->bobot_nilai; // Clamp to max bobot
                        }
                        $detail->nilai_diperoleh = $nilai;
                        $detail->benar = $nilai > 0;
                        $detail->save();
                    }
                }
            }

            // Recalculate Total
            $totalSkor = $jawaban->detailJawaban()->sum('nilai_diperoleh');
            $totalBobot = $jawaban->ujian->soalUjian()->sum('bobot_nilai');

            $nilaiAkhir = 0;
            if ($totalBobot > 0) {
                $nilaiAkhir = ($totalSkor / $totalBobot) * 100;
            }

            $jawaban->nilai = $nilaiAkhir;
            $jawaban->lulus = $nilaiAkhir >= $jawaban->ujian->nilai_minimal_lulus;
            $jawaban->catatan_pengawas = $request->catatan_pengawas;
            $jawaban->terverifikasi = true; // Mark as graded
            $jawaban->diverifikasi_oleh = Auth::id();
            $jawaban->tanggal_verifikasi = now();

            // Clear system note about waiting grading
            if ($jawaban->catatan_sistem == 'Menunggu penilaian essay.') {
                $jawaban->catatan_sistem = null;
            }

            $jawaban->save();

            DB::commit();
            return redirect()->route('guru.ujian.hasil.index', $jawaban->jadwal_ujian_id)
                             ->with('success', 'Hasil ujian berhasil diperbarui. Nilai Akhir: ' . number_format($nilaiAkhir, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
}
