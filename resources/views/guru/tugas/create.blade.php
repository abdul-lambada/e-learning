@extends('layouts.guru')

@section('title', 'Buat Tugas Baru')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Buat Tugas Baru pada Pertemuan: {{ $pertemuan->judul_pertemuan }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.tugas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="fw-bold text-primary">Informasi Tugas</h6>

                            <x-input label="Judul Tugas" name="judul_tugas" value="{{ old('judul_tugas') }}"
                                placeholder="Contoh: Latihan Soal Bab 1" required />

                            <x-textarea label="Deskripsi Tugas" name="deskripsi" rows="3"
                                placeholder="Jelaskan mengenai tugas ini..." required />

                            <x-textarea label="Instruksi Pengerjaan (Opsional)" name="instruksi" rows="3"
                                placeholder="Langkah-langkah detail pengerjaan..." />

                            <h6 class="fw-bold text-primary mt-4">Waktu Pengerjaan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input label="Tanggal Mulai (Dibuka)" type="datetime-local" name="tanggal_mulai"
                                        value="{{ old('tanggal_mulai', date('Y-m-d\TH:i')) }}" required />
                                </div>
                                <div class="col-md-6">
                                    <x-input label="Batas Waktu (Deadline)" type="datetime-local" name="tanggal_deadline"
                                        value="{{ old('tanggal_deadline', date('Y-m-d\TH:i', strtotime('+1 week'))) }}"
                                        required />
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

                                    <x-input label="Nilai Maksimal" type="number" name="nilai_maksimal" value="100"
                                        min="0" max="100" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan Tugas</x-button>
                        <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
