@extends('layouts.guru')

@section('title', 'Edit Pertemuan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pertemuan: {{ $pertemuan->judul_pertemuan }}</h5>
                    <a href="{{ route('guru.jadwal.show', $pertemuan->guru_mengajar_id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.pertemuan.update', $pertemuan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Informasi Pertemuan</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="judul_pertemuan">Topik / Judul Pertemuan <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('judul_pertemuan') is-invalid @enderror"
                                        id="judul_pertemuan" name="judul_pertemuan"
                                        value="{{ old('judul_pertemuan', $pertemuan->judul_pertemuan) }}" required>
                                    @error('judul_pertemuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi / Tujuan Pembelajaran</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $pertemuan->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="aktif">Status Pertemuan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('aktif') is-invalid @enderror" id="aktif"
                                        name="aktif" required>
                                        <option value="1" {{ old('aktif', $pertemuan->aktif) == 1 ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="0" {{ old('aktif', $pertemuan->aktif) == 0 ? 'selected' : '' }}>
                                            Non-Aktif (Sembunyikan)</option>
                                    </select>
                                    @error('aktif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Waktu & Tanggal</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="tanggal_pertemuan">Tanggal <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pertemuan') is-invalid @enderror"
                                        id="tanggal_pertemuan" name="tanggal_pertemuan"
                                        value="{{ old('tanggal_pertemuan', $pertemuan->tanggal_pertemuan->format('Y-m-d')) }}"
                                        required>
                                    @error('tanggal_pertemuan')
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
                                                name="jam_mulai"
                                                value="{{ old('jam_mulai', $pertemuan->jam_mulai->format('H:i')) }}"
                                                required>
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
                                                id="jam_selesai" name="jam_selesai"
                                                value="{{ old('jam_selesai', $pertemuan->jam_selesai->format('H:i')) }}"
                                                required>
                                            @error('jam_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="status">Status Pelaksanaan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="dijadwalkan"
                                            {{ old('status', $pertemuan->status) == 'dijadwalkan' ? 'selected' : '' }}>
                                            Dijadwalkan</option>
                                        <option value="berlangsung"
                                            {{ old('status', $pertemuan->status) == 'berlangsung' ? 'selected' : '' }}>
                                            Berlangsung</option>
                                        <option value="selesai"
                                            {{ old('status', $pertemuan->status) == 'selesai' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('guru.jadwal.show', $pertemuan->guru_mengajar_id) }}"
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
