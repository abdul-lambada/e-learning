<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JawabanUjian;
use App\Models\JadwalUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat ujian')->only(['index', 'hasil']);
    }

    /**
     * Tampilkan jadwal ujian siswa.
     * (Fitur ini mungkin sudah ada atau digabung dgn index, tapi kita fokus ke Hasil Ujian skrg)
     */
    public function index()
    {
        // TODO: Implementasi Jadwal Ujian
        // Untuk sekarang return view empty atau redirect
        return view('siswa.ujian.index');
    }

    /**
     * Tampilkan riwayat hasil ujian siswa.
     */
    public function hasil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil riwayat jawaban ujian yang sudah selesai
        // Relasi: JawabanUjian -> JadwalUjian -> Ujian -> MataPelajaran
        $riwayatUjian = JawabanUjian::where('siswa_id', $user->id)
            ->where('status', 'selesai')
            ->with(['jadwalUjian.ujian.mataPelajaran', 'jadwalUjian.ujian'])
            ->orderBy('waktu_selesai', 'desc')
            ->paginate(10);

        return view('siswa.ujian.hasil', compact('riwayatUjian'));
    }
}
