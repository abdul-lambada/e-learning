@extends('layouts.siswa_mobile')

@section('title', 'Dashboard')

@section('content')
    @php
        $hour = date('H');
        $greeting =
            $hour < 11
                ? 'Selamat Pagi'
                : ($hour < 15
                    ? 'Selamat Siang'
                    : ($hour < 19
                        ? 'Selamat Sore'
                        : 'Selamat Malam'));
    @endphp

    <!-- Welcome Card -->
    <div class="bg-indigo-600 rounded-[32px] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden mb-6">
        <div class="relative z-10">
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-200 mb-2">{{ $greeting }}</h2>
            <h1 class="text-2xl font-black mb-1 leading-tight flex items-center gap-2">
                {{ explode(' ', auth()->user()->nama_lengkap)[0] }}!
                <span
                    class="bg-yellow-400 text-white text-[9px] px-2 py-0.5 rounded-full flex items-center gap-1 shadow-lg shadow-yellow-500/20">
                    <i class='bx bxs-zap'></i> {{ auth()->user()->poin }}
                </span>
            </h1>
            <p class="text-indigo-100 text-[11px] font-medium opacity-80 mb-6">
                @if ($kelas)
                    Kamu di kelas <span class="font-bold border-b border-indigo-300">#{{ $kelas->nama_kelas }}</span>.
                    @if ($jadwalHariIni->count() > 0)
                        Ada {{ $jadwalHariIni->count() }} mapel hari ini.
                    @endif
                @else
                    Belum masuk kelas.
                @endif
            </p>
            <div class="flex gap-3">
                <a href="{{ route('siswa.pembelajaran.index') }}"
                    class="bg-white text-indigo-600 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                    Belajar
                </a>
                <a href="{{ route('siswa.absensi.index') }}"
                    class="bg-indigo-500/30 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all border border-white/10 ring-1 ring-white/10">
                    Absen
                </a>
            </div>
        </div>
        <!-- Modern Decorative Elements -->
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
        <div class="absolute -left-12 -bottom-12 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>
    </div>

    <!-- Weekly Activity Chart -->
    <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm mb-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 text-sm">Aktivitas Belajar</h3>
            <span
                class="text-[8px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase tracking-widest">7
                Hari Terakhir</span>
        </div>
        <div class="h-40 relative">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <!-- Class Leaderboard Widget -->
    @if ($topSiswa->count() > 0)
        <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 text-sm">Leaderboard Kelas</h3>
                <a href="{{ route('siswa.leaderboard') }}"
                    class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-4">
                @foreach ($topSiswa as $index => $s)
                    @php
                        $colors = [
                            'text-yellow-500 bg-yellow-50',
                            'text-slate-400 bg-slate-50',
                            'text-orange-500 bg-orange-50',
                        ];
                        $icons = ['bx-medal', 'bx-medal', 'bx-medal'];
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $colors[$index] }} flex items-center justify-center shrink-0">
                            <i class='bx {{ $icons[$index] }} text-lg'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-gray-800 truncate">{{ $s->nama_lengkap }}</h4>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">{{ $s->poin }}
                                points</p>
                        </div>
                        @if ($index == 0)
                            <div class="px-2 py-0.5 bg-yellow-400 text-white text-[8px] font-black rounded-full uppercase">
                                Top 1</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('activityChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($weekLabels) !!},
                        datasets: [{
                            label: 'Tugas',
                            data: {!! json_encode($weeklyData['tugas']) !!},
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0
                        }, {
                            label: 'Kuis',
                            data: {!! json_encode($weeklyData['kuis']) !!},
                            borderColor: '#9333ea',
                            backgroundColor: 'rgba(147, 51, 234, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                display: false
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 9,
                                        family: "'Outfit', sans-serif",
                                        weight: 'bold'
                                    },
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
