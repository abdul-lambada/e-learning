<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelola kelas');
    }

    public function index(Request $request)
    {
        $query = Kelas::with(['waliKelas', 'users']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('kode_kelas', 'like', "%{$search}%")
                  ->orWhere('tahun_ajaran', 'like', "%{$search}%");
        }

        // Filter by Tingkat
        if ($request->has('tingkat') && $request->tingkat != '') {
            $query->where('tingkat', $request->tingkat);
        }

        $kelas = $query->latest()->paginate(10);

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        // Get all users with role 'guru' for Wali Kelas selection
        $gurus = User::role('guru')->where('aktif', true)->get();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kelas' => 'required|string|unique:kelas,kode_kelas',
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|in:10,11,12', // Asumsi SMA/SMK
            'jurusan' => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:9', // Format: 2024/2025
            'wali_kelas_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $validated['aktif'] = true;

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show(Kelas $kelas)
    {
        $kelas->load(['waliKelas', 'users']);
        return view('admin.kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        $gurus = User::role('guru')->where('aktif', true)->get();
        return view('admin.kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'kode_kelas' => 'required|string|unique:kelas,kode_kelas,' . $kelas->id,
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'required|exists:users,id',
            'aktif' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(Kelas $kelas)
    {
        // Check if class has students
        if ($kelas->users()->count() > 0) {
            return redirect()->route('admin.kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa!');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
