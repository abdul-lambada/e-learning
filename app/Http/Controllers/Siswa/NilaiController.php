<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Tampilkan rekap nilai akhir siswa.
     */
    public function index()
    {
        $siswaId = Auth::id();
        $nilai = NilaiAkhir::with(['mataPelajaran', 'kelas'])
            ->where('siswa_id', $siswaId)
            ->orderBy('id', 'desc')
            ->get();

        return view('siswa.nilai.index', compact('nilai'));
    }

    public function cetak()
    {
        $user = Auth::user();
        $nilai = NilaiAkhir::with(['mataPelajaran', 'kelas'])
            ->where('siswa_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('siswa.nilai.pdf', [
            'nilai' => $nilai,
            'user' => $user,
            'tanggal' => now()->translatedFormat('d F Y'),
        ]);

        return $pdf->download('Laporan-Nilai-' . $user->username . '.pdf');
    }
}
