@extends('layouts.guru')

@section('title', 'Buat Tugas Baru')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Buat Tugas Baru pada Pertemuan: {{ $pertemuan->judul_pertemuan }}</h5>
                    <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.tugas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold text-primary">Informasi Tugas</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="judul_tugas">Judul Tugas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul_tugas') is-invalid @enderror"
                                        id="judul_tugas" name="judul_tugas" value="{{ old('judul_tugas') }}"
                                        placeholder="Contoh: Latihan Soal Bab 1" required>
                                    @error('judul_tugas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi Tugas <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                        placeholder="Jelaskan mengenai tugas ini..." required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="instruksi">Instruksi Pengerjaan (Opsional)</label>
                                    <textarea class="form-control @error('instruksi') is-invalid @enderror" id="instruksi" name="instruksi" rows="3"
                                        placeholder="Langkah-langkah detail pengerjaan...">{{ old('instruksi') }}</textarea>
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
                                                value="{{ old('tanggal_mulai', date('Y-m-d\TH:i')) }}" required>
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
                                                value="{{ old('tanggal_deadline', date('Y-m-d\TH:i', strtotime('+1 week'))) }}"
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
                                                    name="active_upload_file" value="1" checked>
                                                <label class="form-check-label" for="active_upload_file">
                                                    Upload File (PDF/DOC/Image)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="active_upload_link"
                                                    name="active_upload_link" value="1">
                                                <label class="form-check-label" for="active_upload_link">
                                                    Tautan / Link Eksternal
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-2">Pilih minimal satu.</small>
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <label class="form-label" for="nilai_maksimal">Nilai Maksimal</label>
                                            <input type="number" class="form-control" id="nilai_maksimal"
                                                name="nilai_maksimal" value="100" min="0" max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Tugas
                            </button>
                            <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}"
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
