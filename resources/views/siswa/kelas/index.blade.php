@extends('layouts.siswa_mobile')

@section('title', 'Informasi Kelas')

@section('content')
    <div class="space-y-6 pb-12">
        <!-- Header Section -->
        <div class="bg-indigo-600 rounded-[32px] p-6 text-white shadow-lg shadow-indigo-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10 flex flex-col gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class='bx bx-buildings text-3xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black leading-tight">{{ $kelas->nama_kelas }}</h2>
                    <p class="text-indigo-100 text-xs font-medium">{{ $kelas->tingkat }} • {{ $kelas->jurusan }} • TA
                        {{ $kelas->tahun_ajaran }}</p>
                </div>

                <div class="pt-4 mt-2 border-t border-white/10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 border-white/20 overflow-hidden shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas->waliKelas->nama_lengkap ?? 'WK') }}&color=7F9CF5&background=EBF4FF"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="block text-[8px] font-bold text-indigo-200 uppercase tracking-widest">Wali Kelas</span>
                        <p class="text-[11px] font-bold truncate">
                            {{ $kelas->waliKelas->nama_lengkap ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Toggles -->
        <div class="flex p-1 bg-gray-100 rounded-2xl">
            <button onclick="switchTab('jadwal')" id="tab-jadwal"
                class="flex-1 py-2.5 text-[10px] font-bold bg-white text-indigo-600 rounded-xl shadow-sm transition-all">MATA
                PELAJARAN</button>
            <button onclick="switchTab('teman')" id="tab-teman"
                class="flex-1 py-2.5 text-[10px] font-bold text-gray-400 rounded-xl transition-all">TEMAN SEKELAS</button>
        </div>

        <!-- Schedule/Teachers List -->
        <div id="content-jadwal" class="space-y-4 animate-in fade-in slide-in-from-bottom-2 duration-300">
            @forelse ($kelas->guruMengajar as $jadwal)
                <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-indigo-600 shrink-0">
                        <i class='bx bx-book-open text-2xl'></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 leading-tight truncate">
                            {{ $jadwal->mataPelajaran->nama_mapel }}</h4>
                        <p class="text-[10px] text-gray-400 font-medium truncate">{{ $jadwal->guru->nama_lengkap }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="text-[9px] font-bold px-2 py-0.5 bg-indigo-50 text-indigo-500 rounded-lg">{{ $jadwal->hari }}</span>
                            <span class="text-[9px] font-medium text-gray-400">{{ $jadwal->jam_mulai->format('H:i') }} -
                                {{ $jadwal->jam_selesai->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-3xl border border-dashed border-gray-100">
                    <p class="text-xs text-gray-400">Belum ada mata pelajaran.</p>
                </div>
            @endforelse
        </div>

        <!-- Classmates List -->
        <div id="content-teman" class="hidden grid-cols-2 gap-3 animate-in fade-in slide-in-from-bottom-2 duration-300">
            @forelse ($temanSekelas as $teman)
                <div
                    class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center text-center gap-2">
                    <div class="w-14 h-14 rounded-2xl border-4 border-gray-50 overflow-hidden mb-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($teman->nama_lengkap) }}&color=7F9CF5&background=EBF4FF"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0 w-full px-1">
                        <h4 class="text-[11px] font-bold text-gray-800 leading-tight truncate">{{ $teman->nama_lengkap }}
                        </h4>
                        <p class="text-[9px] text-gray-400 font-medium">{{ $teman->nis }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-10 bg-gray-50 rounded-3xl border border-dashed border-gray-100">
                    <p class="text-xs text-gray-400">Kamu sendirian di kelas ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const tj = document.getElementById('tab-jadwal');
            const tt = document.getElementById('tab-teman');
            const cj = document.getElementById('content-jadwal');
            const ct = document.getElementById('content-teman');

            if (tab === 'jadwal') {
                tj.className =
                    'flex-1 py-2.5 text-[10px] font-bold bg-white text-indigo-600 rounded-xl shadow-sm transition-all';
                tt.className = 'flex-1 py-2.5 text-[10px] font-bold text-gray-400 rounded-xl transition-all';
                cj.classList.remove('hidden');
                ct.classList.add('hidden');
                ct.classList.remove('grid');
            } else {
                tt.className =
                    'flex-1 py-2.5 text-[10px] font-bold bg-white text-indigo-600 rounded-xl shadow-sm transition-all';
                tj.className = 'flex-1 py-2.5 text-[10px] font-bold text-gray-400 rounded-xl transition-all';
                ct.classList.remove('hidden');
                ct.classList.add('grid');
                cj.classList.add('hidden');
            }
        }
    </script>
@endsection
