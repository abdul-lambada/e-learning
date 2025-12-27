@extends('layouts.guru')

@section('title', 'Edit Komponen Nilai')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Edit Komponen Nilai</h4>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Form Edit Bobot Nilai</h5>
                        <small class="text-muted float-end">{{ $komponen_nilai->mataPelajaran->nama_mapel }}</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('guru.komponen-nilai.update', $komponen_nilai->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Tahun Ajaran</label>
                                <input type="text" class="form-control" value="{{ $komponen_nilai->tahun_ajaran }}"
                                    readonly disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Semester</label>
                                <input type="text" class="form-control" value="{{ ucfirst($komponen_nilai->semester) }}"
                                    readonly disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bobot Pendahuluan (%)</label>
                                <input type="number" name="bobot_pendahuluan" class="form-control"
                                    value="{{ old('bobot_pendahuluan', $komponen_nilai->bobot_pendahuluan) }}" required
                                    min="0" max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bobot Absensi (%)</label>
                                <input type="number" name="bobot_absensi" class="form-control"
                                    value="{{ old('bobot_absensi', $komponen_nilai->bobot_absensi) }}" required
                                    min="0" max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bobot Tugas (%)</label>
                                <input type="number" name="bobot_tugas" class="form-control"
                                    value="{{ old('bobot_tugas', $komponen_nilai->bobot_tugas) }}" required min="0"
                                    max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bobot Kuis (%)</label>
                                <input type="number" name="bobot_kuis" class="form-control"
                                    value="{{ old('bobot_kuis', $komponen_nilai->bobot_kuis) }}" required min="0"
                                    max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bobot Ujian (%)</label>
                                <input type="number" name="bobot_ujian" class="form-control"
                                    value="{{ old('bobot_ujian', $komponen_nilai->bobot_ujian) }}" required min="0"
                                    max="100">
                            </div>
                            <div class="mb-3 border-top pt-3">
                                <label class="form-label fw-bold">KKM</label>
                                <input type="number" name="kkm" class="form-control"
                                    value="{{ old('kkm', $komponen_nilai->kkm) }}" required min="0" max="100">
                                <small class="text-muted">Kriteria Ketuntasan Minimal</small>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                                <a href="{{ route('guru.komponen-nilai.index') }}"
                                    class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-label-info">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Bobot</h5>
                        <p class="card-text">
                            Total seluruh bobot (Pendahuluan + Absensi + Tugas + Kuis + Ujian) harus berjumlah
                            <strong>100%</strong>.
                        </p>
                        <p class="card-text">
                            Perubahan bobot akan langsung mempengaruhi kalkulasi <strong>Nilai Akhir</strong> pada laporan
                            nilai siswa.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
