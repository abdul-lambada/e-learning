<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\Ujian;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function kuis(Request $request)
    {
        $query = Kuis::with(['guruMengajar.guru', 'guruMengajar.kelas', 'guruMengajar.mapel']);
        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        $kuis = $query->latest()->paginate(10);
        return view('admin.evaluasi.kuis', compact('kuis'));
    }

    public function ujian(Request $request)
    {
        $query = Ujian::with(['guruMengajar.guru', 'guruMengajar.kelas', 'guruMengajar.mapel']);
        if ($request->has('search')) {
            $query->where('nama_ujian', 'like', '%' . $request->search . '%');
        }
        $ujian = $query->latest()->paginate(10);
        return view('admin.evaluasi.ujian', compact('ujian'));
    }
}
