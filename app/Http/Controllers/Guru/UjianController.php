<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat ujian')->only(['index', 'show']);
        $this->middleware('permission:kelola ujian')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Menampilkan daftar ujian.
     */
    public function index()
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Cari ID Mapel yang diajar guru
        $mapelIds = GuruMengajar::where('guru_id', $guru->id)->pluck('mata_pelajaran_id');
        $kelasIds = GuruMengajar::where('guru_id', $guru->id)->pluck('kelas_id');

        // Tampilkan ujian yang mata pelajaran dan kelasnya relevan dengan guru
        $ujianList = Ujian::with(['mataPelajaran', 'kelas', 'jadwalUjian'])
                    ->whereIn('mata_pelajaran_id', $mapelIds)
                    ->whereIn('kelas_id', $kelasIds)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('guru.ujian.index', compact('ujianList'));
    }

    /**
     * Form buat ujian baru.
     */
    public function create()
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Load data untuk dropdown
        $daftarMengajar = GuruMengajar::with(['mataPelajaran', 'kelas'])
                            ->where('guru_id', $guru->id)
                            ->get();

        // Group by Mapel-Kelas agar tidak duplikat jika jadwal beda hari
        // Sebenarnya kita butuh list unique (Mapel, Kelas).
        $formOptions = collect();
        foreach($daftarMengajar as $ajar) {
            $key = $ajar->mata_pelajaran_id . '-' . $ajar->kelas_id;
            if (!$formOptions->has($key)) {
                $formOptions->put($key, [
                    'mapel' => $ajar->mataPelajaran,
                    'kelas' => $ajar->kelas
                ]);
            }
        }

        return view('guru.ujian.create', compact('formOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'option_id' => 'required', // Format: mapel_id-kelas_id
            'nama_ujian' => 'required|string|max:255',
            'jenis_ujian' => 'required|in:UTS,UAS,Harian,Lainnya',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:5',
            'jumlah_soal' => 'required|integer|min:1',
            'nilai_maksimal' => 'required|integer|min:10',
            'nilai_minimal_lulus' => 'required|numeric|min:0',
        ]);

        list($mapelId, $kelasId) = explode('-', $request->option_id);

        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Cek permission akses mapel/kelas (double check)
        $isTeaching = GuruMengajar::where('guru_id', $guru->id)
                        ->where('mata_pelajaran_id', $mapelId)
                        ->where('kelas_id', $kelasId)
                        ->exists();
        if (!$isTeaching) abort(403);

        $ujian = Ujian::create([
            'mata_pelajaran_id' => $mapelId,
            'kelas_id' => $kelasId,
            'kode_ujian' => 'U-' . time(), // Auto generate
            'nama_ujian' => $request->nama_ujian,
            'jenis_ujian' => $request->jenis_ujian,
            'deskripsi' => $request->deskripsi,
            'instruksi' => $request->instruksi,
            'durasi_menit' => $request->durasi_menit,
            'jumlah_soal' => $request->jumlah_soal,
            'nilai_maksimal' => $request->nilai_maksimal,
            'nilai_minimal_lulus' => $request->nilai_minimal_lulus,
            'acak_soal' => $request->has('acak_soal'),
            'aktif' => $request->has('aktif'),
            'tahun_ajaran' => '2024/2025', // Hardcode/Config
            'semester' => 'Ganjil', // Config
            'diimport_oleh' => $guru->id,
        ]);

        return redirect()->route('guru.ujian.show', $ujian->id)->with('success', 'Ujian berhasil dibuat. Silakan tambah soal.');
    }

    public function show(Ujian $ujian)
    {
         // Validasi akses
         /** @var \App\Models\User $guru */
         $guru = Auth::user();
         $isTeaching = GuruMengajar::where('guru_id', $guru->id)
                        ->where('mata_pelajaran_id', $ujian->mata_pelajaran_id)
                        ->where('kelas_id', $ujian->kelas_id)
                        ->exists();
         if (!$isTeaching && !$guru->hasRole('admin')) abort(403);

         $ujian->load(['soalUjian', 'jadwalUjian']);

         return view('guru.ujian.show', compact('ujian'));
    }

    public function edit(Ujian $ujian)
    {
        // ... (similar to create/show logic)
        return view('guru.ujian.edit', compact('ujian'));
    }

    public function update(Request $request, Ujian $ujian)
    {
        // ... update logic
        $request->validate([
            'nama_ujian' => 'required|string',
            'durasi_menit' => 'required|integer',
        ]);

        $ujian->update($request->all()); // Simplify for now
        return redirect()->route('guru.ujian.show', $ujian->id)->with('success', 'Ujian diperbarui.');
    }

    public function destroy(Ujian $ujian)
    {
        $ujian->delete();
        return redirect()->route('guru.ujian.index')->with('success', 'Ujian dihapus.');
    }
}
