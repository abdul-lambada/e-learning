@extends('layouts.app')

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
            <x-card>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-2">Deskripsi</h5>
                        <p class="mb-0">{{ $pertemuan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column gap-2 mt-3 mt-md-0">
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
            </x-card>
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
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-absensi" aria-controls="navs-absensi" aria-selected="false">
                            <i class="bx bx-user-check me-1"></i> Kehadiran
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-diskusi" aria-controls="navs-diskusi" aria-selected="false">
                            <i class="bx bx-chat me-1"></i> Forum Diskusi
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Tab Materi -->
                    <div class="tab-pane fade show active" id="navs-materi" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Daftar Materi</h5>
                            @can('kelola materi')
                                <a href="{{ route('guru.materi.create', ['pertemuan_id' => $pertemuan->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus me-1"></i> Tambah Materi
                                </a>
                            @endcan
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
                                                @endif
                                            </div>
                                        </div>
                                        @can('kelola materi')
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="materiOpt{{ $materi->id }}"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="materiOpt{{ $materi->id }}">
                                                    <a class="dropdown-item"
                                                        href="{{ route('guru.materi.edit', $materi->id) }}">Edit</a>
                                                    <button type="button" class="dropdown-item text-danger btn-delete"
                                                        data-url="{{ route('guru.materi.destroy', $materi->id) }}"
                                                        data-name="{{ $materi->judul_materi }}" data-title="Hapus Materi">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
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
                            @can('kelola tugas')
                                <a href="{{ route('guru.tugas.create', ['pertemuan_id' => $pertemuan->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus me-1"></i> Buat Tugas Baru
                                </a>
                            @endcan
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
                                                @can('kelola tugas')
                                                    <a class="dropdown-item"
                                                        href="{{ route('guru.tugas.edit', $tugas->id) }}">Edit</a>
                                                    <button type="button" class="dropdown-item text-danger btn-delete"
                                                        data-url="{{ route('guru.tugas.destroy', $tugas->id) }}"
                                                        data-name="{{ $tugas->judul_tugas }}" data-title="Hapus Tugas">
                                                        Hapus
                                                    </button>
                                                @endcan
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
                            @can('kelola kuis')
                                <a href="{{ route('guru.kuis.create', ['pertemuan_id' => $pertemuan->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus me-1"></i> Buat Kuis Baru
                                </a>
                            @endcan
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
                                                @can('kelola kuis')
                                                    <a class="dropdown-item"
                                                        href="{{ route('guru.kuis.edit', $kuis->id) }}">Edit Pengaturan</a>
                                                    <button type="button" class="dropdown-item text-danger btn-delete"
                                                        data-url="{{ route('guru.kuis.destroy', $kuis->id) }}"
                                                        data-name="{{ $kuis->judul_kuis }}" data-title="Hapus Kuis">
                                                        Hapus
                                                    </button>
                                                @endcan
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

                    <!-- Tab Absensi -->
                    <div class="tab-pane fade" id="navs-absensi" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Daftar Kehadiran Siswa</h5>
                            <div>
                                <span class="badge bg-label-success me-1">H : Hadir</span>
                                <span class="badge bg-label-info me-1">I : Izin</span>
                                <span class="badge bg-label-warning me-1">S : Sakit</span>
                                <span class="badge bg-label-danger">A : Alpha</span>
                            </div>
                        </div>
                        <form action="{{ route('guru.pertemuan.absensi', $pertemuan->id) }}" method="POST">
                            @csrf
                            <div class="table-responsive text-nowrap border rounded mb-3">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th class="text-center">H</th>
                                            <th class="text-center">I</th>
                                            <th class="text-center">S</th>
                                            <th class="text-center">A</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pertemuan->guruMengajar->kelas->users as $siswa)
                                            @php
                                                $abs = $pertemuan->absensi->where('siswa_id', $siswa->id)->first();
                                                $st = $abs ? $abs->status : 'hadir';
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">{{ $siswa->nama_lengkap }}</span>
                                                        <small class="text-muted">{{ $siswa->nis }}</small>
                                                    </div>
                                                </td>
                                                <td class="text-center"><input class="form-check-input" type="radio"
                                                        name="status[{{ $siswa->id }}]" value="hadir"
                                                        {{ $st == 'hadir' ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input" type="radio"
                                                        name="status[{{ $siswa->id }}]" value="izin"
                                                        {{ $st == 'izin' ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input" type="radio"
                                                        name="status[{{ $siswa->id }}]" value="sakit"
                                                        {{ $st == 'sakit' ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input" type="radio"
                                                        name="status[{{ $siswa->id }}]" value="alpha"
                                                        {{ $st == 'alpha' ? 'checked' : '' }}></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                @can('kelola absensi')
                                    <x-button type="submit" icon="bx-save">
                                        Simpan Absensi
                                    </x-button>
                                @endcan
                            </div>
                        </form>
                    </div>

                    <!-- Tab Diskusi -->
                    <div class="tab-pane fade" id="navs-diskusi" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Forum Diskusi Kelas</h5>
                            <span class="badge bg-label-primary">Real-time Forum</span>
                        </div>

                        <div id="diskusi-container" class="mb-4"
                            style="max-height: 500px; overflow-y: auto; padding: 10px;">
                            <!-- Pesan akan dimuat via JS -->
                            <div class="text-center py-5" id="diskusi-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Memuat diskusi...</p>
                            </div>
                        </div>

                        <div class="card bg-lighter border-0 shadow-none">
                            <div class="card-body">
                                <form id="form-diskusi">
                                    <input type="hidden" name="parent_id" id="diskusi-parent-id" value="">
                                    <div id="reply-info" class="mb-2 d-none">
                                        <span
                                            class="badge bg-label-info d-flex align-items-center justify-content-between">
                                            <span>Membalas pesan...</span>
                                            <i class="bx bx-x cursor-pointer" onclick="cancelReply()"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <div class="flex-grow-1">
                                            <textarea class="form-control" name="pesan" id="diskusi-pesan" rows="2"
                                                placeholder="Tulis pertanyaan atau tanggapan Anda..."></textarea>
                                        </div>
                                        <div class="align-self-end">
                                            <button type="submit" class="btn btn-primary btn-icon"
                                                id="btn-send-diskusi">
                                                <i class="bx bx-paper-plane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modals untuk Materi Teks -->
    @foreach ($pertemuan->materiPembelajaran as $materi)
        @if ($materi->tipe_materi == 'teks')
            <div class="modal fade" id="modalMateri{{ $materi->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $materi->judul_materi }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {!! nl2br(e($materi->konten)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const discussionContainer = document.getElementById('diskusi-container');
                const formDiskusi = document.getElementById('form-diskusi');
                const diskusiPesan = document.getElementById('diskusi-pesan');
                const diskusiParentId = document.getElementById('diskusi-parent-id');
                const replyInfo = document.getElementById('reply-info');
                const btnSend = document.getElementById('btn-send-diskusi');

                let lastFetchTime = null;

                // Load Initial Data
                loadDiscussion();

                // Poll for updates every 10 seconds (Pseudo real-time)
                setInterval(loadDiscussion, 10000);

                function loadDiscussion() {
                    fetch("{{ route('diskusi.index', $pertemuan->id) }}")
                        .then(response => response.json())
                        .then(data => {
                            if (data.data.length > 0) {
                                renderDiscussion(data.data);
                            } else {
                                if (!lastFetchTime) {
                                    discussionContainer.innerHTML = `
                                        <div class="text-center py-5">
                                            <i class="bx bx-chat text-muted" style="font-size: 3rem;"></i>
                                            <p class="mt-2 text-muted">Belum ada diskusi. Mulai percakapan sekarang!</p>
                                        </div>
                                    `;
                                }
                            }
                            lastFetchTime = new Date();
                        })
                        .catch(err => console.error('Error loading discussion:', err));
                }

                function renderDiscussion(comments) {
                    let html = '';
                    comments.forEach(comment => {
                        html += `
                            <div class="d-flex align-items-start mb-4">
                                <div class="avatar avatar-sm me-3 flex-shrink-0">
                                    <span class="avatar-initial rounded-circle bg-label-${comment.user.peran === 'guru' ? 'primary' : 'info'}">
                                        ${comment.user.nama_lengkap.charAt(0)}
                                    </span>
                                </div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <span class="fw-bold me-2">${comment.user.nama_lengkap}</span>
                                            <span class="badge bg-label-${comment.user.peran === 'guru' ? 'primary' : 'info'} btn-xs">
                                                ${comment.user.peran.toUpperCase()}
                                            </span>
                                        </div>
                                        <small class="text-muted">${formatDate(comment.created_at)}</small>
                                    </div>
                                    <div class="bg-light p-3 rounded mb-2">
                                        <p class="mb-0 text-wrap" style="word-break: break-word;">${comment.pesan}</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-xs text-primary p-0" onclick="prepareReply(${comment.id}, '${comment.user.nama_lengkap}')">Balas</button>
                                        ${(comment.user_id == {{ Auth::id() }} || {{ Auth::user()->isGuru() ? 'true' : 'false' }}) ?
                                            `<button class="btn btn-xs text-danger p-0" onclick="deleteComment(${comment.id})">Hapus</button>` : ''}
                                    </div>

                                    <!-- Replies -->
                                    <div class="ms-5 mt-3 border-start ps-3" id="replies-${comment.id}">
                                        ${renderReplies(comment.replies || [])}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    discussionContainer.innerHTML = html;
                }

                function renderReplies(replies) {
                    let html = '';
                    replies.forEach(reply => {
                        html += `
                            <div class="d-flex align-items-start mb-3">
                                <div class="avatar avatar-xs me-2 flex-shrink-0">
                                    <span class="avatar-initial rounded-circle bg-label-${reply.user.peran === 'guru' ? 'primary' : 'info'}">
                                        ${reply.user.nama_lengkap.charAt(0)}
                                    </span>
                                </div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2 small">${reply.user.nama_lengkap}</span>
                                            <span class="badge bg-label-${reply.user.peran === 'guru' ? 'primary' : 'info'}" style="font-size: 0.6rem;">
                                                ${reply.user.peran.toUpperCase()}
                                            </span>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.7rem;">${formatDate(reply.created_at)}</small>
                                    </div>
                                    <div class="bg-lighter p-2 rounded mb-1">
                                        <p class="mb-0 small text-wrap" style="word-break: break-word;">${reply.pesan}</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        ${(reply.user_id == {{ Auth::id() }} || {{ Auth::user()->isGuru() ? 'true' : 'false' }}) ?
                                            `<button class="btn btn-xs text-danger p-0" style="font-size: 0.7rem;" onclick="deleteComment(${reply.id})">Hapus</button>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    return html;
                }

                function formatDate(dateStr) {
                    const date = new Date(dateStr);
                    return date.toLocaleString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        day: '2-digit',
                        month: 'short'
                    });
                }

                window.prepareReply = function(id, name) {
                    diskusiParentId.value = id;
                    replyInfo.classList.remove('d-none');
                    replyInfo.querySelector('span').innerText = `Membalas ke: ${name}`;
                    diskusiPesan.focus();
                }

                window.cancelReply = function() {
                    diskusiParentId.value = '';
                    replyInfo.classList.add('d-none');
                }

                window.deleteComment = function(id) {
                    if (confirm('Hapus pesan ini?')) {
                        fetch(`/diskusi/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(res => {
                                if (res.success) {
                                    loadDiscussion();
                                }
                            });
                    }
                }

                formDiskusi.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const pesan = diskusiPesan.value.trim();
                    if (!pesan) return;

                    btnSend.disabled = true;

                    fetch("{{ route('diskusi.store', $pertemuan->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                pesan: pesan,
                                parent_id: diskusiParentId.value
                            })
                        })
                        .then(response => response.json())
                        .then(res => {
                            if (res.success) {
                                diskusiPesan.value = '';
                                cancelReply();
                                loadDiscussion();
                            }
                        })
                        .finally(() => {
                            btnSend.disabled = false;
                        });
                });
            });
        </script>
    @endpush
@endsection
