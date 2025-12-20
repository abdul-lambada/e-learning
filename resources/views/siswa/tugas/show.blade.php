@extends('layouts.siswa')

@section('title', 'Detail Tugas')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">{{ $tugas->judul_tugas }}</h4>
                <p class="mb-0 text-muted">Deadline: <strong>{{ $tugas->tanggal_deadline->format('l, d F Y, H:i') }}</strong>
                </p>
            </div>
            <a href="{{ route('siswa.pembelajaran.pertemuan', $tugas->pertemuan_id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Materi
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Instruksi Tugas -->
        <div class="col-md-7 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Instruksi Tugas</h5>
                </div>
                <div class="card-body">
                    <p>{{ $tugas->deskripsi }}</p>

                    @if ($tugas->instruksi)
                        <div class="alert alert-primary">
                            <h6><i class="bx bx-info-circle me-1"></i> Petunjuk Pengerjaan:</h6>
                            <p class="mb-0 small">{!! nl2br(e($tugas->instruksi)) !!}</p>
                        </div>
                    @endif

                    <hr>

                    <h6 class="text-muted small text-uppercase">Informasi Tambahan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-1"><i class="bx bx-file me-2"></i> Tipe Pengumpulan:
                            @if ($tugas->upload_file)
                                File
                            @endif
                            @if ($tugas->upload_file && $tugas->upload_link)
                                &
                            @endif
                            @if ($tugas->upload_link)
                                Link
                            @endif
                        </li>
                        <li class="mb-1"><i class="bx bx-time me-2"></i> Dibuka:
                            {{ $tugas->tanggal_mulai->format('d M Y, H:i') }}</li>
                        <li class="mb-1"><i class="bx bx-error-circle me-2"></i> Terlambat Diizinkan:
                            {{ $tugas->izinkan_terlambat ? 'Ya' : 'Tidak' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Status & Form Upload -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Status Pengumpulan</h5>
                </div>
                <div class="card-body pt-4">

                    <!-- Status Badge -->
                    <div class="d-flex justify-content-between mb-3">
                        <span>Status Pengumpulan</span>
                        @if ($pengumpulan)
                            @if ($pengumpulan->status == 'dinilai')
                                <span class="badge bg-success">Sudah Dinilai</span>
                            @elseif($pengumpulan->status == 'terlambat')
                                <span class="badge bg-danger">Terlambat</span>
                            @else
                                <span class="badge bg-info">Dikumpulkan</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Belum Mengumpulkan</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Nilai</span>
                        @if ($pengumpulan && $pengumpulan->nilai !== null)
                            <span class="fw-bold fs-5">{{ $pengumpulan->nilai }} / {{ $tugas->nilai_maksimal }}</span>
                        @else
                            <span>- / {{ $tugas->nilai_maksimal }}</span>
                        @endif
                    </div>

                    @if ($pengumpulan && $pengumpulan->komentar_guru)
                        <div class="alert alert-warning mb-3">
                            <strong>Feedback Guru:</strong><br>
                            {{ $pengumpulan->komentar_guru }}
                        </div>
                    @endif

                    <hr>

                    <!-- List File/Link yang sudah dikumpulkan -->
                    @if ($pengumpulan)
                        <h6 class="text-muted small">File Anda:</h6>
                        @foreach ($pengumpulan->filePengumpulanTugas as $file)
                            <div class="d-flex align-items-center mb-2 p-2 border rounded bg-light">
                                <i class="bx bxs-file-pdf me-2 text-warning"></i>
                                <div class="flex-grow-1 text-truncate">
                                    <small>{{ $file->file_name }}</small>
                                </div>
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="ms-2 text-primary"><i
                                        class="bx bx-download"></i></a>
                            </div>
                        @endforeach

                        @if ($pengumpulan->link_url)
                            <div class="d-flex align-items-center mb-2 p-2 border rounded bg-light">
                                <i class="bx bx-link me-2 text-info"></i>
                                <div class="flex-grow-1 text-truncate">
                                    <small><a href="{{ $pengumpulan->link_url }}"
                                            target="_blank">{{ $pengumpulan->link_url }}</a></small>
                                </div>
                            </div>
                        @endif

                        @if ($pengumpulan->status == 'dinilai')
                            <div class="alert alert-success mt-3 text-center">
                                Sudah dinilai, tidak dapat mengubah jawaban.
                            </div>
                        @else
                            <div class="alert alert-info mt-3 text-center small">
                                Anda sudah mengumpulkan. Mengirim ulang akan menimpa jawaban sebelumnya.
                            </div>
                        @endif
                    @endif

                    <!-- Form Upload -->
                    @if (!$pengumpulan || $pengumpulan->status != 'dinilai')
                        <form action="{{ route('siswa.tugas.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">

                            @if ($tugas->upload_file)
                                <div class="mb-3">
                                    <label class="form-label">Upload File</label>
                                    <input type="file" name="files[]" class="form-control" multiple>
                                    <small class="text-muted">Max 10MB per file.</small>
                                </div>
                            @endif

                            @if ($tugas->upload_link)
                                <div class="mb-3">
                                    <label class="form-label">Tautan Link</label>
                                    <input type="url" name="link_url" class="form-control" placeholder="https://..."
                                        value="{{ $pengumpulan->link_url ?? '' }}">
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary w-100 mt-2">
                                <i class="bx bx-send me-1"></i> {{ $pengumpulan ? 'Update Kerjaan' : 'Kumpulkan Tugas' }}
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
