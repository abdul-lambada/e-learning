@extends('layouts.app')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Jadwal Pelajaran Baru</h5>
                    <a href="{{ route('admin.jadwal-pelajaran.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jadwal-pelajaran.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Informasi Kelas & Mata Pelajaran</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="kelas_id">Kelas <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id"
                                        name="kelas_id" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}"
                                                {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="mata_pelajaran_id">Mata Pelajaran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('mata_pelajaran_id') is-invalid @enderror"
                                        id="mata_pelajaran_id" name="mata_pelajaran_id" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach ($mapel as $m)
                                            <option value="{{ $m->id }}"
                                                {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mata_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="guru_id">Guru Pengajar <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('guru_id') is-invalid @enderror" id="guru_id"
                                        name="guru_id" required>
                                        <option value="">Pilih Guru</option>
                                        @foreach ($gurus as $g)
                                            <option value="{{ $g->id }}"
                                                {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                                {{ $g->nama_lengkap }} ({{ $g->nip ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guru_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="semester">Semester <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('semester') is-invalid @enderror"
                                                id="semester" name="semester" required>
                                                <option value="">Pilih Semester</option>
                                                <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>
                                                    Ganjil</option>
                                                <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>
                                                    Genap</option>
                                            </select>
                                            @error('semester')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="tahun_ajaran">Tahun Ajaran <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('tahun_ajaran') is-invalid @enderror"
                                                id="tahun_ajaran" name="tahun_ajaran"
                                                value="{{ old('tahun_ajaran', '2024/2025') }}" required>
                                            @error('tahun_ajaran')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Waktu & Jadwal</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="hari">Hari <span class="text-danger">*</span></label>
                                    <select class="form-select @error('hari') is-invalid @enderror" id="hari"
                                        name="hari" required>
                                        <option value="">Pilih Hari</option>
                                        <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin
                                        </option>
                                        <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa
                                        </option>
                                        <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                        <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis
                                        </option>
                                        <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat
                                        </option>
                                        <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu
                                        </option>
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="jam_mulai">Jam Mulai <span
                                                    class="text-danger">*</span></label>
                                            <input type="time"
                                                class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai"
                                                name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                            @error('jam_mulai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="jam_selesai">Jam Selesai <span
                                                    class="text-danger">*</span></label>
                                            <input type="time"
                                                class="form-control @error('jam_selesai') is-invalid @enderror"
                                                id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}"
                                                required>
                                            @error('jam_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-info-circle"></i> Info</h6>
                                    <p class="mb-0 small">Sistem akan otomatis mengecek bentrok jadwal (Hari & Jam yang
                                        sama) untuk Guru dan Kelas yang dipilih.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.jadwal-pelajaran.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
