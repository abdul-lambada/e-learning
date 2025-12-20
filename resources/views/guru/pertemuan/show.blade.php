@extends('layouts.guru')

@section('title', 'Detail Pertemuan')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <span class="text-muted fw-light">Pertemuan {{ $pertemuan->pertemuan_ke }} /</span>
                    {{ $pertemuan->judul_pertemuan }}
                </h4>
                <p class="mb-0 text-muted">
                    {{ $pertemuan->guruMengajar->mataPelajaran->nama_mapel }} -
                    {{ $pertemuan->guruMengajar->kelas->nama_kelas }}
                </p>
            </div>
            <a href="{{ route('guru.jadwal.show', $pertemuan->guru_mengajar_id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Kelas
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Info Pertemuan -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Deskripsi</h5>
                            <p>{{ $pertemuan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column gap-2">
                                <div class="badge bg-label-info p-2" style="font-size: 0.9rem;">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ $pertemuan->tanggal_pertemuan->format('l, d F Y') }}
                                </div>
                                <div class="badge bg-label-warning p-2" style="font-size: 0.9rem;">
                                    <i class="bx bx-time me-1"></i> {{ $pertemuan->jam_mulai->format('H:i') }} -
                                    {{ $pertemuan->jam_selesai->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tab Navigasi -->
        <div class="col-md-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-materi" aria-controls="navs-materi" aria-selected="true">
                            <i class="bx bx-book-content me-1"></i> Materi Pembelajaran
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-tugas" aria-controls="navs-tugas" aria-selected="false">
                            <i class="bx bx-task me-1"></i> Tugas & PR
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-kuis" aria-controls="navs-kuis" aria-selected="false">
                            <i class="bx bx-edit me-1"></i> Kuis
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Tab Materi -->
                    <div class="tab-pane fade show active" id="navs-materi" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Daftar Materi</h5>
                            <a href="{{ route('guru.materi.create', ['pertemuan_id' => $pertemuan->id]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i> Tambah Materi
                            </a>
                        </div>

                        @forelse($pertemuan->materiPembelajaran as $materi)
                            <div class="card mb-3 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar avatar-md me-3">
                                                @if ($materi->tipe_materi == 'file')
                                                    <span class="avatar-initial rounded bg-label-warning"><i
                                                            class="bx bx-file"></i></span>
                                                @elseif($materi->tipe_materi == 'video')
                                                    <span class="avatar-initial rounded bg-label-danger"><i
                                                            class="bx bx-video"></i></span>
                                                @elseif($materi->tipe_materi == 'link')
                                                    <span class="avatar-initial rounded bg-label-info"><i
                                                            class="bx bx-link"></i></span>
                                                @else
                                                    <span class="avatar-initial rounded bg-label-secondary"><i
                                                            class="bx bx-text"></i></span>
                                                @endif
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $materi->judul_materi }}</h5>
                                                <p class="text-muted mb-2 small">{{ $materi->deskripsi }}</p>

                                                @if ($materi->tipe_materi == 'file')
                                                    <a href="{{ Storage::url($materi->file_path) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="bx bx-download me-1"></i> Download File
                                                        ({{ round($materi->file_size / 1024) }} KB)
                                                    </a>
                                                @elseif($materi->tipe_materi == 'video')
                                                    <a href="{{ $materi->video_url }}"
                                                        class="btn btn-sm btn-outline-danger" target="_blank">
                                                        <i class="bx bx-play me-1"></i> Tonton Video
                                                    </a>
                                                @elseif($materi->tipe_materi == 'link')
                                                    <a href="{{ $materi->link_url }}" class="btn btn-sm btn-outline-info"
                                                        target="_blank">
                                                        <i class="bx bx-link-external me-1"></i> Buka Link
                                                    </a>
                                                @elseif($materi->tipe_materi == 'teks')
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalMateri{{ $materi->id }}">
                                                        <i class="bx bx-book-open me-1"></i> Baca Konten
                                                    </button>

                                                    <!-- Modal Text Content -->
                                                    <div class="modal fade" id="modalMateri{{ $materi->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div
                                                            class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">{{ $materi->judul_materi }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {!! nl2br(e($materi->konten)) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="materiOpt{{ $materi->id }}"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="materiOpt{{ $materi->id }}">
                                                <a class="dropdown-item"
                                                    href="{{ route('guru.materi.edit', $materi->id) }}">Edit</a>
                                                <form action="{{ route('guru.materi.destroy', $materi->id) }}"
                                                    method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bx bx-folder-open" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada materi pembelajaran.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Tab Tugas -->
                    <div class="tab-pane fade" id="navs-tugas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Daftar Tugas</h5>
                            <a href="{{ route('guru.tugas.create', ['pertemuan_id' => $pertemuan->id]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i> Buat Tugas Baru
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible mb-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @forelse($pertemuan->tugas as $tugas)
                            <div class="card mb-3 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar avatar-md me-3">
                                                <span class="avatar-initial rounded bg-label-primary"><i
                                                        class="bx bx-task"></i></span>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="{{ route('guru.tugas.show', $tugas->id) }}"
                                                        class="text-body">
                                                        {{ $tugas->judul_tugas }}
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2 small">{{ Str::limit($tugas->deskripsi, 100) }}
                                                </p>

                                                <div class="d-flex gap-2 text-muted small">
                                                    <span><i class="bx bx-calendar me-1"></i> Deadline:
                                                        <strong>{{ $tugas->tanggal_deadline->format('d M Y, H:i') }}</strong></span>
                                                    @if ($tugas->upload_file)
                                                        <span class="badge bg-label-secondary">File Upload</span>
                                                    @endif
                                                    @if ($tugas->upload_link)
                                                        <span class="badge bg-label-info">Link</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="tugasOpt{{ $tugas->id }}"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="tugasOpt{{ $tugas->id }}">
                                                <a class="dropdown-item"
                                                    href="{{ route('guru.tugas.show', $tugas->id) }}">
                                                    <i class="bx bx-show me-1"></i> Lihat & Nilai
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('guru.tugas.edit', $tugas->id) }}">Edit</a>
                                                <form action="{{ route('guru.tugas.destroy', $tugas->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus tugas ini? Semua pengumpulan siswa akan terhapus.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('guru.tugas.show', $tugas->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Lihat Pengumpulan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bx bx-task" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada tugas yang dibuat.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Tab Kuis (Placeholder) -->
                    <!-- Tab Kuis -->
                    <div class="tab-pane fade" id="navs-kuis" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Daftar Kuis & Ujian</h5>
                            <a href="{{ route('guru.kuis.create', ['pertemuan_id' => $pertemuan->id]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i> Buat Kuis Baru
                            </a>
                        </div>

                        @forelse($pertemuan->kuis as $kuis)
                            <div class="card mb-3 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h5 class="mb-1">{{ $kuis->judul_kuis }}</h5>
                                                @if (!$kuis->aktif)
                                                    <span class="badge bg-label-secondary">Non-Aktif</span>
                                                @endif
                                            </div>
                                            <p class="mb-2 text-muted small">
                                                {{ $kuis->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                            <div class="d-flex gap-3 text-muted small flex-wrap">
                                                <span><i class="bx bx-time me-1"></i> {{ $kuis->durasi_menit }}
                                                    Menit</span>
                                                <span><i class="bx bx-list-check me-1"></i> {{ $kuis->soalKuis->count() }}
                                                    Soal</span>
                                                <span><i class="bx bx-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($kuis->tanggal_mulai)->format('d M H:i') }}</span>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="kuisOpt{{ $kuis->id }}"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="kuisOpt{{ $kuis->id }}">
                                                <a class="dropdown-item"
                                                    href="{{ route('guru.kuis.show', $kuis->id) }}">Kelola Soal</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('guru.kuis.edit', $kuis->id) }}">Edit Pengaturan</a>
                                                <form action="{{ route('guru.kuis.destroy', $kuis->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus kuis ini? Semua soal dan nilai siswa akan hilang.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('guru.kuis.show', $kuis->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-cog me-1"></i> Kelola Kuis & Soal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bx bx-edit" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada kuis yang dibuat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
