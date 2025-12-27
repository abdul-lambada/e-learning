<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KomponenNilai;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomponenNilaiController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();

        // Ambil mapel yang diampu oleh guru ini
        $mataPelajarans = MataPelajaran::whereHas('guruMengajar', function($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        })->get();

        $komponens = KomponenNilai::whereIn('mata_pelajaran_id', $mataPelajarans->pluck('id'))
            ->with('mataPelajaran')
            ->get();

        return view('guru.komponen_nilai.index', compact('komponens', 'mataPelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'bobot_pendahuluan' => 'required|numeric|min:0|max:100',
            'bobot_absensi' => 'required|numeric|min:0|max:100',
            'bobot_tugas' => 'required|numeric|min:0|max:100',
            'bobot_kuis' => 'required|numeric|min:0|max:100',
            'bobot_ujian' => 'required|numeric|min:0|max:100',
            'kkm' => 'required|numeric|min:0|max:100',
        ]);

        $total = $request->bobot_pendahuluan + $request->bobot_absensi + $request->bobot_tugas + $request->bobot_kuis + $request->bobot_ujian;

        if ($total != 100) {
            return back()->with('error', 'Total bobot harus 100%. Saat ini: ' . $total . '%');
        }

        KomponenNilai::updateOrCreate(
            [
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester' => $request->semester,
            ],
            $request->all()
        );

        return redirect()->route('guru.komponen-nilai.index')->with('success', 'Komponen nilai berhasil disimpan.');
    }
}
