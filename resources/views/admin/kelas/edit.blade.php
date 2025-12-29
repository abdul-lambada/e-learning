@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Kelas: {{ $kelas->nama_kelas }}">
                <x-slot name="headerAction">
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Akademik</h6>

                            <x-input label="Kode Kelas" name="kode_kelas"
                                value="{{ old('kode_kelas', $kelas->kode_kelas) }}" required />

                            <x-input label="Nama Kelas" name="nama_kelas"
                                value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required />

                            <x-select label="Tingkat" name="tingkat" required>
                                <option value="">Pilih Tingkat</option>
                                <option value="10" {{ old('tingkat', $kelas->tingkat) == '10' ? 'selected' : '' }}>Kelas
                                    10</option>
                                <option value="11" {{ old('tingkat', $kelas->tingkat) == '11' ? 'selected' : '' }}>Kelas
                                    11</option>
                                <option value="12" {{ old('tingkat', $kelas->tingkat) == '12' ? 'selected' : '' }}>Kelas
                                    12</option>
                            </x-select>

                            <x-input label="Jurusan" name="jurusan" value="{{ old('jurusan', $kelas->jurusan) }}"
                                required />

                            <x-select label="Status" name="aktif" required>
                                <option value="1" {{ old('aktif', $kelas->aktif) == 1 ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="0" {{ old('aktif', $kelas->aktif) == 0 ? 'selected' : '' }}>Non-Aktif
                                </option>
                            </x-select>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Detail Lainnya</h6>

                            <x-input label="Tahun Ajaran" name="tahun_ajaran"
                                value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" required />



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
                        <x-button type="submit" icon="bx-save">Update</x-button>
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
