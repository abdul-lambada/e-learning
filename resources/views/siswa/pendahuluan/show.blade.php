@extends('layouts.app')

@section('title', 'Pendahuluan - ' . $jadwal->mataPelajaran->nama_mapel)

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Siswa / {{ $jadwal->mataPelajaran->nama_mapel }} /</span> Pendahuluan
            </h4>

            <div class="card mb-4">
                <div class="card-body">
                    @if ($pendahuluan)
                        <div class="text-center mb-5">
                            <h2 class="fw-bold mb-2">{{ $pendahuluan->judul }}</h2>
                            <p class="text-muted">Informasi dasar mengenai mata pelajaran ini.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-10 mx-auto">
                                <div class="content-body prose prose-slate max-w-none">
                                    {!! $pendahuluan->konten !!}
                                </div>

                                @if ($pendahuluan->video_url)
                                    <div class="mt-5 border-top pt-4 text-center">
                                        <h5 class="mb-3">Video Pengantar</h5>
                                        @php
                                            // Simple youtube embed logic if needed
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
                                            <div class="ratio ratio-16x9 shadow rounded overflow-hidden mx-auto"
                                                style="max-width: 800px;">
                                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                    title="YouTube video" allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <a href="{{ $pendahuluan->video_url }}" target="_blank"
                                                class="btn btn-outline-danger">
                                                <i class="bx bxl-youtube me-2"></i> Tonton di YouTube
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/page-misc-error-light.png"
                                alt="No Intro" width="200" class="mb-3">
                            <h4>Materi Pendahuluan Belum Tersedia</h4>
                            <p class="text-muted">Guru belum mempublikasikan informasi pendahuluan untuk mata pelajaran ini.
                            </p>
                        </div>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('siswa.pembelajaran.show', $jadwal->id) }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Pertemuan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .content-body {
            font-size: 1.1rem;
            line-height: 1.7;
        }
    </style>
@endpush
