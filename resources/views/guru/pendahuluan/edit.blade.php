@extends('layouts.guru')
@section('title', 'Edit Pendahuluan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Item Pendahuluan">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pendahuluan.show', $pendahuluan->mata_pelajaran_id) }}"
                        class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Batal
                    </a>
                </x-slot>

                <form action="{{ route('guru.pendahuluan.update', $pendahuluan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <x-input label="Judul" name="judul" value="{{ $pendahuluan->judul }}" required />

                    <x-textarea label="Isi / Konten" name="konten" rows="5" value="{{ $pendahuluan->konten }}"
                        required />

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
                        <div class="col-md-6">
                            <x-input label="Estimasi Durasi (Menit)" type="number" name="durasi_estimasi"
                                value="{{ $pendahuluan->durasi_estimasi }}" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Urutan Tampil" type="number" name="urutan" value="{{ $pendahuluan->urutan }}"
                                required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="wajib_diselesaikan" id="wajib"
                                {{ $pendahuluan->wajib_diselesaikan ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="wajib">Wajib Diselesaikan Siswa</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif"
                                {{ $pendahuluan->aktif ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="aktif">Aktifkan</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan Perubahan</x-button>
                        <a href="{{ route('guru.pendahuluan.show', $pendahuluan->mata_pelajaran_id) }}"
                            class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
