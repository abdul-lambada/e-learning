<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $kelas = $user->kelas()->first();

        if (!$kelas) {
            return back()->with('error', 'Anda harus terdaftar di kelas untuk melihat leaderboard.');
        }

        // Leaderboard global (Satu sekolah)
        $topGlobal = User::role('siswa')
            ->orderBy('poin', 'desc')
            ->take(10)
            ->get();

        // Leaderboard kelas
        $topKelas = User::role('siswa')
            ->whereHas('kelas', function($q) use ($kelas) {
                $q->where('kelas.id', $kelas->id);
            })
            ->orderBy('poin', 'desc')
            ->get();

        // Cari ranking saya di kelas
        $myRank = $topKelas->search(function($item) use ($user) {
            return $item->id === $user->id;
        }) + 1;

        return view('siswa.leaderboard.index', compact('topGlobal', 'topKelas', 'kelas', 'myRank'));
    }
}
