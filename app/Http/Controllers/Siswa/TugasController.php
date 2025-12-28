<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\FilePengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat tugas')->only(['index', 'show']);
        $this->middleware('permission:kumpulkan tugas')->only(['store']);
    }

    /**
     * Tampilkan daftar tugas untuk siswa.
     */
    public function index()
    {
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();
        $kelasIds = $siswa->kelas()->pluck('kelas.id');

        $tugasList = Tugas::with(['pertemuan.guruMengajar.mataPelajaran', 'pertemuan.guruMengajar.guru'])
            ->whereHas('pertemuan.guruMengajar', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            })
            ->where('aktif', true)
            ->latest()
            ->paginate(15);

        // Tambahkan info pengumpulan ke tiap tugas
        foreach ($tugasList as $t) {
            $t->pengumpulan = PengumpulanTugas::where('tugas_id', $t->id)
                ->where('siswa_id', $siswa->id)
                ->first();
        }

        return view('siswa.tugas.index', compact('tugasList'));
    }

    public function show(Tugas $tugas)
    {
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();

        // Cek akses: Siswa harus anggota kelas dari tugas ini
        $kelasId = $tugas->pertemuan->guruMengajar->kelas_id;
        $activeKelas = $siswa->kelas()->where('kelas.id', $kelasId)->exists();

        if (!$activeKelas) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Ambil data pengumpulan siswa (jika ada)
        $pengumpulan = PengumpulanTugas::with('filePengumpulan')
                        ->where('tugas_id', $tugas->id)
                        ->where('siswa_id', $siswa->id)
                        ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'link_url' => 'nullable|url',
            'files.*' => 'nullable|file|max:10240', // Max 10MB per file
        ]);

        $tugas = Tugas::findOrFail($request->tugas_id);
        /** @var \App\Models\User $siswa */
        $siswa = Auth::user();

        // Cek deadline
        $isLate = now()->greaterThan($tugas->tanggal_deadline);
        if ($isLate && !$tugas->izinkan_terlambat) {
            return back()->with('error', 'Maaf, batas waktu pengumpulan sudah berakhir.');
        }

        // Buat atau Update Pengumpulan
        $pengumpulan = PengumpulanTugas::firstOrNew([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id
        ]);

        $pengumpulan->tanggal_dikumpulkan = now();
        $pengumpulan->status = $isLate ? 'terlambat' : 'dikumpulkan';
        $pengumpulan->link_url = $request->link_url;
        $pengumpulan->keterangan = $request->keterangan;

        if ($isLate) {
            $pengumpulan->terlambat = true;
            $pengumpulan->hari_terlambat = now()->diffInDays($tugas->tanggal_deadline);
        }

        $pengumpulan->save();

        // Handle File Upload
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('tugas_siswa', 'public');

                FilePengumpulanTugas::create([
                    'pengumpulan_tugas_id' => $pengumpulan->id,
                    'path_file' => $path,
                    'nama_file' => $file->getClientOriginalName(),
                    'tipe_file' => $file->getClientOriginalExtension(),
                    'ukuran_file' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('siswa.tugas.show', $tugas->id)
                         ->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
