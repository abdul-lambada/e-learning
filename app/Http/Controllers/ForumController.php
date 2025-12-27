<?php

namespace App\Http\Controllers;

use App\Models\ForumTopik;
use App\Models\ForumBalasan;
use App\Models\GuruMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // If a specific class is selected
        if ($request->has('jadwal_id')) {
            $jadwal = GuruMengajar::findOrFail($request->jadwal_id);
            $topiks = ForumTopik::where('guru_mengajar_id', $jadwal->id)
                ->with(['user', 'balasan'])
                ->withCount('balasan')
                ->orderBy('pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $layout = 'layouts.' . $user->peran;
            return view('forum.index', compact('topiks', 'jadwal', 'layout'));
        }

        // Default view: show list of classes the user is involved in
        if ($user->peran == 'guru') {
            $daftarKelas = GuruMengajar::with(['kelas', 'mataPelajaran'])
                ->where('guru_id', $user->id)
                ->get();
        } else if ($user->peran == 'siswa') {
            $daftarKelas = GuruMengajar::with(['kelas', 'mataPelajaran'])
                ->whereHas('kelas.siswa', function($q) use ($user) {
                    $q->where('siswa_id', $user->id);
                })
                ->get();
        } else {
            // Admin can see everything? Maybe just show list of classes
            $daftarKelas = GuruMengajar::with(['kelas', 'mataPelajaran', 'guru'])->get();
        }

        $layout = 'layouts.' . $user->peran;
        return view('forum.select_class', compact('daftarKelas', 'layout'));
    }

    public function show(ForumTopik $topik)
    {
        $topik->load(['user', 'balasan.user', 'guruMengajar.kelas', 'guruMengajar.mataPelajaran']);
        $layout = 'layouts.' . Auth::user()->peran;
        return view('forum.show', compact('topik', 'layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_mengajar_id' => 'required|exists:guru_mengajar,id',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'pertemuan_id' => 'nullable|exists:pertemuan,id',
        ]);

        $topik = ForumTopik::create([
            'guru_mengajar_id' => $request->guru_mengajar_id,
            'pertemuan_id' => $request->pertemuan_id,
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'konten' => $request->konten,
            'pinned' => $request->has('pinned') && Auth::user()->peran == 'guru',
        ]);

        return redirect()->route('forum.show', $topik)->with('success', 'Topik diskusi berhasil dibuat.');
    }

    public function reply(Request $request, ForumTopik $topik)
    {
        $request->validate([
            'konten' => 'required|string',
        ]);

        ForumBalasan::create([
            'forum_topik_id' => $topik->id,
            'user_id' => Auth::id(),
            'konten' => $request->konten,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function destroy(ForumTopik $topik)
    {
        // Only owner or guru of the class or admin can delete
        if (Auth::id() == $topik->user_id || Auth::user()->peran == 'admin' || (Auth::user()->peran == 'guru' && $topik->guruMengajar->guru_id == Auth::id())) {
            $topik->delete();
            return redirect()->route('forum.index', ['jadwal_id' => $topik->guru_mengajar_id])->with('success', 'Topik berhasil dihapus.');
        }

        abort(403);
    }
}
