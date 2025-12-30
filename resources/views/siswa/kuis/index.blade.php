@extends('layouts.siswa_mobile')

@section('title', 'Kuis Saya')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kuis Saya</h2>
                <p class="text-xs text-gray-500">Uji pemahamanmu dengan kuis interaktif</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <i class='bx bx-brain text-2xl'></i>
            </div>
        </div>

        <!-- Filter/Tab (Optional, for now just list) -->
        <div class="flex gap-2 p-1 bg-gray-100 rounded-2xl">
            <button class="flex-1 py-2 text-[10px] font-bold bg-white text-indigo-600 rounded-xl shadow-sm">SEMUA
                KUIS</button>
            <button class="flex-1 py-2 text-[10px] font-bold text-gray-400">RIWAYAT</button>
        </div>

        <div class="space-y-4">
            @forelse($kuisList as $k)
                @php
                    $now = now();
                    $isStarted = $now->greaterThanOrEqualTo($k->tanggal_mulai);
                    $isExpired = $now->greaterThan($k->tanggal_selesai);
                @endphp
                <div
                    class="bg-white rounded-[32px] p-5 shadow-sm border border-gray-100 space-y-4 relative overflow-hidden">
                    <!-- Status Badge Floating -->
                    <div class="absolute top-4 right-4">
                        @if ($isExpired)
                            <span
                                class="text-[8px] font-black uppercase tracking-widest bg-gray-100 text-gray-400 px-2 py-1 rounded-lg">Berakhir</span>
                        @elseif(!$isStarted)
                            <span
                                class="text-[8px] font-black uppercase tracking-widest bg-blue-50 text-blue-500 px-2 py-1 rounded-lg">Belum
                                Buka</span>
                        @else
                            <span
                                class="text-[8px] font-black uppercase tracking-widest bg-green-50 text-green-500 px-2 py-1 rounded-lg animate-pulse">Aktif</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <i class='bx bx-notepad text-xl'></i>
                        </div>
                        <div class="min-w-0 pr-12">
                            <span
                                class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest">{{ $k->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</span>
                            <h3 class="font-bold text-gray-800 text-sm truncate leading-tight">
                                {{ $k->judul_kuis ?? $k->nama_kuis }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-3 border-y border-gray-50">
                        <div>
                            <span
                                class="block text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Deadline</span>
                            <span
                                class="text-[10px] font-bold text-gray-700">{{ $k->tanggal_selesai->format('d M, H:i') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Skor
                                Terakhir</span>
                            <span
                                class="text-[10px] font-bold {{ $k->skor_terakhir >= ($k->nilai_minimal_lulus ?? 70) ? 'text-green-600' : 'text-gray-700' }}">
                                {{ $k->skor_terakhir !== null ? number_format($k->skor_terakhir, 1) : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-1">
                        @if ($k->pernah_mengerjakan)
                            <div class="flex -space-x-2">
                                <span
                                    class="w-6 h-6 rounded-full bg-green-100 border-2 border-white flex items-center justify-center text-green-600">
                                    <i class='bx bx-check text-xs'></i>
                                </span>
                                <span class="text-[9px] font-bold text-green-500 pl-3 pt-1">Sudah Dikerjakan</span>
                            </div>
                        @else
                            <span class="text-[9px] font-bold text-gray-400 italic">Belum dikerjakan</span>
                        @endif

                        <a href="{{ route('siswa.kuis.show', $k->id) }}"
                            class="bg-indigo-600 text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                            Buka Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100 px-8">
                    <i class='bx bx-folder-open text-5xl text-gray-200 mb-2'></i>
                    <p class="text-xs text-gray-400 font-medium">Belum ada kuis yang tersedia untukmu.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $kuisList->links() }}
        </div>
    </div>
@endsection
