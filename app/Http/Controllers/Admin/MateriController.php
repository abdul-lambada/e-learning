<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $query = MateriPembelajaran::with(['pertemuan.guruMengajar.guru', 'pertemuan.guruMengajar.kelas', 'pertemuan.guruMengajar.mataPelajaran']);

        if ($request->has('search')) {
            $query->where('judul_materi', 'like', '%' . $request->search . '%');
        }

        $materis = $query->latest()->paginate(10);

        return view('admin.materi.index', compact('materis'));
    }

    public function destroy(MateriPembelajaran $materi)
    {
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus oleh admin.');
    }
}
