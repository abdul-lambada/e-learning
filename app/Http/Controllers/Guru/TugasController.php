<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pertemuan;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat tugas')->only(['show', 'index']);
        $this->middleware('permission:kelola tugas')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('permission:nilai tugas')->only(['nilai']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $tugas = Tugas::has('pertemuan.guruMengajar') // Only tasks with valid parents
        ->whereHas('pertemuan.guruMengajar', function ($query) use ($user) {
            $query->where('guru_id', $user->id);
        })
        ->with(['pertemuan.guruMengajar.kelas', 'pertemuan.guruMengajar.mataPelajaran'])
        ->latest()
        ->paginate(10);

        return view('guru.tugas.index', compact('tugas'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request)
    {
        $pertemuanId = $request->query('pertemuan_id');
        $pertemuan = Pertemuan::findOrFail($pertemuanId);

        // Authorization check
        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.tugas.create', compact('pertemuan'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_deadline' => 'required|date|after_or_equal:tanggal_mulai',
            'nilai_maksimal' => 'required|integer|min:0|max:100',
        ]);

        $pertemuan = Pertemuan::findOrFail($request->pertemuan_id);

        // Authorization check
        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        Tugas::create([
            'pertemuan_id' => $request->pertemuan_id,
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'instruksi' => $request->instruksi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_deadline' => $request->tanggal_deadline,
            'nilai_maksimal' => $request->nilai_maksimal,
            'upload_file' => $request->active_upload_file ? true : false, // Checkbox handling
            'upload_link' => $request->active_upload_link ? true : false,
            'aktif' => true,
        ]);

        return redirect()->route('guru.pertemuan.show', $pertemuan->id)
                         ->with('success', 'Tugas berhasil dibuat.');
    }

    /**
     * Display the specified task and submissions.
     */
    public function show(Tugas $tugas)
    {
        // Ensure relationships exist (prevent crash if parent meeting deleted)
        if (!$tugas->pertemuan || !$tugas->pertemuan->guruMengajar) {
            abort(404, 'Pertemuan atau data pengajar tidak ditemukan.');
        }

        // Authorization check
        if ($tugas->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $tugas->load(['pengumpulanTugas.siswa', 'pertemuan.guruMengajar.kelas.users']);
        // List semua siswa di kelas ini untuk melihat siapa yang belum mengumpulkan
        $allSiswa = $tugas->pertemuan->guruMengajar->kelas->users;

        return view('guru.tugas.show', compact('tugas', 'allSiswa'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Tugas $tugas)
    {
        if (!$tugas->pertemuan || !$tugas->pertemuan->guruMengajar) {
           abort(404);
        }

        if ($tugas->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }
        return view('guru.tugas.edit', compact('tugas'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Tugas $tugas)
    {
        if (!$tugas->pertemuan || !$tugas->pertemuan->guruMengajar) {
            abort(404);
        }

        if ($tugas->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_deadline' => 'required|date|after_or_equal:tanggal_mulai',
            'nilai_maksimal' => 'required|integer|min:0|max:100',
        ]);

        $tugas->update([
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'instruksi' => $request->instruksi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_deadline' => $request->tanggal_deadline,
            'nilai_maksimal' => $request->nilai_maksimal,
            'upload_file' => $request->active_upload_file ? true : false,
            'upload_link' => $request->active_upload_link ? true : false,
            'aktif' => $request->aktif,
        ]);

        return redirect()->route('guru.pertemuan.show', $tugas->pertemuan_id)
                         ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Tugas $tugas)
    {
        if (!$tugas->pertemuan || !$tugas->pertemuan->guruMengajar) {
             // If relationship broken, allow delete if admin or owner, but safely.
             // Here assuming strict check, just abort.
             abort(404);
        }

        if ($tugas->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $pertemuanId = $tugas->pertemuan_id;
        $tugas->delete();

        return redirect()->route('guru.pertemuan.show', $pertemuanId)
                         ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Give grade to a submission.
     */
    public function nilai(Request $request, $pengumpulanId)
    {
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);

        // Authorization
        // Authorization safety check
        if (!$pengumpulan->tugas || !$pengumpulan->tugas->pertemuan || !$pengumpulan->tugas->pertemuan->guruMengajar) {
            abort(404);
        }

        if ($pengumpulan->tugas->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'komentar_guru' => 'nullable|string'
        ]);

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'komentar_guru' => $request->komentar_guru,
            'status' => 'dinilai',
            'tanggal_dinilai' => now(),
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }
}
