<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $query = Tugas::with(['pertemuan.guruMengajar.guru', 'pertemuan.guruMengajar.kelas', 'pertemuan.guruMengajar.mataPelajaran']);

        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $tugas = $query->latest()->paginate(10);

        return view('admin.tugas.index', compact('tugas'));
    }

    public function destroy(Tugas $tugas)
    {
        $tugas->delete();
        return back()->with('success', 'Tugas berhasil dihapus oleh admin.');
    }
}
