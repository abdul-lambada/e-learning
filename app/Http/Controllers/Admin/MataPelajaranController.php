<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_mapel', 'like', "%{$search}%")
                  ->orWhere('kode_mapel', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('aktif', $request->status == 'aktif' ? true : false);
        }

        $mapel = $query->latest()->paginate(10);

        return view('admin.mata-pelajaran.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'durasi_pertemuan' => 'required|integer|min:1', // Dalam menit
            'gambar_cover' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $validated['aktif'] = true;

        if ($request->hasFile('gambar_cover')) {
            $path = $request->file('gambar_cover')->store('covers', 'public');
            $validated['gambar_cover'] = $path;
        }

        MataPelajaran::create($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function show(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata-pelajaran.show', compact('mataPelajaran'));
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|unique:mata_pelajaran,kode_mapel,' . $mataPelajaran->id,
            'nama_mapel' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'durasi_pertemuan' => 'required|integer|min:1',
            'gambar_cover' => 'nullable|image|max:2048',
            'aktif' => 'required|boolean',
        ]);

        if ($request->hasFile('gambar_cover')) {
            // Delete old cover if exists
            if ($mataPelajaran->gambar_cover) {
                Storage::disk('public')->delete($mataPelajaran->gambar_cover);
            }
            $path = $request->file('gambar_cover')->store('covers', 'public');
            $validated['gambar_cover'] = $path;
        }

        $mataPelajaran->update($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil diupdate!');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        // Check relationships
        if ($mataPelajaran->guruMengajar()->exists()) {
            return redirect()->route('admin.mata-pelajaran.index')
                ->with('error', 'Tidak dapat menghapus mapel yang sedang diajarkan oleh guru!');
        }

        if ($mataPelajaran->gambar_cover) {
            Storage::disk('public')->delete($mataPelajaran->gambar_cover);
        }

        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus!');
    }
}
