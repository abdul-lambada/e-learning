@extends('layouts.siswa_mobile')

@section('title', $kuis->judul_kuis)

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Back Button & Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.kuis.index') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-gray-900 leading-tight truncate">{{ $kuis->judul_kuis }}</h2>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest leading-none">Detail Assessment</p>
            </div>
        </div>

        <!-- Info Grid Cards -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-indigo-50 p-4 rounded-3xl space-y-1">
                <i class='bx bx-time-five text-indigo-600 text-xl'></i>
                <div class="block">
                    <span class="text-[8px] font-bold text-indigo-400 uppercase tracking-tighter">Durasi</span>
                    <p class="text-sm font-black text-indigo-900 leading-tight">{{ $kuis->durasi_menit }} Menit</p>
                </div>
            </div>
            <div class="bg-blue-50 p-4 rounded-3xl space-y-1">
                <i class='bx bx-list-check text-blue-600 text-xl'></i>
                <div class="block">
                    <span class="text-[8px] font-bold text-blue-400 uppercase tracking-tighter">Jumlah Soal</span>
                    <p class="text-sm font-black text-blue-900 leading-tight">{{ $kuis->soalKuis->count() }} Butir</p>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-3xl space-y-1">
                <i class='bx bx-award text-green-600 text-xl'></i>
                <div class="block">
                    <span class="text-[8px] font-bold text-green-400 uppercase tracking-tighter">Kriteria Lulus</span>
                    <p class="text-sm font-black text-green-900 leading-tight">Skor {{ $kuis->nilai_minimal_lulus }}</p>
                </div>
            </div>
            <div class="bg-orange-50 p-4 rounded-3xl space-y-1">
                <i class='bx bx-refresh text-orange-600 text-xl'></i>
                <div class="block">
                    <span class="text-[8px] font-bold text-orange-400 uppercase tracking-tighter">Percobaan</span>
                    <p class="text-sm font-black text-orange-900 leading-tight">{{ $sisaPercobaan }} /
                        {{ $kuis->max_percobaan }} Sisa</p>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 space-y-4">
            <h3 class="font-bold text-gray-900">Deskripsi & Petunjuk</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                {{ $kuis->deskripsi ?? 'Persiapkan dirimu untuk mengerjakan kuis ini dengan jujur dan teliti.' }}
            </p>

            @if ($kuis->instruksi)
                <div class="bg-yellow-50 p-4 rounded-2xl border border-yellow-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i class='bx bx-info-circle text-yellow-600'></i>
                        <span class="text-[10px] font-bold text-yellow-700 uppercase tracking-widest">Instruksi
                            Penting</span>
                    </div>
                    <p class="text-[11px] text-yellow-800 leading-relaxed font-medium">
                        {!! nl2br(e($kuis->instruksi)) !!}
                    </p>
                </div>
            @endif
        </div>

        <!-- Attempt History -->
        <div class="space-y-4">
            <h3 class="font-bold text-gray-900 px-1">Riwayat Pengerjaan</h3>
            <div class="space-y-3">
                @forelse($riwayat as $history)
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-400">
                                #{{ $history->percobaan_ke }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Status</span>
                                <span
                                    class="text-xs font-bold {{ $history->status == 'selesai' ? 'text-green-600' : 'text-orange-500' }}">
                                    {{ ucfirst($history->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter block">Skor</span>
                            <span
                                class="text-lg font-black {{ ($history->nilai ?? 0) >= $kuis->nilai_minimal_lulus ? 'text-green-600' : 'text-gray-700' }}">
                                {{ $history->status == 'selesai' ? number_format($history->nilai, 1) : '-' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-2xl p-8 text-center border border-dashed border-gray-200">
                        <p class="text-xs text-gray-400 font-medium italic">Belum ada riwayat percobaan kuis ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Fixed Action Button at Bottom -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-md border-t border-gray-100 z-40">
            @if ($sedangMengerjakan)
                <a href="{{ route('siswa.kuis.kerjakan', $sedangMengerjakan->id) }}"
                    class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-orange-100 active:scale-95 transition-all">
                    <i class='bx bx-play-circle text-xl'></i>
                    Lanjutkan Mengerjakan
                </a>
            @elseif($sisaPercobaan > 0)
                <form action="{{ route('siswa.kuis.start', $kuis->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Mulai kuis sekarang? Waktu akan dihitung saat Anda menekan mulai.')"
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                        <i class='bx bx-play text-xl'></i>
                        Mulai Assessment
                    </button>
                </form>
            @else
                <button disabled
                    class="w-full bg-gray-200 text-gray-400 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 cursor-not-allowed">
                    <i class='bx bx-lock-alt text-xl'></i>
                    Kesempatan Habis
                </button>
            @endif
        </div>
    </div>
@endsection
