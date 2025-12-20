<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function __construct() {
        $this->middleware('permission:kelola siswa kelas');
    }

    /**
     * Menampilkan daftar siswa di kelas perwalian.
     */
    public function index()
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Ambil kelas dimana guru ini menjadi wali kelas
        $kelasWali = Kelas::with(['siswa' => function($q) {
            $q->orderBy('nama_lengkap');
        }])
        ->where('wali_kelas_id', $guru->id)
        ->where('aktif', true)
        ->get();

        return view('guru.siswa.index', compact('kelasWali'));
    }
}
