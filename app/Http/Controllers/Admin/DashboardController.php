<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_siswa' => User::role('siswa')->count(),
            'total_guru' => User::role('guru')->count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => MataPelajaran::count(),
        ];

        return view('admin.dashboard', $data);
    }
}
