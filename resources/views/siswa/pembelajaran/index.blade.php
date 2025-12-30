@extends('layouts.siswa_mobile')

@section('title', 'Jadwal Pelajaran')

@section('content')
    <div class="space-y-6 pb-8">
        <!-- Header Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Jadwal Pelajaran</h2>
            @if ($kelas)
                <p class="text-sm text-gray-500">Kelas <span class="font-bold text-indigo-600">{{ $kelas->nama_kelas }}</span>
                </p>
            @endif
        </div>

        @if (!$kelas)
            <div class="bg-orange-50 border border-orange-100 p-4 rounded-2xl flex items-start gap-3">
                <i class='bx bx-error-circle text-orange-500 text-xl'></i>
                <div>
                    <h4 class="font-bold text-orange-800 text-sm">Belum Ada Kelas</h4>
                    <p class="text-orange-600 text-xs">Kamu belum terdaftar di kelas manapun. Silakan hubungi admin sekolah.
                    </p>
                </div>
            </div>
        @endif

        <!-- List Jadwal -->
        <div class="space-y-4">
            @forelse($jadwalPelajaran as $data)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col group">
                    @if ($data->mataPelajaran->gambar_cover)
                        <div class="h-32 w-full overflow-hidden relative">
                            <img src="{{ Storage::url($data->mataPelajaran->gambar_cover) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                alt="{{ $data->mataPelajaran->nama_mapel }}">
                            <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                            <span class="absolute bottom-3 left-4 text-white font-bold">{{ $data->hari }}</span>
                        </div>
                    @else
                        <div
                            class="h-24 w-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative">
                            <i class='bx bx-book-open text-white/20 text-6xl absolute right-[-10px] bottom-[-10px]'></i>
                            <span class="absolute bottom-3 left-4 text-white font-bold">{{ $data->hari }}</span>
                        </div>
                    @endif

                    <div class="p-4 space-y-3">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $data->mataPelajaran->nama_mapel }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <i class='bx bx-user text-gray-400 text-xs'></i>
                                <span class="text-xs text-gray-500 font-medium">{{ $data->guru->nama_lengkap }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-2 border-y border-gray-50">
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class='bx bx-time text-indigo-500'></i>
                                <span>{{ \Carbon\Carbon::parse($data->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($data->jam_selesai)->format('H:i') }}</span>
                            </div>
                            <span
                                class="text-[10px] font-bold bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full uppercase tracking-wider">
                                Aktif
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1.5 pt-1">
                            <div
                                class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest leading-none">
                                <span class="text-gray-400">Progress Belajar</span>
                                <span class="text-indigo-600">{{ $data->progress_percent }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 rounded-full transition-all duration-1000"
                                    style="width: {{ $data->progress_percent }}%"></div>
                            </div>
                            <div class="text-[8px] text-gray-400 font-bold uppercase">{{ $data->materi_selesai }} dari
                                {{ $data->total_materi }} materi selesai</div>
                        </div>

                        <a href="{{ route('siswa.pembelajaran.show', $data->id) }}"
                            class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold text-center block shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                            Buka Materi
                        </a>
                    </div>
                </div>
            @empty
                @if ($kelas)
                    <div class="text-center py-12 px-4">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-calendar-x text-5xl text-gray-200'></i>
                        </div>
                        <h4 class="font-bold text-gray-800">Jadwal Masih Kosong</h4>
                        <p class="text-gray-400 text-sm mt-1">Belum ada mata pelajaran yang dijadwalkan untuk kelas kamu.
                        </p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
@endsection
