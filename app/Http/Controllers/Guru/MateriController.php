<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kelola materi')->except(['show', 'download', 'index']);
        // Jika ada method show/index yg public, bisa ditambahkan permission:lihat materi
    }

    public function create(Request $request)
    {
        $pertemuanId = $request->query('pertemuan_id');
        $pertemuan = Pertemuan::with('guruMengajar')->findOrFail($pertemuanId);

        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.materi.create', compact('pertemuan'));
    }

    public function store(Request $request)
    {
        $pertemuan = Pertemuan::with('guruMengajar')->findOrFail($request->pertemuan_id);

        if ($pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'judul_materi' => 'required|string|max:255',
            'tipe_materi' => 'required|in:file,video,teks,link',
            'deskripsi' => 'nullable|string',
            'konten' => 'nullable|required_if:tipe_materi,teks',
            'video_url' => 'nullable|url|required_if:tipe_materi,video',
            'link_url' => 'nullable|url|required_if:tipe_materi,link',
            'file_materi' => 'nullable|file|max:10240|required_if:tipe_materi,file', // Max 10MB
            'dapat_diunduh' => 'nullable|boolean',
        ]);

        $validated['aktif'] = true;
        // Checkbox handling
        $validated['dapat_diunduh'] = $request->has('dapat_diunduh');

        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $path = $file->store('materi', 'public'); // Store in storage/app/public/materi

            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
        }

        MateriPembelajaran::create($validated);

        return redirect()->route('guru.pertemuan.show', $pertemuan->id)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function edit(MateriPembelajaran $materi)
    {
        $materi->load('pertemuan.guruMengajar');
        if ($materi->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        return view('guru.materi.edit', compact('materi'));
    }

    public function update(Request $request, MateriPembelajaran $materi)
    {
        $materi->load('pertemuan.guruMengajar');
        if ($materi->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'tipe_materi' => 'required|in:file,video,teks,link',
            'deskripsi' => 'nullable|string',
            'konten' => 'nullable|required_if:tipe_materi,teks',
            'video_url' => 'nullable|url|required_if:tipe_materi,video',
            'link_url' => 'nullable|url|required_if:tipe_materi,link',
            'file_materi' => 'nullable|file|max:10240',
            'dapat_diunduh' => 'nullable|boolean',
            'aktif' => 'required|boolean',
        ]);

        $validated['dapat_diunduh'] = $request->has('dapat_diunduh');

        if ($request->hasFile('file_materi')) {
            // Hapus file lama
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file_materi');
            $path = $file->store('materi', 'public');

            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
        }

        $materi->update($validated);

        return redirect()->route('guru.pertemuan.show', $materi->pertemuan_id)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(MateriPembelajaran $materi)
    {
        $materi->load('pertemuan.guruMengajar');
        if ($materi->pertemuan->guruMengajar->guru_id !== Auth::id()) {
            abort(403);
        }

        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $pertemuanId = $materi->pertemuan_id;
        $materi->delete();

        return redirect()->route('guru.pertemuan.show', $pertemuanId)
            ->with('success', 'Materi berhasil dihapus!');
    }
}
