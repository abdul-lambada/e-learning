<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Diskusi;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiskusiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lihat diskusi')->only(['index']);
        $this->middleware('permission:tambah diskusi')->only(['store']);
    }

    /**
     * Store a newly created discussion or reply.
     */
    public function store(Request $request, $pertemuanId)
    {
        $request->validate([
            'pesan' => 'required|string',
            'parent_id' => 'nullable|exists:diskusi,id',
        ]);

        if (!$this->checkAccess($pertemuanId)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $diskusi = Diskusi::create([
            'pertemuan_id' => $pertemuanId,
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
            'parent_id' => $request->parent_id,
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isSiswa()) {
            $user->awardPoints(1, "Berpartisipasi dalam diskusi");

            // Cek Badge: Pahlawan Diskusi (5 partisipasi)
            $diskusiCount = Diskusi::where('user_id', $user->id)->count();
            if ($diskusiCount >= 5) {
                $user->awardBadge('pahlawan-diskusi');
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => $diskusi->load('user')
            ]);
        }

        return back()->with('success', 'Pesan berhasil dikirim');
    }

    /**
     * Get discussion list for a meeting (AJAX).
     */
    public function index($pertemuanId)
    {
        if (!$this->checkAccess($pertemuanId)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $diskusi = Diskusi::with(['user', 'replies.user'])
            ->where('pertemuan_id', $pertemuanId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return response()->json($diskusi);
    }

    /**
     * Remove a discussion entry.
     */
    public function destroy(Diskusi $diskusi)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user has access to the meeting first!
        if (!$this->checkAccess($diskusi->pertemuan_id)) {
             return response()->json(['success' => false, 'message' => 'Unauthorized Access'], 403);
        }

        // Only owner or guru can delete
        if ($user->id !== $diskusi->user_id && !$user->isGuru() && !$user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $diskusi->delete();

        return response()->json(['success' => true, 'message' => 'Pesan dihapus']);
    }

    private function checkAccess($pertemuanId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) return false;

        if ($user->isAdmin()) return true;

        $pertemuan = Pertemuan::with('guruMengajar')->find($pertemuanId);
        if (!$pertemuan) return false;

        if ($user->isGuru()) {
            // Check if guru owns this schedule
            return $pertemuan->guruMengajar->guru_id === $user->id;
        }

        if ($user->isSiswa()) {
            // Check if siswa is in the class
            return $user->kelas()->where('kelas.id', $pertemuan->guruMengajar->kelas_id)->exists();
        }

        return false;
    }
}
