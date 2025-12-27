<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $siswas = [];

        if ($request->filled('kelas_id')) {
            $siswas = User::role('siswa')
                ->whereHas('kelas', function($q) use ($request) {
                    $q->where('kelas.id', $request->kelas_id);
                })
                ->with(['pengumpulanTugas', 'jawabanKuis', 'jawabanUjian'])
                ->paginate(20);
        }

        return view('admin.nilai.index', compact('kelas', 'siswas'));
    }
}
