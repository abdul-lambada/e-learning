<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\Tugas;
use App\Models\Kuis;
use App\Models\JadwalUjian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $kelas = $user->kelas()->first();

        // 1. Get Schedule (Fixed days)
        $schedules = collect();
        if ($kelas) {
            $schedules = GuruMengajar::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran', \App\Models\PengaturanAkademik::active()?->tahun_ajaran)
                ->get();
        }

        // 2. Get Deadlines
        $tugas = Tugas::whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                if($kelas) $q->where('kelas_id', $kelas->id);
            })->where('aktif', true)->get();

        $kuis = Kuis::whereHas('pertemuan.guruMengajar', function($q) use ($kelas) {
                if($kelas) $q->where('kelas_id', $kelas->id);
            })->where('aktif', true)->get();

        // Standard event format for UI
        $events = [];

        // Add Tugas to events
        foreach ($tugas as $t) {
            $events[] = [
                'title' => 'Tugas: ' . $t->judul,
                'date' => Carbon::parse($t->tanggal_deadline)->toDateString(),
                'type' => 'tugas',
                'color' => 'orange',
            ];
        }

        // Add Kuis to events
        foreach ($kuis as $k) {
            $events[] = [
                'title' => 'Kuis: ' . ($k->judul_kuis ?? $k->nama_kuis),
                'date' => Carbon::parse($k->tanggal_selesai)->toDateString(),
                'type' => 'kuis',
                'color' => 'blue',
            ];
        }

        return view('siswa.calendar.index', compact('events', 'schedules'));
    }
}
