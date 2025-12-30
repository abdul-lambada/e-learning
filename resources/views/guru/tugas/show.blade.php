@extends('layouts.app')

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
            <x-card title="Instruksi & Deskripsi Tugas">
                <div class="row">
                    <div class="col-md-7">
                        <div class="bg-light p-3 rounded-3 mb-3 border-start border-4 border-primary">
                            <h6 class="fw-bold mb-2">Deskripsi:</h6>
                            <p class="mb-0 text-dark">{{ $tugas->deskripsi }}</p>
                        </div>
                        @if ($tugas->instruksi)
                            <div class="bg-light p-3 rounded-3 border-start border-4 border-info">
                                <h6 class="fw-bold mb-2">Instruksi Detail:</h6>
                                <p class="mb-0 text-dark">{!! nl2br(e($tugas->instruksi)) !!}</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="150" class="text-muted">Status Tugas</td>
                                    <td>:
                                        @if ($tugas->aktif)
                                            <span class="badge bg-label-success">Aktif / Terlihat</span>
                                        @else
                                            <span class="badge bg-label-secondary">Hidden / Draft</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nilai Maksimal</td>
                                    <td>: <span class="fw-bold">{{ $tugas->nilai_maksimal }} Poin</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tipe Pengumpulan</td>
                                    <td>:
                                        @if ($tugas->upload_file)
                                            <span class="badge badge-dot bg-primary me-1"></span> File Upload
                                        @endif
                                        @if ($tugas->upload_link)
                                            <span class="badge badge-dot bg-info ms-2 me-1"></span> Link / URL
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Tabel Pengumpulan -->
        <div class="col-md-12">
            <x-card title="Daftar Pengumpulan Siswa">
                <x-slot name="headerAction">
                    <span class="badge bg-label-primary">Total Siswa: {{ $allSiswa->count() }}</span>
                </x-slot>

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
                                            <x-button size="sm" color="primary" data-bs-toggle="modal"
                                                data-bs-target="#modalNilai{{ $submission->id }}" icon="bx-check-circle">
                                                Nilai
                                            </x-button>

                                            <!-- Modal Nilai -->
                                            <div class="modal fade" id="modalNilai{{ $submission->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Beri Nilai: {{ $siswa->nama_lengkap }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('guru.tugas.nilai', $submission->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <x-input label="Nilai (0 - {{ $tugas->nilai_maksimal }})"
                                                                    type="number" name="nilai"
                                                                    placeholder="Masukkan nilai"
                                                                    value="{{ $submission->nilai }}"
                                                                    max="{{ $tugas->nilai_maksimal }}" required />

                                                                <x-textarea label="Komentar / Feedback" name="komentar_guru"
                                                                    rows="3"
                                                                    value="{{ $submission->komentar_guru }}" />
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <x-button type="submit">Simpan Nilai</x-button>
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
            </x-card>
        </div>
    </div>
@endsection
