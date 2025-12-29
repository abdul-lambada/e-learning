@extends('layouts.guru')

@section('title', 'Edit Tugas')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Tugas: {{ $tugas->judul_tugas }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pertemuan.show', $tugas->pertemuan_id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.tugas.update', $tugas->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="fw-bold text-primary">Informasi Tugas</h6>

                            <x-input label="Judul Tugas" name="judul_tugas"
                                value="{{ old('judul_tugas', $tugas->judul_tugas) }}" required />

                            <x-textarea label="Deskripsi Tugas" name="deskripsi" rows="3" required
                                value="{{ old('deskripsi', $tugas->deskripsi) }}" />

                            <x-textarea label="Instruksi Pengerjaan (Opsional)" name="instruksi" rows="3"
                                value="{{ old('instruksi', $tugas->instruksi) }}" />

                            <h6 class="fw-bold text-primary mt-4">Waktu Pengerjaan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-input label="Tanggal Mulai (Dibuka)" type="datetime-local" name="tanggal_mulai"
                                        value="{{ old('tanggal_mulai', $tugas->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-input label="Batas Waktu (Deadline)" type="datetime-local" name="tanggal_deadline"
                                        value="{{ old('tanggal_deadline', $tugas->tanggal_deadline->format('Y-m-d\TH:i')) }}"
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

                                    <x-input label="Nilai Maksimal" type="number" name="nilai_maksimal"
                                        value="{{ old('nilai_maksimal', $tugas->nilai_maksimal) }}" min="0"
                                        max="100" />

                                    <x-select label="Status Tugas" name="aktif">
                                        <option value="1" {{ $tugas->aktif ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ !$tugas->aktif ? 'selected' : '' }}>Sembunyikan</option>
                                    </x-select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Update Tugas</x-button>
                        <a href="{{ route('guru.pertemuan.show', $tugas->pertemuan_id) }}"
                            class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
