@extends('layouts.siswa_mobile')

@section('title', 'Pendahuluan - ' . $jadwal->mataPelajaran->nama_mapel)

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Back Button & Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.pembelajaran.show', $jadwal->id) }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-gray-900 leading-tight truncate">Pendahuluan</h2>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest leading-none">
                    {{ $jadwal->mataPelajaran->nama_mapel }}</p>
            </div>
        </div>

        @if ($pendahuluan)
            <!-- Hero Info Card -->
            <div class="bg-indigo-600 rounded-[32px] p-8 text-white shadow-lg shadow-indigo-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10 space-y-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class='bx bx-info-circle text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black leading-tight">{{ $pendahuluan->judul }}</h1>
                        <p class="text-indigo-100 text-xs font-medium mt-1">Informasi dasar & kontrak pengerjaan mata
                            pelajaran.</p>
                    </div>
                </div>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 space-y-6">
                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed text-xs">
                    {!! $pendahuluan->konten !!}
                </div>

                @if ($pendahuluan->video_url)
                    <div class="space-y-3 pt-4 border-t border-gray-50">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Video Pengantar</h4>
                        </div>

                        @php
                            $videoId = null;
                            if (
                                preg_match(
                                    '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                                    $pendahuluan->video_url,
                                    $match,
                                )
                            ) {
                                $videoId = $match[1];
                            }
                        @endphp

                        @if ($videoId)
                            <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-100">
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}"
                                    frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $pendahuluan->video_url }}" target="_blank"
                                class="flex items-center justify-center gap-3 w-full py-4 bg-red-50 text-red-600 rounded-2xl font-bold text-xs ring-1 ring-red-100 active:scale-95 transition-all">
                                <i class='bx bxl-youtube text-xl'></i>
                                Tonton di YouTube
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-100 px-10">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4">
                    <i class='bx bx-book-content text-4xl text-gray-200'></i>
                </div>
                <h4 class="font-bold text-gray-800">Belum Ada Informasi</h4>
                <p class="text-[10px] text-gray-400 mt-2 font-medium leading-relaxed">Guru pengampu belum mempublikasikan
                    informasi pendahuluan untuk mata pelajaran ini.</p>
                <a href="{{ route('siswa.pembelajaran.show', $jadwal->id) }}"
                    class="inline-block mt-6 px-6 py-3 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-indigo-600 shadow-sm active:scale-95 transition-all">
                    Kembali
                </a>
            </div>
        @endif
    </div>

    <style>
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
