<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil kelas dimana siswa terdaftar
        $kelas = $user->kelas()
            ->with(['waliKelas', 'guruMengajar.mataPelajaran', 'guruMengajar.guru'])
            ->withCount('users')
            ->first(); // Asumsi siswa hanya punya 1 kelas aktif

        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('warning', 'Anda belum terdaftar di kelas manapun.');
        }

        // Ambil teman sekelas
        $temanSekelas = $kelas->users()->where('users.id', '!=', $user->id)->get();

        return view('siswa.kelas.index', compact('kelas', 'temanSekelas'));
    }
}
