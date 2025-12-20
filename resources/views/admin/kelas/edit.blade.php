@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Kelas: {{ $kelas->nama_kelas }}</h5>
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Informasi Akademik</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="kode_kelas">Kode Kelas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_kelas') is-invalid @enderror"
                                        id="kode_kelas" name="kode_kelas"
                                        value="{{ old('kode_kelas', $kelas->kode_kelas) }}" required>
                                    @error('kode_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="nama_kelas">Nama Kelas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror"
                                        id="nama_kelas" name="nama_kelas"
                                        value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                                    @error('nama_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="tingkat">Tingkat <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('tingkat') is-invalid @enderror" id="tingkat"
                                        name="tingkat" required>
                                        <option value="">Pilih Tingkat</option>
                                        <option value="10"
                                            {{ old('tingkat', $kelas->tingkat) == '10' ? 'selected' : '' }}>Kelas 10
                                        </option>
                                        <option value="11"
                                            {{ old('tingkat', $kelas->tingkat) == '11' ? 'selected' : '' }}>Kelas 11
                                        </option>
                                        <option value="12"
                                            {{ old('tingkat', $kelas->tingkat) == '12' ? 'selected' : '' }}>Kelas 12
                                        </option>
                                    </select>
                                    @error('tingkat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="jurusan">Jurusan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jurusan') is-invalid @enderror"
                                        id="jurusan" name="jurusan" value="{{ old('jurusan', $kelas->jurusan) }}"
                                        required>
                                    @error('jurusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="aktif">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('aktif') is-invalid @enderror" id="aktif"
                                        name="aktif" required>
                                        <option value="1" {{ old('aktif', $kelas->aktif) == 1 ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="0" {{ old('aktif', $kelas->aktif) == 0 ? 'selected' : '' }}>
                                            Non-Aktif</option>
                                    </select>
                                    @error('aktif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Detail Lainnya</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="tahun_ajaran">Tahun Ajaran <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                                        id="tahun_ajaran" name="tahun_ajaran"
                                        value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" required>
                                    @error('tahun_ajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="wali_kelas_id">Wali Kelas <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('wali_kelas_id') is-invalid @enderror"
                                        id="wali_kelas_id" name="wali_kelas_id" required>
                                        <option value="">Pilih Wali Kelas</option>
                                        @foreach ($gurus as $guru)
                                            <option value="{{ $guru->id }}"
                                                {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->nama_lengkap }} ({{ $guru->nip ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('wali_kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                        rows="3">{{ old('keterangan', $kelas->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
