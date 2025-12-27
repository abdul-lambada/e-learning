<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\NilaiAkhir;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    /**
     * Tampilkan overview kelas binaan.
     */
    public function index()
    {
        $user = Auth::user();
        $kelasWali = Kelas::withCount('siswa')
            ->where('wali_kelas_id', $user->id)
            ->get();

        if ($kelasWali->isEmpty()) {
            return view('guru.wali-kelas.index', [
                'kelas' => null
            ]);
        }

        // Default ke kelas pertama
        $kelas = $kelasWali->first();
        $students = $kelas->siswa()
            ->orderBy('nama_lengkap', 'asc')
            ->paginate(25);

        return view('guru.wali-kelas.index', compact('kelas', 'students', 'kelasWali'));
    }

    /**
     * Tampilkan detail kelas tertentu.
     */
    public function show($id)
    {
        $user = Auth::user();
        $kelas = Kelas::withCount('siswa')
            ->where('id', $id)
            ->where('wali_kelas_id', $user->id)
            ->firstOrFail();

        $kelasWali = Kelas::where('wali_kelas_id', $user->id)->get();

        $students = $kelas->siswa()
            ->orderBy('nama_lengkap', 'asc')
            ->paginate(25);

        return view('guru.wali-kelas.index', compact('kelas', 'students', 'kelasWali'));
    }

    /**
     * Tampilkan detail perkembangan siswa di kelas binaan.
     */
    public function showSiswa($kelasId, $siswaId)
    {
        $user = Auth::user();
        $kelas = Kelas::where('id', $kelasId)
            ->where('wali_kelas_id', $user->id)
            ->firstOrFail();

        $siswa = User::findOrFail($siswaId);

        // Cek apakah siswa memang di kelas tersebut
        if (!$siswa->kelas()->where('kelas.id', $kelasId)->exists()) {
            abort(404);
        }

        // Ambil ringkasan nilai
        $nilaiSummary = NilaiAkhir::with('mataPelajaran')
            ->where('siswa_id', $siswaId)
            ->where('kelas_id', $kelasId)
            ->get();

        // Ambil statistik absensi
        $absensiStats = Absensi::where('siswa_id', $siswaId)
            ->whereHas('pertemuan.guruMengajar', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('guru.wali-kelas.siswa-detail', compact('kelas', 'siswa', 'nilaiSummary', 'absensiStats'));
    }
}
