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
}
