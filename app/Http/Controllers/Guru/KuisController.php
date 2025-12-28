<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat kuis')->only(['show', 'hasil', 'review', 'index']);
        $this->middleware('permission:kelola kuis')->only(['create', 'store', 'edit', 'update', 'destroy', 'simpanKoreksi']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan daftar kuis milik guru yang sedang login
        $user = Auth::user();

        $kuis = Kuis::has('pertemuan.guruMengajar')
            ->whereHas('pertemuan.guruMengajar', function ($query) use ($user) {
                $query->where('guru_id', $user->id);
            })
            ->with(['pertemuan.guruMengajar.kelas', 'pertemuan.guruMengajar.mataPelajaran'])
            ->latest()
            ->paginate(10);

        return view('guru.kuis.index', compact('kuis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pertemuanId = $request->query('pertemuan_id');
        $pertemuan = Pertemuan::findOrFail($pertemuanId);

        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.kuis.create', compact('pertemuan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'judul_kuis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'durasi_menit' => 'required|integer|min:1',
            'nilai_maksimal' => 'required|integer|min:0|max:100',
            'nilai_minimal_lulus' => 'required|numeric|min:0|max:100',
        ]);

        $pertemuan = Pertemuan::findOrFail($request->pertemuan_id);
         if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $kuis = Kuis::create([
            'pertemuan_id' => $request->pertemuan_id,
            'judul_kuis' => $request->judul_kuis,
            'deskripsi' => $request->deskripsi,
            'instruksi' => $request->instruksi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi_menit' => $request->durasi_menit,
            'tampilkan_timer' => $request->has('tampilkan_timer'),
            'acak_soal' => $request->has('acak_soal'),
            'acak_jawaban' => $request->has('acak_jawaban'),
            'nilai_maksimal' => $request->nilai_maksimal,
            'nilai_minimal_lulus' => $request->nilai_minimal_lulus,
            'max_percobaan' => $request->max_percobaan ?? 1,
            'izinkan_kembali' => $request->has('izinkan_kembali'),
            'aktif' => true,
        ]);

        return redirect()->route('guru.kuis.show', $kuis->id)
                         ->with('success', 'Kuis berhasil dibuat. Silakan tambahkan soal.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $kuis->load('soalKuis');

        return view('guru.kuis.show', compact('kuis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.kuis.edit', compact('kuis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul_kuis' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'durasi_menit' => 'required|integer|min:1',
        ]);

        $kuis->update([
            'judul_kuis' => $request->judul_kuis,
            'deskripsi' => $request->deskripsi,
            'instruksi' => $request->instruksi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi_menit' => $request->durasi_menit,
            'tampilkan_timer' => $request->has('tampilkan_timer'),
            'acak_soal' => $request->has('acak_soal'),
            'acak_jawaban' => $request->has('acak_jawaban'),
            'nilai_maksimal' => $request->nilai_maksimal,
            'nilai_minimal_lulus' => $request->nilai_minimal_lulus,
            'max_percobaan' => $request->max_percobaan,
            'izinkan_kembali' => $request->has('izinkan_kembali'),
            'aktif' => $request->aktif,
        ]);

        return redirect()->route('guru.kuis.show', $kuis->id)
                         ->with('success', 'Kuis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $pertemuanId = $kuis->pertemuan_id;
        $kuis->delete();

        return redirect()->route('guru.pertemuan.show', $pertemuanId)
                         ->with('success', 'Kuis berhasil dihapus.');
    }
    // Menampilkan daftar hasil pengerjaan siswa
    public function hasil(Kuis $kuis)
    {
        if ($kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) abort(403);

        $hasil = \App\Models\JawabanKuis::with('siswa')
                    ->where('kuis_id', $kuis->id)
                    ->orderBy('nilai', 'desc')
                    ->get();

        return view('guru.kuis.hasil', compact('kuis', 'hasil'));
    }

    // Halaman koreksi jawaban siswa (untuk essay)
    public function review(\App\Models\JawabanKuis $jawabanKuis)
    {
        if ($jawabanKuis->kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) abort(403);

        $jawabanKuis->load(['siswa', 'detailJawaban.soalKuis']);

        return view('guru.kuis.review', compact('jawabanKuis'));
    }

    // Simpan nilai koreksi (Essay)
    public function simpanKoreksi(Request $request, \App\Models\JawabanKuis $jawabanKuis)
    {
        if ($jawabanKuis->kuis->pertemuan->guruMengajar->guru_id !== Auth::id()) abort(403);

        $input = $request->validate([
            'nilai_essay' => 'array',
            'nilai_essay.*' => 'numeric|min:0',
        ]);

        $totalNilai = 0;
        $detailJawabans = $jawabanKuis->detailJawaban()->with('soalKuis')->get();

        foreach ($detailJawabans as $detail) {
            // Update nilai essay jika ada input
            if (isset($input['nilai_essay'][$detail->id])) {
                $detail->nilai_diperoleh = $input['nilai_essay'][$detail->id];
                // Tandai benar jika nilai > 0 (opsional logic)
                $detail->benar = $detail->nilai_diperoleh > 0;
                $detail->save();
            }
            $totalNilai += $detail->nilai_diperoleh;
        }

        // Hitung ulang nilai akhir (Skala 100)
        $totalBobotSoal = $jawabanKuis->kuis->soalKuis()->sum('bobot_nilai');
        $nilaiAkhir = $totalBobotSoal > 0 ? ($totalNilai / $totalBobotSoal) * 100 : 0;

        $jawabanKuis->update([
            'nilai' => $nilaiAkhir,
            'lulus' => $nilaiAkhir >= $jawabanKuis->kuis->nilai_minimal_lulus
        ]);

        return redirect()->route('guru.kuis.hasil', $jawabanKuis->kuis_id)
                         ->with('success', 'Penilaian berhasil disimpan.');
    }
}
