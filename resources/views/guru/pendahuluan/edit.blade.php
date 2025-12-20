@extends('layouts.guru')
@section('title', 'Edit Pendahuluan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pendahuluan /</span> Edit</h4>

        <div class="card mb-4">
            <h5 class="card-header">Edit Item Pendahuluan</h5>
            <div class="card-body">
                <form action="{{ route('guru.pendahuluan.update', $pendahuluan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" value="{{ $pendahuluan->judul }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Isi / Konten</label>
                        <textarea class="form-control" name="konten" rows="5" required>{{ $pendahuluan->konten }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Pendukung (Opsional)</label>
                        @if ($pendahuluan->file_pendukung)
                            <div class="mb-2">
                                <a href="{{ Storage::url($pendahuluan->file_pendukung) }}" target="_blank"
                                    class="badge bg-label-primary">Lihat File Saat Ini</a>
                            </div>
                        @endif
                        <input type="file" class="form-control" name="file_pendukung">
                        <div class="form-text">Upload file baru untuk mengganti yang lama.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estimasi Durasi (Menit)</label>
                            <input type="number" class="form-control" name="durasi_estimasi"
                                value="{{ $pendahuluan->durasi_estimasi }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" class="form-control" name="urutan" value="{{ $pendahuluan->urutan }}"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="wajib_diselesaikan" id="wajib"
                                {{ $pendahuluan->wajib_diselesaikan ? 'checked' : '' }}>
                            <label class="form-check-label" for="wajib">Wajib Diselesaikan Siswa</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif"
                                {{ $pendahuluan->aktif ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">Aktifkan</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('guru.pendahuluan.show', $pendahuluan->mata_pelajaran_id) }}"
                        class="btn btn-label-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
