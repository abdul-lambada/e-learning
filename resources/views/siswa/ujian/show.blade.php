@extends('layouts.siswa_mobile')

@section('title', 'Informasi Ujian')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Back Button & Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.ujian.index') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-gray-900 leading-tight truncate">Detail Ujian</h2>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest leading-none">
                    {{ $jadwal->ujian->jenis_ujian }}</p>
            </div>
        </div>

        <!-- Ujian Hero Card -->
        <div class="bg-indigo-600 rounded-[32px] p-8 text-white shadow-lg shadow-indigo-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10 space-y-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class='bx bx-edit text-3xl'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black leading-tight">{{ $jadwal->ujian->nama_ujian }}</h1>
                    <p class="text-indigo-100 text-xs font-medium mt-1">{{ $jadwal->ujian->mataPelajaran->nama_mapel }}</p>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Durasi</span>
                <p class="text-sm font-black text-gray-900">{{ $jadwal->ujian->durasi_menit }} Menit</p>
            </div>
            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Jumlah Soal</span>
                <p class="text-sm font-black text-gray-900">{{ $jadwal->ujian->jumlah_soal }} Butir</p>
            </div>
            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Mulai</span>
                <p class="text-[11px] font-bold text-gray-900 leading-tight">{{ $jadwal->jam_mulai->format('H:i') }} WIB</p>
            </div>
            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Selesai</span>
                <p class="text-[11px] font-bold text-gray-900 leading-tight">{{ $jadwal->jam_selesai->format('H:i') }} WIB
                </p>
            </div>
        </div>

        <!-- Instructions Section -->
        @if ($jadwal->ujian->instruksi)
            <div class="bg-orange-50 rounded-3xl p-6 border border-orange-100 space-y-3">
                <div class="flex items-center gap-2">
                    <i class='bx bx-error-circle text-orange-500 text-lg'></i>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-orange-600">Instruksi Pengerjaan</h3>
                </div>
                <p class="text-xs text-orange-800 leading-relaxed font-medium">
                    {{ $jadwal->ujian->instruksi }}
                </p>
            </div>
        @endif

        @php
            $now = \Carbon\Carbon::now();
            $start = \Carbon\Carbon::parse(
                $jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_mulai->format('H:i'),
            );
            $isStarted = $now >= $start;
        @endphp

        <!-- Action Section -->
        <div class="fixed bottom-0 left-0 right-0 p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-40">
            @if ($sedangMengerjakan)
                <a href="{{ route('siswa.ujian.kerjakan', $sedangMengerjakan->id) }}"
                    class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-center block shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                    LANJUTKAN UJIAN
                </a>
            @else
                @if ($riwayat->count() > 0 && ($jadwal->ujian->max_percobaan > 0 && $riwayat->count() >= $jadwal->ujian->max_percobaan))
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-3">Selesai Dikerjakan
                        </p>
                        <a href="{{ route('siswa.ujian.index') }}"
                            class="w-full bg-gray-100 text-gray-400 py-4 rounded-2xl font-black text-center block">
                            KEMBALI
                        </a>
                    </div>
                @else
                    @if ($isStarted)
                        <form action="{{ route('siswa.ujian.start', $jadwal->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Mulai ujian sekarang? Waktu pengerjaan akan segera berjalan.')"
                                class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-center shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                                MULAI KERJAKAN
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-100 text-gray-400 py-4 rounded-2xl font-black text-center">
                            BELUM DIMULAI
                        </button>
                    @endif
                @endif
            @endif
        </div>
    </div>
@endsection
