<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalSayaController extends Controller
{
    /**
     * Menampilkan daftar jadwal mengajar guru yang sedang login.
     */
    public function index()
    {
        $guruId = Auth::id();

        $jadwal = GuruMengajar::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->where('tahun_ajaran', '2024/2025') // Sebaiknya dinamis get current active year
            ->orderBy('hari')
            ->get();

        return view('guru.jadwal.index', compact('jadwal'));
    }

    /**
     * Menampilkan detail jadwal (List Pertemuan).
     */
    public function show(GuruMengajar $jadwal)
    {
        // Pastikan guru yang login adalah pemilik jadwal ini
        if ($jadwal->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        $jadwal->load(['kelas', 'mataPelajaran', 'pertemuan' => function($q) {
            $q->orderBy('pertemuan_ke');
        }]);

        return view('guru.jadwal.show', compact('jadwal'));
    }
}
