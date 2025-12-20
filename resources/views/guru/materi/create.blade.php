@extends('layouts.guru')

@section('title', 'Tambah Materi Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Materi: {{ $pertemuan->judul_pertemuan }}</h5>
                    <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="judul_materi">Judul Materi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul_materi') is-invalid @enderror"
                                        id="judul_materi" name="judul_materi" value="{{ old('judul_materi') }}"
                                        placeholder="Contoh: Slide Presentasi Bab 1" required>
                                    @error('judul_materi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi Singkat</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="2"
                                        placeholder="Penjelasan singkat tentang materi ini...">{{ old('deskripsi') }}</textarea>
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
                                                        <small>PDF,</small>
                                                        <small>PPT,</small>
                                                        <small>DOC</small>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="file" id="tipeFile"
                                                        {{ old('tipe_materi') == 'file' ? 'checked' : '' }}
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
                                                        <small>YouTube</small>
                                                        <small>video</small>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="video" id="tipeVideo"
                                                        {{ old('tipe_materi') == 'video' ? 'checked' : '' }}
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
                                                        <small>Website</small>
                                                        <small>External</small>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="link" id="tipeLink"
                                                        {{ old('tipe_materi') == 'link' ? 'checked' : '' }}
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
                                                        <small>Bacaan</small>
                                                        <small>Langsung</small>
                                                    </span>
                                                    <input name="tipe_materi" class="form-check-input" type="radio"
                                                        value="teks" id="tipeTeks"
                                                        {{ old('tipe_materi') == 'teks' ? 'checked' : '' }}
                                                        onchange="toggleMateriInput()">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('tipe_materi')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dynamic Inputs -->
                                <div id="input-file" class="d-none dynamic-input">
                                    <div class="mb-3">
                                        <label class="form-label" for="file_materi">Upload File <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('file_materi') is-invalid @enderror"
                                            id="file_materi" name="file_materi">
                                        <small class="text-muted">Max: 10MB. Format: PDF, DOCX, PPTX, JPG, PNG.</small>
                                        @error('file_materi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="dapat_diunduh"
                                            name="dapat_diunduh" value="1" checked>
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
                                            name="video_url" value="{{ old('video_url') }}"
                                            placeholder="https://www.youtube.com/watch?v=...">
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
                                            id="link_url" name="link_url" value="{{ old('link_url') }}"
                                            placeholder="https://website-belajar.com/...">
                                        @error('link_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="input-teks" class="d-none dynamic-input">
                                    <div class="mb-3">
                                        <label class="form-label" for="konten">Konten Bacaan <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('konten') is-invalid @enderror" id="konten" name="konten" rows="10"
                                            placeholder="Tulis materi pembelajaran disini...">{{ old('konten') }}</textarea>
                                        @error('konten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="card bg-label-secondary mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold">Tips</h6>
                                        <ul class="ps-3 mb-0 small text-muted">
                                            <li>Pilih <strong>File</strong> untuk mengupload dokumen seperti PDF atau
                                                PowerPoint.</li>
                                            <li>Pilih <strong>Video</strong> untuk menyematkan video dari YouTube.</li>
                                            <li>Pilih <strong>Link</strong> untuk memberikan tautan ke sumber eksternal.
                                            </li>
                                            <li>Pilih <strong>Teks</strong> untuk menulis materi bacaan langsung di sini.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Materi
                            </button>
                            <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}"
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
                // Hide all dynamic inputs
                document.querySelectorAll('.dynamic-input').forEach(el => el.classList.add('d-none'));

                // Get selected radio
                const selected = document.querySelector('input[name="tipe_materi"]:checked');
                if (selected) {
                    const type = selected.value;
                    const target = document.getElementById('input-' + type);
                    if (target) {
                        target.classList.remove('d-none');
                    }
                }
            }

            // Run on load to handle old input
            document.addEventListener('DOMContentLoaded', toggleMateriInput);
        </script>
    @endpush
@endsection
