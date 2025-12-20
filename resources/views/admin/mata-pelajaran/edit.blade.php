@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Mata Pelajaran: {{ $mataPelajaran->nama_mapel }}</h5>
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-3 text-primary">Informasi Utama</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="kode_mapel">Kode Mapel <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('kode_mapel') is-invalid @enderror"
                                                id="kode_mapel" name="kode_mapel"
                                                value="{{ old('kode_mapel', $mataPelajaran->kode_mapel) }}" required>
                                            @error('kode_mapel')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="nama_mapel">Nama Mata Pelajaran <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('nama_mapel') is-invalid @enderror"
                                                id="nama_mapel" name="nama_mapel"
                                                value="{{ old('nama_mapel', $mataPelajaran->nama_mapel) }}" required>
                                            @error('nama_mapel')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="jumlah_pertemuan">Target Pertemuan <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('jumlah_pertemuan') is-invalid @enderror"
                                                id="jumlah_pertemuan" name="jumlah_pertemuan"
                                                value="{{ old('jumlah_pertemuan', $mataPelajaran->jumlah_pertemuan) }}"
                                                min="1" required>
                                            @error('jumlah_pertemuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="durasi_pertemuan">Durasi (Menit) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('durasi_pertemuan') is-invalid @enderror"
                                                id="durasi_pertemuan" name="durasi_pertemuan"
                                                value="{{ old('durasi_pertemuan', $mataPelajaran->durasi_pertemuan) }}"
                                                min="1" required>
                                            @error('durasi_pertemuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $mataPelajaran->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="aktif">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('aktif') is-invalid @enderror" id="aktif"
                                        name="aktif" required>
                                        <option value="1"
                                            {{ old('aktif', $mataPelajaran->aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0"
                                            {{ old('aktif', $mataPelajaran->aktif) == 0 ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                    </select>
                                    @error('aktif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h6 class="mb-3 text-primary">Cover Image</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="gambar_cover">Upload Gambar Baru</label>
                                    <input type="file" class="form-control @error('gambar_cover') is-invalid @enderror"
                                        id="gambar_cover" name="gambar_cover" accept="image/*"
                                        onchange="previewImage(this)">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                                    @error('gambar_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 text-center">
                                    <img id="preview"
                                        src="{{ $mataPelajaran->gambar_cover ? Storage::url($mataPelajaran->gambar_cover) : '/sneat-1.0.0/sneat-1.0.0/assets/img/layouts/layout-container-light.png' }}"
                                        alt="Preview" class="img-fluid rounded border p-1" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
