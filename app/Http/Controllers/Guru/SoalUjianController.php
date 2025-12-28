<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SoalUjianController extends Controller
{
    public function __construct() {
        $this->middleware('permission:kelola ujian');
    }

    private function checkAccess(Ujian $ujian) {
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $ujian->mata_pelajaran_id)
                        ->where('kelas_id', $ujian->kelas_id)
                        ->exists();
        if (!$isTeaching) abort(403);
    }

    public function create(Ujian $ujian)
    {
        $this->checkAccess($ujian);
        return view('guru.ujian.soal.create', compact('ujian'));
    }

    public function store(Request $request, Ujian $ujian)
    {
        $this->checkAccess($ujian);

        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,essay',
            'bobot_nilai' => 'required|numeric|min:0',
            'pilihan_a' => 'required_if:tipe_soal,pilihan_ganda',
            'pilihan_b' => 'required_if:tipe_soal,pilihan_ganda',
            'kunci_jawaban' => 'required_if:tipe_soal,pilihan_ganda|in:A,B,C,D,E',
            'gambar_soal' => 'nullable|image|max:2048',
        ]);

        $gambarSoalPath = null;
        if ($request->hasFile('gambar_soal')) {
            $gambarSoalPath = $request->file('gambar_soal')->store('soal_ujian', 'public');
        }

        $lastNo = $ujian->soalUjian()->max('nomor_soal') ?? 0;

        SoalUjian::create([
            'ujian_id' => $ujian->id,
            'nomor_soal' => $lastNo + 1,
            'pertanyaan' => $request->pertanyaan,
            'tipe_soal' => $request->tipe_soal,
            'bobot_nilai' => $request->bobot_nilai,
            'gambar_soal' => $gambarSoalPath,
            'pilihan_a' => $request->pilihan_a,
            'pilihan_b' => $request->pilihan_b,
            'pilihan_c' => $request->pilihan_c,
            'pilihan_d' => $request->pilihan_d,
            'pilihan_e' => $request->pilihan_e,
            'kunci_jawaban' => $request->kunci_jawaban,
            'pembahasan' => $request->pembahasan,
        ]);

        return redirect()->route('guru.ujian.show', $ujian->id)
                         ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Ujian $ujian, SoalUjian $soal)
    {
        $this->checkAccess($ujian);
        if ($soal->ujian_id !== $ujian->id) abort(404);

        return view('guru.ujian.soal.edit', compact('ujian', 'soal'));
    }

    public function update(Request $request, Ujian $ujian, SoalUjian $soal)
    {
        $this->checkAccess($ujian);
        if ($soal->ujian_id !== $ujian->id) abort(404);

        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,essay',
            'bobot_nilai' => 'required|numeric|min:0',
            'pilihan_a' => 'required_if:tipe_soal,pilihan_ganda',
            'pilihan_b' => 'required_if:tipe_soal,pilihan_ganda',
            'kunci_jawaban' => 'required_if:tipe_soal,pilihan_ganda|in:A,B,C,D,E',
            'gambar_soal' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['gambar_soal', '_token', '_method']);

        if ($request->hasFile('gambar_soal')) {
            if ($soal->gambar_soal) {
                Storage::disk('public')->delete($soal->gambar_soal);
            }
            $data['gambar_soal'] = $request->file('gambar_soal')->store('soal_ujian', 'public');
        }

        if ($request->has('hapus_gambar_soal') && $soal->gambar_soal) {
             Storage::disk('public')->delete($soal->gambar_soal);
             $data['gambar_soal'] = null;
        }

        $soal->update($data);

        return redirect()->route('guru.ujian.show', $ujian->id)
                         ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Ujian $ujian, SoalUjian $soal)
    {
        $this->checkAccess($ujian);
        if ($soal->ujian_id !== $ujian->id) abort(404);

        if ($soal->gambar_soal) {
            Storage::disk('public')->delete($soal->gambar_soal);
        }

        $soal->delete();

        // Reorder
        // $ujian->soalUjian()->orderBy('nomor_soal')->get()->each(function($s, $idx) { $s->update(['nomor_soal' => $idx + 1]); });

        return redirect()->route('guru.ujian.show', $ujian->id)
                         ->with('success', 'Soal berhasil dihapus.');
    }
}
