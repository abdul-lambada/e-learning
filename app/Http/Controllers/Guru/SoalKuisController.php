<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\SoalKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SoalKuisController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelola kuis');
    }

    public function create(Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }
        return view('guru.kuis.soal.create', compact('kuis'));
    }

    public function store(Request $request, Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,essay',
            'bobot_nilai' => 'required|numeric|min:0',
            // Validasi conditional untuk pilihan ganda
            'pilihan_a' => 'required_if:tipe_soal,pilihan_ganda',
            'pilihan_b' => 'required_if:tipe_soal,pilihan_ganda',
            'kunci_jawaban' => 'required_if:tipe_soal,pilihan_ganda|in:A,B,C,D,E',
            'gambar_soal' => 'nullable|image|max:2048',
        ]);

        // Upload gambar soal
        $gambarSoalPath = null;
        if ($request->hasFile('gambar_soal')) {
            $gambarSoalPath = $request->file('gambar_soal')->store('soal_kuis', 'public');
        }

        // Hitung nomor soal otomatis (max + 1)
        $lastNo = $kuis->soalKuis()->max('nomor_soal') ?? 0;

        SoalKuis::create([
            'kuis_id' => $kuis->id,
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

        return redirect()->route('guru.kuis.show', $kuis->id)
                         ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(SoalKuis $soal)
    {
        if ($soal->kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }
        return view('guru.kuis.soal.edit', compact('soal'));
    }

    public function update(Request $request, SoalKuis $soal)
    {
        if ($soal->kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

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
            // Delete old
            if ($soal->gambar_soal) {
                Storage::disk('public')->delete($soal->gambar_soal);
            }
            $data['gambar_soal'] = $request->file('gambar_soal')->store('soal_kuis', 'public');
        }

        // Logic hapus gambar jika diminta (checkbox)
        if ($request->has('hapus_gambar_soal') && $soal->gambar_soal) {
             Storage::disk('public')->delete($soal->gambar_soal);
             $data['gambar_soal'] = null;
        }

        $soal->update($data);

        return redirect()->route('guru.kuis.show', $soal->kuis_id)
                         ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(SoalKuis $soal)
    {
        if ($soal->kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $kuisId = $soal->kuis_id;

        if ($soal->gambar_soal) {
            Storage::disk('public')->delete($soal->gambar_soal);
        }

        $soal->delete();

        // Re-order nomor soal (optional, but good for UX)
        // ... logic reorder here if needed ...

        return redirect()->route('guru.kuis.show', $kuisId)
                         ->with('success', 'Soal berhasil dihapus.');
    }
}
