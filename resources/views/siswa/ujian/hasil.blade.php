@extends('layouts.siswa_mobile')

@section('title', 'Hasil Ujian')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.ujian.index') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Hasil Ujian</h2>
                <p class="text-[10px] text-red-600 font-bold uppercase tracking-widest leading-none">Riwayat Pencapaian</p>
            </div>
        </div>

        @if ($riwayatUjian->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-100">
                <div
                    class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4 border border-gray-50">
                    <i class='bx bx-file-blank text-3xl text-gray-200'></i>
                </div>
                <h4 class="font-bold text-gray-800">Belum Ada Data</h4>
                <p class="text-[10px] text-gray-400 max-w-[200px] mx-auto mt-1">Selesaikan ujian untuk melihat riwayat nilai
                    kamu di sini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($riwayatUjian as $hasil)
                    @php
                        $ujian = $hasil->jadwalUjian->ujian;
                        $lulus = $hasil->nilai >= $ujian->nilai_minimal_lulus;
                    @endphp
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0 pr-12">
                                <span
                                    class="text-[9px] font-bold text-red-500 uppercase tracking-widest">{{ $ujian->jenis_ujian }}</span>
                                <h3 class="font-bold text-gray-900 text-sm leading-tight truncate mt-0.5">
                                    {{ $ujian->nama_ujian }}</h3>
                                <p class="text-[10px] text-gray-400 font-medium truncate">
                                    {{ $ujian->mataPelajaran->nama_mapel }}</p>
                            </div>
                            <div class="text-right">
                                <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest">Skor</span>
                                <span
                                    class="text-xl font-black {{ $lulus ? 'text-green-600' : 'text-red-500' }}">{{ $hasil->nilai }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3 border-y border-gray-50">
                            <div class="flex items-center gap-2">
                                <i class='bx bx-calendar text-gray-400'></i>
                                <span
                                    class="text-[10px] font-bold text-gray-500">{{ \Carbon\Carbon::parse($hasil->waktu_selesai)->format('d M Y, H:i') }}</span>
                            </div>
                            @if ($lulus)
                                <span
                                    class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[8px] font-black uppercase border border-green-100">LULUS</span>
                            @else
                                <span
                                    class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[8px] font-black uppercase border border-red-100">GAGAL</span>
                            @endif
                        </div>

                        <div class="flex justify-center">
                            <button
                                class="text-[10px] font-bold text-indigo-500 flex items-center gap-1 active:scale-95 transition-transform">
                                <i class='bx bx-file-find'></i> Lihat Detail Jawaban
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $riwayatUjian->links() }}
            </div>
        @endif
    </div>
@endsection
