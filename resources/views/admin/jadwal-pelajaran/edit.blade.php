@extends('layouts.app')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Jadwal Pelajaran</h5>
                    <a href="{{ route('admin.jadwal-pelajaran.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jadwal-pelajaran.update', $jadwalPelajaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                                {{ old('kelas_id', $jadwalPelajaran->kelas_id) == $k->id ? 'selected' : '' }}>
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
                                                {{ old('mata_pelajaran_id', $jadwalPelajaran->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>
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
                                                {{ old('guru_id', $jadwalPelajaran->guru_id) == $g->id ? 'selected' : '' }}>
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
                                                <option value="Ganjil"
                                                    {{ old('semester', $jadwalPelajaran->semester) == 'Ganjil' ? 'selected' : '' }}>
                                                    Ganjil</option>
                                                <option value="Genap"
                                                    {{ old('semester', $jadwalPelajaran->semester) == 'Genap' ? 'selected' : '' }}>
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
                                                value="{{ old('tahun_ajaran', $jadwalPelajaran->tahun_ajaran) }}" required>
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
                                        <option value="Senin"
                                            {{ old('hari', $jadwalPelajaran->hari) == 'Senin' ? 'selected' : '' }}>Senin
                                        </option>
                                        <option value="Selasa"
                                            {{ old('hari', $jadwalPelajaran->hari) == 'Selasa' ? 'selected' : '' }}>Selasa
                                        </option>
                                        <option value="Rabu"
                                            {{ old('hari', $jadwalPelajaran->hari) == 'Rabu' ? 'selected' : '' }}>Rabu
                                        </option>
                                        <option value="Kamis"
                                            {{ old('hari', $jadwalPelajaran->hari) == 'Kamis' ? 'selected' : '' }}>Kamis
                                        </option>
                                        <option value="Jumat"
                                            {{ old('hari', $jadwalPelajaran->hari) == 'Jumat' ? 'selected' : '' }}>Jumat
                                        </option>
                                        <option value="Sabtu"
                                            {{ old('hari', $jadwalPelajaran->hari) == 'Sabtu' ? 'selected' : '' }}>Sabtu
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
                                                name="jam_mulai"
                                                value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwalPelajaran->jam_mulai)->format('H:i')) }}"
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
                                                value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwalPelajaran->jam_selesai)->format('H:i')) }}"
                                                required>
                                            @error('jam_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
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
