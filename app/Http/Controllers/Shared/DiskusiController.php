<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Diskusi;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiskusiController extends Controller
{
    /**
     * Store a newly created discussion or reply.
     */
    public function store(Request $request, $pertemuanId)
    {
        $request->validate([
            'pesan' => 'required|string',
            'parent_id' => 'nullable|exists:diskusi,id',
        ]);

        $diskusi = Diskusi::create([
            'pertemuan_id' => $pertemuanId,
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
            'parent_id' => $request->parent_id,
        ]);

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

        // Only owner or guru can delete
        if ($user->id !== $diskusi->user_id && !$user->isGuru() && !$user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $diskusi->delete();

        return response()->json(['success' => true, 'message' => 'Pesan dihapus']);
    }
}
