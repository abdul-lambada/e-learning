<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\PengaturanAplikasi;
use App\Models\PengaturanAkademik;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $siswas = [];

        if ($request->filled('kelas_id')) {
            $siswas = User::role('siswa')
                ->whereHas('kelas', function($q) use ($request) {
                    $q->where('kelas.id', $request->kelas_id);
                })
                ->with(['pengumpulanTugas', 'jawabanKuis', 'jawabanUjian'])
                ->paginate(20);
        }

        return view('admin.nilai.index', compact('kelas', 'siswas'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $siswas = User::role('siswa')
            ->whereHas('kelas', function($q) use ($request) {
                $q->where('kelas.id', $request->kelas_id);
            })
            ->with(['pengumpulanTugas', 'jawabanKuis', 'jawabanUjian'])
            ->get();

        $settings = PengaturanAplikasi::getSettings();
        $akademik = PengaturanAkademik::active();

        $pdf = Pdf::loadView('admin.nilai.cetak', [
            'kelas' => $kelas,
            'siswas' => $siswas,
            'settings' => $settings,
            'akademik' => $akademik,
            'tanggal' => now()->format('d F Y')
        ]);

        return $pdf->stream('Rekap_Nilai_' . $kelas->nama_kelas . '.pdf');
    }
}
