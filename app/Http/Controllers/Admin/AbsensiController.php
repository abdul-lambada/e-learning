<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $query = Absensi::with(['siswa', 'guruMengajar.mapel', 'guruMengajar.kelas', 'pertemuan']);

        if ($request->filled('kelas_id')) {
            $query->whereHas('guruMengajar', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $absensi = $query->latest()->paginate(20);

        return view('admin.absensi.index', compact('absensi', 'kelas'));
    }
}
