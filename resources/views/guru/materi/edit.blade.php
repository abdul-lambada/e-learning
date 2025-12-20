@extends('layouts.guru')

@section('title', 'Edit Materi Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Materi: {{ $materi->judul_materi }}</h5>
                    <a href="{{ route('guru.pertemuan.show', $materi->pertemuan_id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.materi.update', $materi->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="judul_materi">Judul Materi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul_materi') is-invalid @enderror"
                                        id="judul_materi" name="judul_materi"
                                        value="{{ old('judul_materi', $materi->judul_materi) }}" required>
                                    @error('judul_materi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi Singkat</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="tipe_materi">Tipe Materi <span
                                            class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content" for="tipeFile">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-file"></i>
                                                        <span class="custom-option-title">File</span>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="file" id="tipeFile"
                                                        {{ old('tipe_materi', $materi->tipe_materi) == 'file' ? 'checked' : '' }}
                                                        onchange="toggleMateriInput()">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content" for="tipeVideo">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-video"></i>
                                                        <span class="custom-option-title">Video</span>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="video" id="tipeVideo"
                                                        {{ old('tipe_materi', $materi->tipe_materi) == 'video' ? 'checked' : '' }}
                                                        onchange="toggleMateriInput()">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content" for="tipeLink">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-link"></i>
                                                        <span class="custom-option-title">Link</span>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="link" id="tipeLink"
                                                        {{ old('tipe_materi', $materi->tipe_materi) == 'link' ? 'checked' : '' }}
                                                        onchange="toggleMateriInput()">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content" for="tipeTeks">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-text"></i>
                                                        <span class="custom-option-title">Teks</span>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="teks" id="tipeTeks"
                                                        {{ old('tipe_materi', $materi->tipe_materi) == 'teks' ? 'checked' : '' }}
                                                        onchange="toggleMateriInput()">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic Inputs -->
                                <div id="input-file" class="d-none dynamic-input">
                                    <div class="mb-3">
                                        <label class="form-label" for="file_materi">Upload File Baru</label>
                                        <input type="file"
                                            class="form-control @error('file_materi') is-invalid @enderror" id="file_materi"
                                            name="file_materi">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file. File saat
                                            ini: {{ $materi->file_name }}</small>
                                        @error('file_materi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="dapat_diunduh"
                                            name="dapat_diunduh" value="1"
                                            {{ old('dapat_diunduh', $materi->dapat_diunduh) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dapat_diunduh">Izinkan siswa mengunduh file
                                            ini</label>
                                    </div>
                                </div>

                                <div id="input-video" class="d-none dynamic-input">
                                    <div class="mb-3">
                                        <label class="form-label" for="video_url">URL Video (YouTube) <span
                                                class="text-danger">*</span></label>
                                        <input type="url"
                                            class="form-control @error('video_url') is-invalid @enderror" id="video_url"
                                            name="video_url" value="{{ old('video_url', $materi->video_url) }}">
                                        @error('video_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="input-link" class="d-none dynamic-input">
                                    <div class="mb-3">
                                        <label class="form-label" for="link_url">URL Link <span
                                                class="text-danger">*</span></label>
                                        <input type="url" class="form-control @error('link_url') is-invalid @enderror"
                                            id="link_url" name="link_url"
                                            value="{{ old('link_url', $materi->link_url) }}">
                                        @error('link_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="input-teks" class="d-none dynamic-input">
                                    <div class="mb-3">
                                        <label class="form-label" for="konten">Konten Bacaan <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('konten') is-invalid @enderror" id="konten" name="konten" rows="10">{{ old('konten', $materi->konten) }}</textarea>
                                        @error('konten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="aktif">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('aktif') is-invalid @enderror" id="aktif"
                                        name="aktif" required>
                                        <option value="1" {{ old('aktif', $materi->aktif) == 1 ? 'selected' : '' }}>
                                            Aktif (Ditampilkan)</option>
                                        <option value="0" {{ old('aktif', $materi->aktif) == 0 ? 'selected' : '' }}>
                                            Sembunyikan</option>
                                    </select>
                                    @error('aktif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update Materi
                            </button>
                            <a href="{{ route('guru.pertemuan.show', $materi->pertemuan_id) }}"
                                class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleMateriInput() {
                document.querySelectorAll('.dynamic-input').forEach(el => el.classList.add('d-none'));
                const selected = document.querySelector('input[name="tipe_materi"]:checked');
                if (selected) {
                    const type = selected.value;
                    const target = document.getElementById('input-' + type);
                    if (target) {
                        target.classList.remove('d-none');
                    }
                }
            }
            document.addEventListener('DOMContentLoaded', toggleMateriInput);
        </script>
    @endpush
@endsection
