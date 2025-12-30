@extends('layouts.siswa_mobile')

@section('title', 'Jadwal Ujian')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Jadwal Ujian</h2>
                <p class="text-xs text-gray-500">Persiapkan dirimu untuk ujian akademik</p>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center">
                <i class='bx bx-time text-2xl'></i>
            </div>
        </div>

        <!-- Filter/Tab (Optional) -->
        <div class="flex gap-2 p-1 bg-gray-100 rounded-2xl">
            <button class="flex-1 py-2 text-[10px] font-bold bg-white text-red-600 rounded-xl shadow-sm uppercase">AKTIF &
                MENDATANG</button>
            <a href="{{ route('siswa.ujian.hasil') }}"
                class="flex-1 py-2 text-center text-[10px] font-bold text-gray-400 uppercase">HASIL UJIAN</a>
        </div>

        <div class="space-y-4">
            @forelse($jadwals as $jadwal)
                @php
                    $now = \Carbon\Carbon::now();
                    $start = \Carbon\Carbon::parse(
                        $jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_mulai->format('H:i'),
                    );
                    $end = \Carbon\Carbon::parse(
                        $jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_selesai->format('H:i'),
                    );
                    $isOpen = $now >= $start && $now <= $end;
                    $isFinished = $now > $end;
                    $isComing = $now < $start;
                @endphp
                <div
                    class="bg-white rounded-[32px] p-5 shadow-sm border border-gray-100 space-y-4 relative overflow-hidden">
                    <!-- Status Strip -->
                    <div
                        class="absolute top-0 left-0 w-1.5 h-full {{ $isOpen ? 'bg-red-500' : ($isComing ? 'bg-blue-400' : 'bg-gray-200') }}">
                    </div>

                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="text-[8px] font-black uppercase tracking-widest bg-red-50 text-red-600 px-2 py-0.5 rounded-lg border border-red-100">{{ $jadwal->ujian->jenis_ujian }}</span>
                                <span
                                    class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ $jadwal->tanggal_ujian->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight">{{ $jadwal->ujian->nama_ujian }}</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                {{ $jadwal->ujian->mataPelajaran->nama_mapel }}</p>
                        </div>
                        @if ($isOpen)
                            <div class="flex items-center gap-1 text-[8px] font-black text-red-500 uppercase">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span> Open
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-3 bg-gray-50/50 rounded-2xl px-4 border border-gray-50/50">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-time text-gray-400 text-lg'></i>
                            <div>
                                <span
                                    class="block text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Waktu</span>
                                <span class="text-[10px] font-bold text-gray-700">{{ $jadwal->jam_mulai->format('H:i') }} -
                                    {{ $jadwal->jam_selesai->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class='bx bx-map-pin text-gray-400 text-lg'></i>
                            <div>
                                <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Lokasi /
                                    Ruang</span>
                                <span
                                    class="text-[10px] font-bold text-gray-700">{{ $jadwal->ruangan ?? 'CBT-Online' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-stopwatch text-indigo-400'></i>
                            <span class="text-[10px] font-bold text-gray-500">{{ $jadwal->ujian->durasi_menit }}
                                Menit</span>
                        </div>

                        @if ($isOpen)
                            <a href="{{ route('siswa.ujian.show', $jadwal->id) }}"
                                class="bg-red-600 text-white px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-100 active:scale-95 transition-all">
                                Masuk Ujian
                            </a>
                        @elseif($isFinished)
                            <button disabled
                                class="bg-gray-100 text-gray-400 px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase cursor-not-allowed">
                                Selesai
                            </button>
                        @else
                            <button disabled
                                class="bg-gray-50 text-gray-400 px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase border border-gray-100">
                                Belum Mulai
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-100">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4 border border-gray-50">
                        <i class='bx bx-calendar-event text-3xl text-gray-200'></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Tidak Ada Ujian</h4>
                    <p class="text-[10px] text-gray-400 max-w-[200px] mx-auto mt-1">Jadwal ujian akan muncul di sini jika
                        sudah dipublikasikan oleh sekolah.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $jadwals->links() }}
        </div>
    </div>
@endsection
