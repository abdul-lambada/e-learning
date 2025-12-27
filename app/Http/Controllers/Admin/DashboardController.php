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
        $total_siswa = User::role('siswa')->count();
        $total_guru = User::role('guru')->count();
        $total_kelas = Kelas::count();
        $total_mapel = MataPelajaran::count();
        $logs = \App\Models\AuditLog::with('user')->latest()->take(5)->get();
        $recent_users = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'total_siswa', 'total_guru', 'total_kelas', 'total_mapel', 'logs', 'recent_users'
        ));
    }
}
