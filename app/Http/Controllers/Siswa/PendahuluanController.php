<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pendahuluan;
use App\Models\GuruMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendahuluanController extends Controller
{
    /**
     * Tampilkan pendahuluan untuk mata pelajaran tertentu.
     */
    public function show($guruMengajarId)
    {
        $jadwal = GuruMengajar::with(['mataPelajaran.pendahuluan' => function($q) {
            $q->where('aktif', true);
        }, 'guru'])->findOrFail($guruMengajarId);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->kelas()->where('kelas.id', $jadwal->kelas_id)->exists()) {
            abort(403);
        }

        $pendahuluan = $jadwal->mataPelajaran->pendahuluan->first();

        return view('siswa.pendahuluan.show', compact('jadwal', 'pendahuluan'));
    }
}
