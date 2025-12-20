@extends('layouts.guru')
@section('title', 'Tambah Pendahuluan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pendahuluan /</span> Tambah</h4>

        <div class="card mb-4">
            <h5 class="card-header">Tambah Item Pendahuluan - {{ $mataPelajaran->nama_mapel }}</h5>
            <div class="card-body">
                <form action="{{ route('guru.pendahuluan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" required
                            placeholder="Contoh: Silabus, Kontrak Belajar">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Isi / Konten</label>
                        <textarea class="form-control" name="konten" rows="5" required placeholder="Jelaskan detail pendahuluan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Pendukung (Opsional)</label>
                        <input type="file" class="form-control" name="file_pendukung">
                        <div class="form-text">PDF, Docx, pptx max 10MB.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estimasi Durasi (Menit)</label>
                            <input type="number" class="form-control" name="durasi_estimasi" value="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" class="form-control" name="urutan" value="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="wajib_diselesaikan" id="wajib"
                                checked>
                            <label class="form-check-label" for="wajib">Wajib Diselesaikan Siswa (Harus
                                dibaca/download)</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif" checked>
                            <label class="form-check-label" for="aktif">Aktifkan Sekarang</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('guru.pendahuluan.show', $mataPelajaran->id) }}"
                        class="btn btn-label-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
