@extends('layouts.siswa_mobile')

@section('title', 'Leaderboard')

@section('content')
<div class="space-y-6 pb-24">
    <!-- Header -->
    <div class="text-center space-y-2 py-4">
        <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg shadow-yellow-100 mx-auto animate-bounce">
            <i class='bx bxs-trophy text-3xl text-white'></i>
        </div>
        <h2 class="text-2xl font-black text-gray-900 leading-tight">Papan Peringkat</h2>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Kelas {{ $kelas->nama_kelas }}</p>
    </div>

    <!-- My Stats Card -->
    <div class="bg-indigo-600 rounded-[32px] p-6 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500 rounded-full opacity-50"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-xl font-black">
                    #{{ $myRank }}
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Peringkat Kamu</h4>
                    <p class="text-lg font-black">{{ auth()->user()->nama_lengkap }}</p>
                </div>
            </div>
            <div class="text-right">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Total Poin</h4>
                <p class="text-2xl font-black">{{ auth()->user()->poin }} <span class="text-[10px]">XP</span></p>
            </div>
        </div>
    </div>

    <!-- Leaderboard List -->
    <div class="space-y-3">
        @foreach($topKelas as $index => $s)
            @php
                $rank = $index + 1;
                $isTop3 = $rank <= 3;
                $rankColor = $rank == 1 ? 'bg-yellow-400' : ($rank == 2 ? 'bg-slate-300' : ($rank == 3 ? 'bg-orange-400' : 'bg-gray-100'));
                $isMe = $s->id === auth()->id();
            @endphp
            <div class="bg-white rounded-2xl p-4 flex items-center gap-4 border {{ $isMe ? 'border-indigo-200 ring-2 ring-indigo-50' : 'border-gray-50' }} shadow-sm transition-all active:scale-95">
                <!-- Rank Icon/Number -->
                <div class="w-10 h-10 rounded-xl {{ $rankColor }} flex items-center justify-center shrink-0">
                    @if($rank == 1)
                        <i class='bx bxs-medal text-xl text-white'></i>
                    @elseif($rank == 2)
                        <i class='bx bxs-medal text-xl text-white'></i>
                    @elseif($rank == 3)
                        <i class='bx bxs-medal text-xl text-white'></i>
                    @else
                        <span class="text-xs font-black text-gray-500">{{ $rank }}</span>
                    @endif
                </div>

                <!-- Profile -->
                <div class="flex-1 min-w-0 flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($s->nama_lengkap) }}&background=random"
                         class="w-10 h-10 rounded-xl shrink-0" alt="">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 truncate {{ $isMe ? 'text-indigo-600' : '' }}">
                            {{ $s->nama_lengkap }}
                            @if($isMe) <span class="ml-1 text-[8px] bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded-full uppercase">Kamu</span> @endif
                        </h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $s->nis ?? 'SISWA' }}</p>
                    </div>
                </div>

                <!-- Points -->
                <div class="text-right shrink-0">
                    <span class="block text-sm font-black text-gray-800 leading-none">{{ number_format($s->poin) }}</span>
                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">XP</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Footer -->
    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
        <div class="flex gap-3">
            <i class='bx bx-info-circle text-xl text-blue-500'></i>
            <div>
                <h5 class="text-[11px] font-black text-blue-800 uppercase tracking-widest">Cara Dapat Poin?</h5>
                <ul class="text-[10px] text-blue-600 font-bold space-y-1 mt-1">
                    <li>• Absensi Tepat Waktu (+2 Poin)</li>
                    <li>• Kumpulkan Tugas (+10 Poin)</li>
                    <li>• Nilai Sempurna Kuis (+10 Poin Bonus)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
