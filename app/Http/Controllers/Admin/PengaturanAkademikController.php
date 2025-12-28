<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanAkademik;
use Illuminate\Http\Request;

class PengaturanAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelola tahun ajaran');
    }

    public function index()
    {
        $akademiks = PengaturanAkademik::orderBy('tahun_ajaran', 'desc')->get();
        return view('admin.pengaturan_akademik.index', compact('akademiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
        ]);

        PengaturanAkademik::create($request->all());

        return back()->with('success', 'Tahun akademik baru berhasil ditambahkan.');
    }

    public function activate(PengaturanAkademik $akademik)
    {
        // Set all to inactive
        PengaturanAkademik::where('is_aktif', true)->update(['is_aktif' => false]);

        // Activate selected
        $akademik->update(['is_aktif' => true]);

        return back()->with('success', 'Semester ' . ucfirst($akademik->semester) . ' ' . $akademik->tahun_ajaran . ' sekarang aktif.');
    }

    public function destroy(PengaturanAkademik $akademik)
    {
        if ($akademik->is_aktif) {
            return back()->with('error', 'Tidak dapat menghapus tahun akademik yang sedang aktif.');
        }

        $akademik->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
