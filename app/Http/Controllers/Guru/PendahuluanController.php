<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\MataPelajaran;
use App\Models\Pendahuluan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PendahuluanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat pendahuluan')->only(['index', 'show']);
        $this->middleware('permission:tambah pendahuluan')->only(['create', 'store']);
        $this->middleware('permission:ubah pendahuluan')->only(['edit', 'update']);
        $this->middleware('permission:hapus pendahuluan')->only(['destroy']);
    }

    /**
     * Menampilkan daftar mata pelajaran yang bisa dikelola pendahuluannya.
     */
    public function index()
    {
        /** @var \App\Models\User $guru */
        $guru = Auth::user();

        // Ambil mapel unik yang diajar oleh guru ini
        $mapelIds = GuruMengajar::where('guru_id', $guru->id)
                    ->pluck('mata_pelajaran_id')
                    ->unique();

        $mapelList = MataPelajaran::whereIn('id', $mapelIds)->get();

        return view('guru.pendahuluan.index', compact('mapelList'));
    }

    /**
     * Menampilkan daftar item pendahuluan untuk mapel tertentu.
     */
    public function show(MataPelajaran $mataPelajaran)
    {
        // Validasi akses (Guru harus mengajar mapel ini)
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $mataPelajaran->id)
                        ->exists();
        if (!$isTeaching) abort(403);

        $pendahuluanList = Pendahuluan::where('mata_pelajaran_id', $mataPelajaran->id)
                            ->orderBy('urutan')
                            ->get();

        return view('guru.pendahuluan.show', compact('mataPelajaran', 'pendahuluanList'));
    }

    public function create(MataPelajaran $mataPelajaran)
    {
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $mataPelajaran->id)
                        ->exists();
        if (!$isTeaching) abort(403);

        return view('guru.pendahuluan.create', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'durasi_estimasi' => 'nullable|integer',
            'urutan' => 'required|integer',
            'file_pendukung' => 'nullable|file|max:10240', // 10MB
        ]);

        $input = $request->except(['file_pendukung']);
        $input['aktif'] = $request->has('aktif');
        $input['wajib_diselesaikan'] = $request->has('wajib_diselesaikan');

        if ($request->hasFile('file_pendukung')) {
            $input['file_pendukung'] = $request->file('file_pendukung')->store('pendahuluan', 'public');
        }

        Pendahuluan::create($input);

        return redirect()->route('guru.pendahuluan.show', $request->mata_pelajaran_id)
                         ->with('success', 'Pendahuluan berhasil ditambahkan.');
    }

    public function edit(Pendahuluan $pendahuluan)
    {
        // Validasi Akses
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $pendahuluan->mata_pelajaran_id)
                        ->exists();
        if (!$isTeaching) abort(403);

        return view('guru.pendahuluan.edit', compact('pendahuluan'));
    }

    public function update(Request $request, Pendahuluan $pendahuluan)
    {
        // Validasi Akses
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $pendahuluan->mata_pelajaran_id)
                        ->exists();
        if (!$isTeaching) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'durasi_estimasi' => 'nullable|integer',
            'urutan' => 'required|integer',
            'file_pendukung' => 'nullable|file|max:10240',
        ]);

        $input = $request->except(['file_pendukung']);
        $input['aktif'] = $request->has('aktif');
        $input['wajib_diselesaikan'] = $request->has('wajib_diselesaikan');

        if ($request->hasFile('file_pendukung')) {
            if ($pendahuluan->file_pendukung) {
                Storage::disk('public')->delete($pendahuluan->file_pendukung);
            }
            $input['file_pendukung'] = $request->file('file_pendukung')->store('pendahuluan', 'public');
        }

        $pendahuluan->update($input);

        return redirect()->route('guru.pendahuluan.show', $pendahuluan->mata_pelajaran_id)
                         ->with('success', 'Pendahuluan berhasil diperbarui.');
    }

    public function destroy(Pendahuluan $pendahuluan)
    {
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $pendahuluan->mata_pelajaran_id)
                        ->exists();
        if (!$isTeaching) abort(403);

        if ($pendahuluan->file_pendukung) {
            Storage::disk('public')->delete($pendahuluan->file_pendukung);
        }
        $mapelId = $pendahuluan->mata_pelajaran_id;
        $pendahuluan->delete();

        return redirect()->route('guru.pendahuluan.show', $mapelId)
                         ->with('success', 'Pendahuluan berhasil dihapus.');
    }
}
