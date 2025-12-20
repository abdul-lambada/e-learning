<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();

        // 1. Statistik Dasar
        $totalKelas = \App\Models\GuruMengajar::where('guru_id', $guruId)->distinct('kelas_id')->count('kelas_id');
        $totalMapel = \App\Models\GuruMengajar::where('guru_id', $guruId)->distinct('mata_pelajaran_id')->count('mata_pelajaran_id'); // Count distinct mapels

        // 2. Tugas Perlu Dinilai
        // Cari tugas milik guru ini, lalu hitung pengumpulan yang belum dinilai
        $tugasPerluDinilai = \App\Models\PengumpulanTugas::whereHas('tugas', function($q) use ($guruId) {
            $q->whereHas('pertemuan', function($q2) use ($guruId) {
                $q2->whereHas('guruMengajar', function($q3) use ($guruId) {
                    $q3->where('guru_id', $guruId);
                });
            });
        })->where('status', '!=', 'dinilai')->count();

        // 3. Jadwal Hari Ini
        $hariIndo = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariIndo[\Carbon\Carbon::now()->format('l')];

        $jadwalHariIni = \App\Models\GuruMengajar::with(['kelas', 'mataPelajaran'])
                            ->where('guru_id', $guruId)
                            ->where('hari', $hariIni)
                            ->orderBy('jam_mulai')
                            ->get();

        // 4. Pertemuan Aktif (Semua jadwal) report
        $totalPertemuan = \App\Models\Pertemuan::whereHas('guruMengajar', function($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        })->count();

        return view('guru.dashboard', compact(
            'totalKelas',
            'totalMapel',
            'tugasPerluDinilai',
            'jadwalHariIni',
            'totalPertemuan'
        ));
    }
}
