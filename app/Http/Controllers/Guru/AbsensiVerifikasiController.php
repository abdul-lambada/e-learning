<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\GuruMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiVerifikasiController extends Controller
{
    /**
     * Tampilkan daftar absensi yang perlu verifikasi (Izin/Sakit/Alpha).
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil ID Jadwal yang diajar guru ini
        $jadwalIds = GuruMengajar::where('guru_id', $user->id)->pluck('id');

        $absensi = Absensi::with(['siswa', 'pertemuan.guruMengajar.mataPelajaran'])
            ->whereHas('pertemuan', function($q) use ($jadwalIds) {
                $q->whereIn('guru_mengajar_id', $jadwalIds);
            })
            ->whereIn('status', ['izin', 'sakit', 'alpha'])
            ->where('terverifikasi', false)
            ->latest()
            ->paginate(20);

        return view('guru.absensi.verifikasi', compact('absensi'));
    }

    /**
     * Verifikasi status absensi siswa.
     */
    public function verifikasi(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        // Cek akses: Guru harus pengajar di pertemuan ini
        if ($absensi->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $absensi->update([
            'terverifikasi' => true,
            'diverifikasi_oleh' => Auth::id(),
            'status' => $request->status ?? $absensi->status,
            'keterangan' => $request->catatan ?? $absensi->keterangan
        ]);

        return back()->with('success', 'Absensi berhasil diverifikasi.');
    }
}
