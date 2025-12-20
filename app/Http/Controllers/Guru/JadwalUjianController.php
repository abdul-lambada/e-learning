<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\JadwalUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalUjianController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelola jadwal ujian');
    }

    private function checkAccess(Ujian $ujian)
    {
        // Akses cek apakah guru mengajar mapel & kelas ujian ini?
        // Atau jika 'kelola jadwal ujian' boleh siapa saja (Admin/Kurikulum)?
        // Asumsi Guru Mapel boleh menjadwalkan sendiri.
        $isTeaching = GuruMengajar::where('guru_id', Auth::id())
                        ->where('mata_pelajaran_id', $ujian->mata_pelajaran_id)
                        ->where('kelas_id', $ujian->kelas_id)
                        ->exists();
        if (!$isTeaching) abort(403);
    }

    public function create(Ujian $ujian)
    {
        $this->checkAccess($ujian);
        $gurus = \App\Models\User::role('guru')->orderBy('nama_lengkap')->get();
        return view('guru.ujian.jadwal.create', compact('ujian', 'gurus'));
    }

    public function store(Request $request, Ujian $ujian)
    {
        $this->checkAccess($ujian);

        $request->validate([
            'tanggal_ujian' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'required|string',
            'pengawas_id' => 'nullable|exists:users,id',
            'catatan' => 'nullable|string'
        ]);

        JadwalUjian::create([
            'ujian_id' => $ujian->id,
            'tanggal_ujian' => $request->tanggal_ujian,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'pengawas_id' => $request->pengawas_id ?? Auth::id(), // Default diri sendiri jika null
            'catatan' => $request->catatan,
            'status' => 'dijadwalkan',
        ]);

        return redirect()->route('guru.ujian.show', $ujian->id)
                         ->with('success', 'Jadwal ujian berhasil ditambahkan.');
    }

    public function destroy(Ujian $ujian, JadwalUjian $jadwal)
    {
        $this->checkAccess($ujian);
        if ($jadwal->ujian_id !== $ujian->id) abort(404);

        $jadwal->delete();
        return redirect()->route('guru.ujian.show', $ujian->id)->with('success', 'Jadwal dihapus.');
    }
}
