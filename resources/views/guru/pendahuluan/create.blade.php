@extends('layouts.app')
@section('title', 'Tambah Pendahuluan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Tambah Item Pendahuluan - {{ $mataPelajaran->nama_mapel }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pendahuluan.show', $mataPelajaran->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Batal
                    </a>
                </x-slot>

                <form action="{{ route('guru.pendahuluan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">

                    <x-input label="Judul" name="judul" placeholder="Contoh: Silabus, Kontrak Belajar" required />

                    <x-textarea label="Isi / Konten" name="konten" rows="5"
                        placeholder="Jelaskan detail pendahuluan..." required />

                    <div class="mb-3">
                        <label class="form-label">File Pendukung (Opsional)</label>
                        <input type="file" class="form-control" name="file_pendukung">
                        <div class="form-text">PDF, Docx, pptx max 10MB.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Estimasi Durasi (Menit)" type="number" name="durasi_estimasi" value="10" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Urutan Tampil" type="number" name="urutan" value="1" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="wajib_diselesaikan" id="wajib" checked
                                value="1">
                            <label class="form-check-label" for="wajib">Wajib Diselesaikan Siswa (Harus
                                dibaca/download)</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif" checked
                                value="1">
                            <label class="form-check-label" for="aktif">Aktifkan Sekarang</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan</x-button>
                        <a href="{{ route('guru.pendahuluan.show', $mataPelajaran->id) }}"
                            class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
