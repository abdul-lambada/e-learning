<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user();

        $data = [
            'total_kelas' => $guru->guruMengajar()->distinct('kelas_id')->count(),
            'total_mapel' => $guru->guruMengajar()->distinct('mata_pelajaran_id')->count(),
        ];

        return view('guru.dashboard', $data);
    }
}
