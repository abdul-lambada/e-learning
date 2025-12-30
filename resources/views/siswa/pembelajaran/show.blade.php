@extends('layouts.siswa_mobile')

@section('title', $jadwal->mataPelajaran->nama_mapel)

@section('content')
    <div class="space-y-6 pb-12">
        <!-- Back Button & Title -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.pembelajaran.index') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight line-clamp-1">
                    {{ $jadwal->mataPelajaran->nama_mapel }}</h2>
                <p class="text-xs text-gray-500 font-medium">{{ $jadwal->guru->nama_lengkap }}</p>
            </div>
        </div>

        <!-- Subject Hero -->
        <div class="relative h-44 rounded-3xl overflow-hidden shadow-lg shadow-indigo-100">
            @if ($jadwal->mataPelajaran->gambar_cover)
                <img src="{{ Storage::url($jadwal->mataPelajaran->gambar_cover) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-linear-to-br from-indigo-600 to-purple-700 flex items-center justify-center">
                    <i class='bx bx-book-open text-white/20 text-8xl absolute right-[-20px] bottom-[-20px]'></i>
                </div>
            @endif
            <div
                class="absolute inset-x-0 bottom-0 p-4 bg-linear-to-t from-black/80 via-black/40 to-transparent text-white">
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300">Target</span>
                        <p class="text-sm font-bold">{{ $jadwal->mataPelajaran->jumlah_pertemuan }} Pertemuan</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300">Durasi</span>
                        <p class="text-sm font-bold">{{ $jadwal->mataPelajaran->durasi_menit }} Menit</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendahuluan Section -->
        <a href="{{ route('siswa.pendahuluan.show', $jadwal->id) }}"
            class="flex items-center gap-4 bg-indigo-50 p-4 rounded-2xl border border-indigo-100 group">
            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                <i class='bx bx-info-circle text-2xl'></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-indigo-900 text-sm">Kontrak Belajar</h4>
                <p class="text-[10px] text-indigo-500">Klik untuk melihat detail materi awal</p>
            </div>
            <i class='bx bx-chevron-right text-indigo-400 text-xl group-hover:translate-x-1 transition-transform'></i>
        </a>

        <!-- Timeline Pertemuan -->
        <div class="space-y-4">
            <h3 class="font-bold text-gray-800 text-lg px-1">Daftar Pertemuan</h3>

            @forelse ($jadwal->pertemuan as $pertemuan)
                <div class="flex gap-4">
                    <!-- Left Timeline Pillar -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-2.5 h-2.5 rounded-full {{ $pertemuan->status == 'berlangsung' ? 'bg-indigo-600 ring-4 ring-indigo-100' : 'bg-gray-300' }}">
                        </div>
                        @if (!$loop->last)
                            <div class="w-0.5 flex-1 bg-gray-100 my-1"></div>
                        @endif
                    </div>

                    <!-- Content Card -->
                    <div class="flex-1 pb-6">
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm leading-tight">
                                        Ke-{{ $pertemuan->pertemuan_ke }}: {{ $pertemuan->judul_pertemuan }}</h4>
                                    <span
                                        class="text-[10px] text-gray-400 font-medium">{{ $pertemuan->tanggal_pertemuan->format('d M Y') }}</span>
                                </div>
                                @if ($pertemuan->status == 'berlangsung')
                                    <span
                                        class="text-[8px] font-black uppercase bg-green-500 text-white px-2 py-1 rounded-full animate-pulse">Live</span>
                                @endif
                            </div>

                            <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-2">
                                {{ $pertemuan->deskripsi }}
                            </p>

                            <a href="{{ route('siswa.pembelajaran.pertemuan', $pertemuan->id) }}"
                                class="inline-flex items-center gap-2 text-indigo-600 font-bold text-xs hover:gap-3 transition-all">
                                Buka Materi <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-400 text-sm">Belum ada pertemuan yang dibuat.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
