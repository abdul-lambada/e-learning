<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $siswa */
        $siswa = auth()->user();
        $kelas = $siswa->kelas()->first();

        // 1. Jadwal Hari Ini
        $jadwalHariIni = collect(); // Default empty
        if ($kelas) {
            $hariIndo = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hariIni = $hariIndo[\Carbon\Carbon::now()->format('l')];

            $jadwalHariIni = \App\Models\GuruMengajar::with(['mataPelajaran', 'guru'])
                                ->where('kelas_id', $kelas->id)
                                ->where('hari', $hariIni)
                                ->orderBy('jam_mulai')
                                ->get();
        }

        // 2. Tugas Deadline Terdekat (Belum dikerjakan)
        $tugasPending = collect();
        if ($kelas) {
            $tugasPending = \App\Models\Tugas::whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id);
            })
            ->whereDoesntHave('pengumpulanTugas', function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id); // Filter yang belum dikumpulkan
            })
            ->where('aktif', true)
            ->where('tanggal_deadline', '>=', now()) // Deadline belum lewat
            ->orderBy('tanggal_deadline', 'asc')
            ->take(5)
            ->get();
        }

        // 3. Kuis Aktif
        $kuisAktif = 0;
        if ($kelas) {
             $kuisAktif = \App\Models\Kuis::whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id);
            })
            ->where('aktif', true)
            ->where('tanggal_selesai', '>', now())
            ->where('tanggal_mulai', '<=', now())
            ->count();
        }

        return view('siswa.dashboard', compact('kelas', 'jadwalHariIni', 'tugasPending', 'kuisAktif'));
    }
}
