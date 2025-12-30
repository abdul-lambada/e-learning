@extends('layouts.siswa_mobile')

@section('title', 'Riwayat Absensi')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Back Button & Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.absensi.index') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Riwayat Kehadiran</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Statistik & Log Absen
                </p>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-2 -top-2 w-12 h-12 bg-green-50 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Hadir</span>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-3xl font-black text-green-600 leading-none">{{ $statistik['hadir'] }}</span>
                    <span class="text-[10px] text-green-400 font-bold pb-0.5">Sesi</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-2 -top-2 w-12 h-12 bg-blue-50 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Izin</span>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-3xl font-black text-blue-500 leading-none">{{ $statistik['izin'] }}</span>
                    <span class="text-[10px] text-blue-400 font-bold pb-0.5">Sesi</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-2 -top-2 w-12 h-12 bg-orange-50 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Sakit</span>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-3xl font-black text-orange-500 leading-none">{{ $statistik['sakit'] }}</span>
                    <span class="text-[10px] text-orange-400 font-bold pb-0.5">Sesi</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-2 -top-2 w-12 h-12 bg-red-50 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Alpha</span>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-3xl font-black text-red-500 leading-none">{{ $statistik['alpha'] }}</span>
                    <span class="text-[10px] text-red-400 font-bold pb-0.5">Sesi</span>
                </div>
            </div>
        </div>

        <!-- Log Attendance -->
        <div class="space-y-4">
            <h3 class="font-bold text-gray-800 text-lg px-1">Log Terbaru</h3>

            @forelse ($absensi as $log)
                <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                    <!-- Icon based on status -->
                    <div
                        class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0
                        @if ($log->status == 'hadir') bg-green-100 text-green-600 @elseif($log->status == 'alpha') bg-red-100 text-red-600 @else bg-blue-100 text-blue-600 @endif">
                        <i
                            class='bx @if ($log->status == 'hadir') bx-check-double @elseif($log->status == 'alpha') bx-x @else bx-info-circle @endif text-2xl'></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-900 text-sm truncate leading-tight">
                                {{ $log->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</h4>
                            <span
                                class="text-[10px] font-bold text-gray-400 whitespace-nowrap ml-2">{{ \Carbon\Carbon::parse($log->waktu_absen)->format('H:i') }}</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-medium mt-0.5">
                            {{ \Carbon\Carbon::parse($log->pertemuan->tanggal_pertemuan)->format('d M Y') }}</p>

                        @if ($log->keterangan)
                            <div class="mt-2 bg-gray-50 p-2 rounded-xl border border-gray-100">
                                <p class="text-[9px] text-gray-400 italic font-medium leading-tight line-clamp-1">
                                    "{{ $log->keterangan }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-400 text-sm italic">Belum ada riwayat absensi.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="py-4">
            {{ $absensi->links() }}
        </div>
    </div>
@endsection
