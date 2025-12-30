@extends('layouts.siswa_mobile')

@section('title', 'Nilai Saya')

@section('content')
    <div class="space-y-6 pb-8">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Laporan Nilai</h2>
                <p class="text-xs text-gray-500">Lihat pencapaian belajarmu di sini</p>
            </div>
            @if ($nilai->isNotEmpty())
                <a href="{{ route('siswa.nilai.cetak') }}" target="_blank"
                    class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                    <i class='bx bx-printer text-2xl'></i>
                </a>
            @endif
        </div>

        <!-- Grade Summary Cards -->
        <div class="space-y-4">
            @forelse($nilai as $n)
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 pr-4">
                            <span
                                class="text-[10px] uppercase tracking-wider font-bold text-indigo-500">{{ $n->kelas->nama_kelas }}</span>
                            <h3 class="font-bold text-gray-900 text-lg leading-tight mt-0.5">
                                {{ $n->mataPelajaran->nama_mapel }}</h3>
                        </div>
                        <div class="bg-indigo-50 rounded-2xl p-3 text-center min-w-[65px]">
                            <span class="block text-[8px] text-indigo-400 font-bold uppercase tracking-widest">Akhir</span>
                            <span
                                class="text-xl font-black text-indigo-600 leading-none">{{ number_format($n->nilai_akhir, 1) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1.5 bg-gray-50 rounded-xl text-xs font-bold text-gray-600 border border-gray-100">Predikat
                            {{ $n->getPredikat() }}</span>
                        <span
                            class="px-3 py-1.5 {{ $n->lulus ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' }} rounded-xl text-xs font-bold border">
                            {{ $n->lulus ? 'Lulus' : 'Tidak Lulus' }}
                        </span>
                    </div>

                    <!-- Components Breakdown (Inline on Mobile instead of Modal) -->
                    <div class="pt-4 border-t border-gray-50 grid grid-cols-2 gap-y-3">
                        <div>
                            <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest">Absensi
                                ({{ number_format($n->bobot_absensi, 0) }}%)</span>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($n->nilai_absensi, 1) }}</span>
                        </div>
                        <div>
                            <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tugas
                                ({{ number_format($n->bobot_tugas, 0) }}%)</span>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($n->nilai_tugas, 1) }}</span>
                        </div>
                        <div>
                            <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest">Kuis
                                ({{ number_format($n->bobot_kuis, 0) }}%)</span>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($n->nilai_kuis, 1) }}</span>
                        </div>
                        <div>
                            <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest">Ujian
                                ({{ number_format($n->bobot_ujian, 0) }}%)</span>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($n->nilai_ujian, 1) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 py-16 px-10 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4">
                        <i class='bx bx-book-content text-4xl text-gray-200'></i>
                    </div>
                    <h4 class="font-bold text-gray-800 italic">Belum Ada Nilai</h4>
                    <p class="text-gray-400 text-xs mt-1">Nilai kamu belum dipublikasikan oleh guru pengampu.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
