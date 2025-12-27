<?php

namespace App\Http\Controllers;

use App\Models\PerpustakaanMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerpustakaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpustakaanMateri::with('user');

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $materis = $query->latest()->paginate(12);

        $user = Auth::user();
        $layout = 'layouts.' . $user->peran;

        return view('perpustakaan.index', compact('materis', 'layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB
            'url_external' => 'nullable|url',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'kategori', 'url_external']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('perpustakaan', 'public');
        }

        PerpustakaanMateri::create($data);

        return back()->with('success', 'Materi berhasil ditambahkan ke perpustakaan.');
    }

    public function destroy(PerpustakaanMateri $materi)
    {
        // Only owner or admin can delete
        if (Auth::id() == $materi->user_id || Auth::user()->peran == 'admin') {
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $materi->delete();
            return back()->with('success', 'Materi berhasil dihapus.');
        }

        abort(403);
    }
}
