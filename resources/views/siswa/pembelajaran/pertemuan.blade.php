@extends('layouts.siswa')

@section('title', 'Materi Pembelajaran')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <span class="text-muted fw-light">Pertemuan {{ $pertemuan->pertemuan_ke }} /</span>
                    {{ $pertemuan->judul_pertemuan }}
                </h4>
                <p class="mb-0 text-muted">
                    {{ $pertemuan->guruMengajar->mataPelajaran->nama_mapel }}
                </p>
            </div>
            <a href="{{ route('siswa.pembelajaran.show', $pertemuan->guru_mengajar_id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Konten Materi -->
        <div class="col-lg-8 mb-4 order-0 order-lg-0">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Materi Pembelajaran</h5>
                </div>
                <div class="card-body">
                    @if ($pertemuan->deskripsi)
                        <div class="alert alert-primary mb-4" role="alert">
                            <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-info-circle me-1"></i> Pengantar</h6>
                            <p class="mb-0">{{ $pertemuan->deskripsi }}</p>
                        </div>
                    @endif

                    <div class="accordion" id="accordionMateri">
                        @forelse($pertemuan->materiPembelajaran as $index => $materi)
                            <div class="accordion-item border shadow-none mb-3 active">
                                <h2 class="accordion-header" id="heading{{ $materi->id }}">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $materi->id }}" aria-expanded="true"
                                        aria-controls="collapse{{ $materi->id }}">
                                        <span class="me-2">
                                            @if ($materi->tipe_materi == 'file')
                                                <i class="bx bx-file text-warning"></i>
                                            @elseif($materi->tipe_materi == 'video')
                                                <i class="bx bx-video text-danger"></i>
                                            @elseif($materi->tipe_materi == 'link')
                                                <i class="bx bx-link text-info"></i>
                                            @else
                                                <i class="bx bx-text text-secondary"></i>
                                            @endif
                                        </span>
                                        {{ $materi->judul_materi }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $materi->id }}" class="accordion-collapse collapse show"
                                    aria-labelledby="heading{{ $materi->id }}">
                                    <div class="accordion-body">
                                        <p class="text-muted small mb-3">{{ $materi->deskripsi }}</p>

                                        @if ($materi->tipe_materi == 'file')
                                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                                <i class="bx bxs-file-pdf display-6 text-danger me-3"></i>
                                                <div>
                                                    <h6 class="mb-0">{{ $materi->file_name }}</h6>
                                                    <small class="text-muted">{{ round($materi->file_size / 1024) }}
                                                        KB</small>
                                                </div>
                                                @if ($materi->dapat_diunduh)
                                                    <a href="{{ Storage::url($materi->file_path) }}"
                                                        class="btn btn-primary ms-auto" target="_blank" download>
                                                        <i class="bx bx-download me-1"></i> Download
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary ms-auto" disabled>Preview Only</button>
                                                @endif
                                            </div>
                                        @elseif($materi->tipe_materi == 'video')
                                            <div class="ratio ratio-16x9">
                                                <iframe
                                                    src="{{ str_replace('youtu.be/', 'www.youtube.com/embed/', str_replace('watch?v=', 'embed/', $materi->video_url)) }}"
                                                    title="YouTube video" allowfullscreen></iframe>
                                            </div>
                                            <div class="mt-2 text-center">
                                                <a href="{{ $materi->video_url }}" target="_blank"
                                                    class="text-danger small"><i class="bx bxl-youtube"></i> Tonton di
                                                    YouTube</a>
                                            </div>
                                        @elseif($materi->tipe_materi == 'link')
                                            <div class="alert alert-secondary d-flex align-items-center" role="alert">
                                                <i class="bx bx-link-external me-2"></i>
                                                <div>
                                                    Materi ini berupa tautan eksternal:<br>
                                                    <a href="{{ $materi->link_url }}" target="_blank"
                                                        class="fw-bold">{{ $materi->link_url }}</a>
                                                </div>
                                            </div>
                                        @elseif($materi->tipe_materi == 'teks')
                                            <div class="p-3 bg-lighter rounded">
                                                {!! nl2br(e($materi->konten)) !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bx bx-folder-open text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2 text-muted">Belum ada materi untuk pertemuan ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Aktivitas (Tugas/Kuis) -->
        <div class="col-lg-4 order-1 order-lg-1">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Aktivitas</h5>
                </div>
                <div class="card-body">
                    <!-- Tugas List -->
                    <div class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-task"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2 w-100">
                                <h6 class="mb-0">Tugas ({{ $pertemuan->tugas->count() }})</h6>
                                @if ($pertemuan->tugas->count() > 0)
                                    <ul class="list-unstyled mb-0 mt-2">
                                        @foreach ($pertemuan->tugas as $tgs)
                                            @php
                                                // Check status (query ringan)
                                                $isDone = \App\Models\PengumpulanTugas::where('tugas_id', $tgs->id)
                                                    ->where('siswa_id', auth()->id())
                                                    ->exists();
                                                $color = $isDone ? 'success' : 'warning';
                                                $icon = $isDone ? 'bx-check-circle' : 'bx-time';
                                            @endphp
                                            <li class="mb-2 w-100">
                                                <a href="{{ route('siswa.tugas.show', $tgs->id) }}"
                                                    class="text-body d-flex align-items-center w-100 justify-content-between">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="bx {{ $icon }} text-{{ $color }} me-2"></i>
                                                        <small>{{ Str::limit($tgs->judul_tugas, 18) }}</small>
                                                    </span>
                                                    <i class="bx bx-chevron-right text-muted"
                                                        style="font-size: 0.8rem;"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <small class="text-muted">Tidak ada tugas</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Kuis Placeholder -->
                    <div class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-edit"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">Kuis</h6>
                                <small class="text-muted">Tidak ada kuis</small>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold">0</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Instruksi Guru</h6>
                    <p class="card-text small text-muted">
                        Silakan pelajari semua materi yang ada di sebelah kiri. Jika ada tugas, kerjakan sebelum tenggat
                        waktu. Jangan lupa isi presensi jika tersedia.
                    </p>
                    <button class="btn btn-label-success w-100" disabled>
                        <i class="bx bx-check-circle me-1"></i> Isi Presensi (Coming Soon)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
