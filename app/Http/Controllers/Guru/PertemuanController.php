<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMengajar;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PertemuanController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $jadwal = GuruMengajar::findOrFail($jadwalId);

        // Security Check
        if ($jadwal->guru_id !== Auth::id()) {
            abort(403);
        }

        // Auto calculate pertemuan ke-n
        $pertemuanKe = Pertemuan::where('guru_mengajar_id', $jadwalId)->max('pertemuan_ke') + 1;

        return view('guru.pertemuan.create', compact('jadwal', 'pertemuanKe'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jadwal = GuruMengajar::findOrFail($request->guru_mengajar_id);

        if ($jadwal->guru_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'guru_mengajar_id' => 'required|exists:guru_mengajar,id',
            'pertemuan_ke' => 'required|integer',
            'judul_pertemuan' => 'required|string|max:255',
            'tanggal_pertemuan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:dijadwalkan,berlangsung,selesai',
        ]);

        $validated['aktif'] = true;

        Pertemuan::create($validated);

        return redirect()->route('guru.jadwal.show', $jadwal->id)
            ->with('success', 'Pertemuan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pertemuan $pertemuan)
    {
        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $pertemuan->load(['guruMengajar.kelas', 'guruMengajar.mataPelajaran', 'materiPembelajaran']);

        return view('guru.pertemuan.show', compact('pertemuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pertemuan $pertemuan)
    {
        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.pertemuan.edit', compact('pertemuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pertemuan $pertemuan)
    {
        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul_pertemuan' => 'required|string|max:255',
            'tanggal_pertemuan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:dijadwalkan,berlangsung,selesai',
            'aktif' => 'required|boolean',
        ]);

        $pertemuan->update($validated);

        return redirect()->route('guru.jadwal.show', $pertemuan->guru_mengajar_id)
            ->with('success', 'Pertemuan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pertemuan $pertemuan)
    {
        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $jadwalId = $pertemuan->guru_mengajar_id;
        $pertemuan->delete();

        return redirect()->route('guru.jadwal.show', $jadwalId)
            ->with('success', 'Pertemuan berhasil dihapus!');
    }
}
