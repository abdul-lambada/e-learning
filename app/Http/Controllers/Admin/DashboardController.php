<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_siswa = User::role('siswa')->count();
        $total_guru = User::role('guru')->count();
        $total_kelas = Kelas::count();
        $total_mapel = MataPelajaran::count();
        $absensi_hari_ini = \App\Models\Absensi::whereDate('created_at', now())->count();
        $tugas_aktif = \App\Models\Tugas::where('aktif', true)->where('tanggal_deadline', '>=', now())->count();
        $pertemuan_hari_ini = \App\Models\Pertemuan::whereDate('tanggal_pertemuan', now())->count();
        $logs = \App\Models\AuditLog::with('user')->latest()->take(5)->get();
        $users_terbaru = User::latest()->take(5)->get();

        // Data untuk Chart - Aktivitas Mingguan
        $weeklyData = [];
        $weekLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weekLabels[] = $date->format('D');

            // Hitung aktivitas per hari
            $weeklyData['absensi'][] = \App\Models\Absensi::whereDate('created_at', $date)->count();
            $weeklyData['tugas'][] = \App\Models\PengumpulanTugas::whereDate('created_at', $date)->count();
            $weeklyData['kuis'][] = \App\Models\JawabanKuis::whereDate('created_at', $date)->count();
        }

        // Data untuk Chart - Distribusi User
        $userDistribution = [
            'labels' => ['Guru', 'Siswa'],
            'data' => [$total_guru, $total_siswa]
        ];

        return view('admin.dashboard', compact(
            'total_siswa', 'total_guru', 'total_kelas', 'total_mapel',
            'absensi_hari_ini', 'tugas_aktif', 'pertemuan_hari_ini', 'logs', 'users_terbaru',
            'weeklyData', 'weekLabels', 'userDistribution'
        ));
    }
}

