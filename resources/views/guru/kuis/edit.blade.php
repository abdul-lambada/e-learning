@extends('layouts.guru')

@section('title', 'Edit Kuis')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kuis /</span> Edit Kuis</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Edit Formulir Kuis</h5>
                    <div class="card-body">
                        <form action="{{ route('guru.kuis.update', $kuis->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="judul_kuis" class="form-label">Judul Kuis</label>
                                <input type="text" class="form-control" id="judul_kuis" name="judul_kuis"
                                    value="{{ $kuis->judul_kuis }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $kuis->deskripsi }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="instruksi" class="form-label">Instruksi Pengerjaan</label>
                                <textarea class="form-control" id="instruksi" name="instruksi" rows="4">{{ $kuis->instruksi }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai Akses</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_mulai"
                                        name="tanggal_mulai"
                                        value="{{ \Carbon\Carbon::parse($kuis->tanggal_mulai)->format('Y-m-d\TH:i') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai (Batas Akhir)</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_selesai"
                                        name="tanggal_selesai"
                                        value="{{ \Carbon\Carbon::parse($kuis->tanggal_selesai)->format('Y-m-d\TH:i') }}"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="durasi_menit" class="form-label">Durasi (Menit)</label>
                                    <input type="number" class="form-control" id="durasi_menit" name="durasi_menit"
                                        value="{{ $kuis->durasi_menit }}" required min="1">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nilai_maksimal" class="form-label">Nilai Maksimal</label>
                                    <input type="number" class="form-control" id="nilai_maksimal" name="nilai_maksimal"
                                        value="{{ $kuis->nilai_maksimal }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nilai_minimal_lulus" class="form-label">KKM / Passing Grade</label>
                                    <input type="number" class="form-control" id="nilai_minimal_lulus"
                                        name="nilai_minimal_lulus" value="{{ $kuis->nilai_minimal_lulus }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="max_percobaan" class="form-label">Maksimal Percobaan</label>
                                    <input type="number" class="form-control" id="max_percobaan" name="max_percobaan"
                                        value="{{ $kuis->max_percobaan }}" required min="1">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Pengaturan Tambahan</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="aktif" name="aktif"
                                        value="1" {{ $kuis->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aktif">Kuis Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="acak_soal" name="acak_soal"
                                        value="1" {{ $kuis->acak_soal ? 'checked' : '' }}>
                                    <label class="form-check-label" for="acak_soal">Acak Soal</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="acak_jawaban"
                                        name="acak_jawaban" value="1" {{ $kuis->acak_jawaban ? 'checked' : '' }}>
                                    <label class="form-check-label" for="acak_jawaban">Acak Jawaban (Pilihan
                                        Ganda)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="tampilkan_timer"
                                        name="tampilkan_timer" value="1"
                                        {{ $kuis->tampilkan_timer ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tampilkan_timer">Tampilkan Timer</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="izinkan_kembali"
                                        name="izinkan_kembali" value="1"
                                        {{ $kuis->izinkan_kembali ? 'checked' : '' }}>
                                    <label class="form-check-label" for="izinkan_kembali">Izinkan Kembali ke Soal
                                        Sebelumnya</label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('guru.kuis.show', $kuis->id) }}"
                                    class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
