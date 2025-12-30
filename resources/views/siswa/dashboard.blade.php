@extends('layouts.siswa_mobile')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Card -->
    <div
        class="bg-linear-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-1">Halo, {{ explode(' ', auth()->user()->nama_lengkap)[0] }}!</h2>
            <p class="text-indigo-100 text-sm mb-4">
                @if ($kelas)
                    Kamu di kelas <span class="font-bold">{{ $kelas->nama_kelas }}</span>.
                    @if ($jadwalHariIni->count() > 0)
                        Ada <span class="font-bold text-white">{{ $jadwalHariIni->count() }}</span> mapel hari ini.
                    @else
                        Hari ini bebas!
                    @endif
                @else
                    Belum masuk kelas.
                @endif
            </p>
            <a href="{{ route('siswa.pembelajaran.index') }}"
                class="inline-block bg-white text-indigo-600 px-4 py-2 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-transform">
                Mulai Belajar
            </a>
        </div>
        <!-- Decorative Circle -->
        <div class="absolute -right-8 -bottom-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -right-4 -top-8 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-2">
                <i class='bx bx-check-circle text-xl'></i>
            </div>
            <span class="text-2xl font-bold text-gray-800">{{ number_format($persenHadir, 0) }}%</span>
            <span class="text-xs text-gray-400 font-medium">Kehadiran</span>
        </div>
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-2">
                <i class='bx bx-line-chart text-xl'></i>
            </div>
            <span class="text-2xl font-bold text-gray-800">{{ number_format($avgNilai, 1) }}</span>
            <span class="text-xs text-gray-400 font-medium">Rata-rata Nilai</span>
        </div>
    </div>

    <!-- Upcoming Exams Warning -->
    @if ($ujianTerdekat->count() > 0)
        <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <i class='bx bx-alarm-exclamation'></i>
                </div>
                <h3 class="font-bold text-gray-800">Ujian Terdekat</h3>
            </div>
            <div class="space-y-3">
                @foreach ($ujianTerdekat as $uj)
                    <div class="bg-white p-3 rounded-xl border border-red-100 shadow-sm flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $uj->ujian->nama_ujian }}</h4>
                            <p class="text-xs text-red-500 font-medium">{{ $uj->ujian->mataPelajaran->nama_mapel }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-xs font-bold text-gray-800 block">{{ \Carbon\Carbon::parse($uj->tanggal_ujian)->format('d M') }}</span>
                            <span
                                class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($uj->jam_mulai)->format('H:i') }}
                                WIB</span>
                            <a href="{{ route('siswa.ujian.show', $uj->id) }}"
                                class="text-[10px] text-indigo-600 font-bold mt-1 block">Detail ></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Schedule Today -->
    <div class="space-y-3">
        <div class="flex justify-between items-center px-1">
            <h3 class="font-bold text-gray-800 text-lg">Jadwal Hari Ini</h3>
            <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-lg">{{ date('d M Y') }}</span>
        </div>

        @forelse ($jadwalHariIni as $jadwal)
            <a href="{{ route('siswa.pembelajaran.show', $jadwal->id) }}"
                class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-200 transition-colors group">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class='bx bx-book-open'></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $jadwal->mataPelajaran->nama_mapel }}</h4>
                            <p class="text-xs text-gray-500">{{ $jadwal->guru->nama_lengkap }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-1 rounded-lg">
                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <i class='bx bx-time'></i>
                    <span>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                </div>
            </a>
        @empty
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                    <i class='bx bx-calendar-x text-3xl'></i>
                </div>
                <p class="text-gray-400 text-sm font-medium">Tidak ada jadwal hari ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Tasks List -->
    <div class="space-y-3 pb-8">
        <div class="flex justify-between items-center px-1 mt-6">
            <h3 class="font-bold text-gray-800 text-lg">Tugas Belum Selesai</h3>
            <a href="{{ route('siswa.tugas.index') }}" class="text-xs text-indigo-600 font-bold">Lihat Semua</a>
        </div>

        @forelse ($tugasPending as $tugas)
            <a href="{{ route('siswa.tugas.show', $tugas->id) }}"
                class="block bg-white p-4 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group active:scale-95 transition-all">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-orange-400"></div>

                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1 min-w-0 pr-4">
                        <span
                            class="text-[8px] font-black uppercase tracking-widest text-orange-500 bg-orange-50 px-2 py-0.5 rounded-lg mb-1 inline-block">Pending</span>
                        <h4
                            class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-indigo-600 transition-colors">
                            {{ $tugas->judul }}</h4>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-[8px] font-bold text-gray-400 uppercase">Deadline</span>
                        <span
                            class="text-[10px] font-bold text-red-500">{{ \Carbon\Carbon::parse($tugas->tanggal_deadline)->format('d M, H:i') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <i class='bx bx-book-bookmark'></i>
                    <span class="truncate">{{ $tugas->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</span>
                </div>
            </a>
        @empty
            <div class="text-center py-10 bg-white rounded-[32px] border border-dashed border-gray-100">
                <div
                    class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class='bx bx-check-double text-xl'></i>
                </div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Semua Tugas Beres!</p>
            </div>
        @endforelse
    </div>
@endsection
