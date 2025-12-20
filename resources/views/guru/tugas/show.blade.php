@extends('layouts.guru')

@section('title', 'Detail Tugas')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <span class="text-muted fw-light">Guru / Tugas /</span> {{ $tugas->judul_tugas }}
                </h4>
                <p class="mb-0 text-muted">
                    Deadline: {{ $tugas->tanggal_deadline->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('guru.tugas.edit', $tugas->id) }}" class="btn btn-warning">
                    <i class="bx bx-edit me-1"></i> Edit
                </a>
                <a href="{{ route('guru.pertemuan.show', $tugas->pertemuan_id) }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Info Tugas -->
        <div class="col-md-12 mb-4">
            <div class="card accordion-item active">
                <h2 class="accordion-header" id="headingOne">
                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne"
                        aria-expanded="true" aria-controls="accordionOne">
                        Detail Instruksi Tugas
                    </button>
                </h2>
                <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>Deskripsi:</strong>
                        <p>{{ $tugas->deskripsi }}</p>
                        @if ($tugas->instruksi)
                            <strong>Instruksi:</strong>
                            <p class="mb-0">{!! nl2br(e($tugas->instruksi)) !!}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pengumpulan -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pengumpulan Siswa</h5>
                    <span class="badge bg-label-primary">Total Siswa: {{ $allSiswa->count() }}</span>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Waktu Mengumpulkan</th>
                                <th>File/Link</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allSiswa as $siswa)
                                @php
                                    $submission = $tugas->pengumpulanTugas->where('siswa_id', $siswa->id)->first();
                                    $status = 'Belum Mengumpulkan';
                                    $badge = 'bg-label-secondary';

                                    if ($submission) {
                                        if ($submission->status == 'dinilai') {
                                            $status = 'Sudah Dinilai';
                                            $badge = 'bg-label-success';
                                        } elseif ($submission->status == 'terlambat') {
                                            $status = 'Terlambat';
                                            $badge = 'bg-label-danger';
                                        } else {
                                            $status = 'Menunggu Penilaian';
                                            $badge = 'bg-label-warning';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span
                                                    class="avatar-initial rounded-circle bg-label-dark">{{ substr($siswa->nama_lengkap, 0, 2) }}</span>
                                            </div>
                                            <strong>{{ $siswa->nama_lengkap }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $siswa->nis }}</small>
                                    </td>
                                    <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
                                    <td>
                                        @if ($submission && $submission->tanggal_dikumpulkan)
                                            {{ $submission->tanggal_dikumpulkan->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($submission)
                                            @foreach ($submission->filePengumpulanTugas as $file)
                                                <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="bx bx-download"></i> {{ Str::limit($file->file_name, 10) }}
                                                </a>
                                            @endforeach
                                            @if ($submission->link_tugas)
                                                <a href="{{ $submission->link_tugas }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="bx bx-link"></i> Link
                                                </a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($submission && $submission->nilai !== null)
                                            <span class="fw-bold text-dark">{{ $submission->nilai }}</span> /
                                            {{ $tugas->nilai_maksimal }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($submission)
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalNilai{{ $submission->id }}">
                                                <i class="bx bx-check-circle me-1"></i> Nilai
                                            </button>

                                            <!-- Modal Nilai -->
                                            <div class="modal fade" id="modalNilai{{ $submission->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Beri Nilai:
                                                                {{ $siswa->nama_lengkap }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('guru.tugas.nilai', $submission->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nilai" class="form-label">Nilai (0 -
                                                                            {{ $tugas->nilai_maksimal }})</label>
                                                                        <input type="number" id="nilai" name="nilai"
                                                                            class="form-control"
                                                                            placeholder="Masukkan nilai"
                                                                            value="{{ $submission->nilai }}"
                                                                            max="{{ $tugas->nilai_maksimal }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col mb-0">
                                                                        <label for="komentar_guru"
                                                                            class="form-label">Komentar / Feedback</label>
                                                                        <textarea name="komentar_guru" id="komentar_guru" class="form-control" rows="3">{{ $submission->komentar_guru }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary">Simpan
                                                                    Nilai</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Belum Kumpul</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($allSiswa->count() == 0)
                    <div class="p-4 text-center">
                        <p class="text-muted">Tidak ada siswa di kelas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
