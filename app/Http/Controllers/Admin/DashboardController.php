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
            'absensi_hari_ini' => \App\Models\Absensi::whereDate('created_at', now())->count(),
            'tugas_aktif' => \App\Models\Tugas::where('aktif', true)->where('tanggal_deadline', '>=', now())->count(),
            'pertemuan_hari_ini' => \App\Models\Pertemuan::whereDate('tanggal_pertemuan', now())->count(),
            'users_terbaru' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
