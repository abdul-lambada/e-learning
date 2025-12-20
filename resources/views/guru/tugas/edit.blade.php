@extends('layouts.guru')

@section('title', 'Edit Tugas')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Tugas: {{ $tugas->judul_tugas }}</h5>
                    <a href="{{ route('guru.pertemuan.show', $tugas->pertemuan_id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.tugas.update', $tugas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold text-primary">Informasi Tugas</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="judul_tugas">Judul Tugas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul_tugas') is-invalid @enderror"
                                        id="judul_tugas" name="judul_tugas"
                                        value="{{ old('judul_tugas', $tugas->judul_tugas) }}" required>
                                    @error('judul_tugas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi Tugas <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                        required>{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="instruksi">Instruksi Pengerjaan (Opsional)</label>
                                    <textarea class="form-control @error('instruksi') is-invalid @enderror" id="instruksi" name="instruksi" rows="3">{{ old('instruksi', $tugas->instruksi) }}</textarea>
                                    @error('instruksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h6 class="fw-bold text-primary mt-4">Waktu Pengerjaan</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai (Dibuka) <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetime-local"
                                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                                id="tanggal_mulai" name="tanggal_mulai"
                                                value="{{ old('tanggal_mulai', $tugas->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                                                required>
                                            @error('tanggal_mulai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="tanggal_deadline">Batas Waktu (Deadline) <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetime-local"
                                                class="form-control @error('tanggal_deadline') is-invalid @enderror"
                                                id="tanggal_deadline" name="tanggal_deadline"
                                                value="{{ old('tanggal_deadline', $tugas->tanggal_deadline->format('Y-m-d\TH:i')) }}"
                                                required>
                                            @error('tanggal_deadline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <h6 class="fw-bold text-primary">Pengaturan Pengumpulan</h6>

                                <div class="card bg-label-secondary mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label d-block">Tipe Pengumpulan</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="active_upload_file"
                                                    name="active_upload_file" value="1"
                                                    {{ $tugas->upload_file ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active_upload_file">
                                                    Upload File
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="active_upload_link"
                                                    name="active_upload_link" value="1"
                                                    {{ $tugas->upload_link ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active_upload_link">
                                                    Tautan / Link Eksternal
                                                </label>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <label class="form-label" for="nilai_maksimal">Nilai Maksimal</label>
                                            <input type="number" class="form-control" id="nilai_maksimal"
                                                name="nilai_maksimal"
                                                value="{{ old('nilai_maksimal', $tugas->nilai_maksimal) }}" min="0"
                                                max="100">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="aktif">Status Tugas</label>
                                            <select class="form-select" id="aktif" name="aktif">
                                                <option value="1" {{ $tugas->aktif ? 'selected' : '' }}>Aktif
                                                </option>
                                                <option value="0" {{ !$tugas->aktif ? 'selected' : '' }}>Sembunyikan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update Tugas
                            </button>
                            <a href="{{ route('guru.pertemuan.show', $tugas->pertemuan_id) }}"
                                class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
