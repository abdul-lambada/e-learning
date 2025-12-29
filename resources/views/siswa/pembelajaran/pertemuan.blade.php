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
            <x-card title="Materi Pembelajaran" class="mb-4">
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
                                            <a href="{{ $materi->video_url }}" target="_blank" class="text-danger small"><i
                                                    class="bx bxl-youtube"></i> Tonton di
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
            </x-card>

            <!-- Forum Diskusi -->
            <x-card title="Forum Diskusi" class="mt-4">
                <div id="diskusi-container" class="mb-4" style="max-height: 500px; overflow-y: auto; padding: 10px;">
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
                                <span class="badge bg-label-info d-flex align-items-center justify-content-between">
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
                                    <button type="submit" class="btn btn-primary btn-icon" id="btn-send-diskusi">
                                        <i class="bx bx-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Sidebar Aktivitas (Tugas/Kuis) -->
        <div class="col-lg-4 order-1 order-lg-1">
            <x-card title="Aktivitas" class="mb-4">
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
                                                    <i class="bx {{ $icon }} text-{{ $color }} me-2"></i>
                                                    <small>{{ Str::limit($tgs->judul_tugas, 18) }}</small>
                                                </span>
                                                <i class="bx bx-chevron-right text-muted" style="font-size: 0.8rem;"></i>
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

                <!-- Kuis -->
                <div class="d-flex mt-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-joystick"></i></span>
                    </div>
                    <div class="w-100">
                        <h6 class="mb-1">Kuis & Ujian</h6>
                        @if ($pertemuan->kuis->count() > 0)
                            <ul class="list-unstyled mb-0 mt-2">
                                @foreach ($pertemuan->kuis as $qz)
                                    @php
                                        $attempt = \App\Models\JawabanKuis::where('kuis_id', $qz->id)
                                            ->where('siswa_id', auth()->id())
                                            ->orderBy('id', 'desc')
                                            ->first();
                                        $isDone = $attempt && $attempt->status == 'selesai';
                                        $isRunning = $attempt && $attempt->status == 'sedang_dikerjakan';

                                        $badgeColor = 'secondary';
                                        $statusText = 'Mulai';

                                        if ($isDone) {
                                            $badgeColor = 'success';
                                            $statusText = 'Nilai: ' . (float) $attempt->nilai;
                                        } elseif ($isRunning) {
                                            $badgeColor = 'warning';
                                            $statusText = 'Lanjut';
                                        }
                                    @endphp
                                    <li class="mb-2 w-100">
                                        <a href="{{ route('siswa.kuis.show', $qz->id) }}"
                                            class="text-body d-flex align-items-center w-100 justify-content-between">
                                            <span class="d-flex align-items-center">
                                                <i class="bx bx-chevron-right me-1 text-muted"></i>
                                                <small
                                                    class="{{ $isDone ? 'text-decoration-line-through text-muted' : '' }}">{{ Str::limit($qz->judul_kuis, 15) }}</small>
                                            </span>
                                            <span class="badge bg-label-{{ $badgeColor }} p-1"
                                                style="font-size: 0.7rem;">{{ $statusText }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <small class="text-muted">Tidak ada kuis aktif</small>
                        @endif
                    </div>
                </div>
            </x-card>

            <x-card title="Instruksi Guru">
                <p class="card-text small text-muted">
                    Silakan pelajari semua materi yang ada di sebelah kiri. Jika ada tugas, kerjakan sebelum tenggat
                    waktu. Jangan lupa isi presensi jika tersedia.
                </p>
                @php
                    $myAbsensi = \App\Models\Absensi::where('pertemuan_id', $pertemuan->id)
                        ->where('siswa_id', auth()->id())
                        ->first();
                @endphp

                @if ($myAbsensi)
                    <div class="alert alert-success py-2 mb-0 text-center">
                        <i class="bx bx-check-circle me-1"></i> Kehadiran:
                        <strong>{{ ucfirst($myAbsensi->status) }}</strong>
                    </div>
                @elseif($pertemuan->aktif)
                    <form action="{{ route('siswa.pembelajaran.absen', $pertemuan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                            onclick="return confirm('Apakah Anda yakin ingin mengisi presensi sekarang?')">
                            <i class="bx bx-user-check me-1"></i> Isi Presensi (Hadir)
                        </button>
                    </form>
                @else
                    <div class="alert alert-secondary py-2 mb-0 text-center">
                        <i class="bx bx-lock-alt me-1"></i> Presensi Ditutup
                    </div>
                @endif
            </x-card>
        </div>
    </div>
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

                // Poll for updates every 10 seconds
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
                                        ${(comment.user_id == {{ Auth::id() }}) ?
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
                                        ${(reply.user_id == {{ Auth::id() }}) ?
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
