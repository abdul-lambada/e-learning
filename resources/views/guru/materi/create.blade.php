@extends('layouts.guru')

@section('title', 'Tambah Materi Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Tambah Materi: {{ $pertemuan->judul_pertemuan }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

                    <div class="row">
                        <div class="col-md-8">
                            <x-input label="Judul Materi" name="judul_materi" value="{{ old('judul_materi') }}"
                                placeholder="Contoh: Slide Presentasi Bab 1" required />

                            <x-textarea label="Deskripsi Singkat" name="deskripsi" rows="2"
                                placeholder="Penjelasan singkat tentang materi ini..." />

                            <div class="mb-3">
                                <label class="form-label">Tipe Materi <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content" for="tipeFile">
                                                <span class="custom-option-body">
                                                    <i class="bx bx-file"></i>
                                                    <span class="custom-option-title">File</span>
                                                    <small>PDF, PPT, DOC</small>
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
                                                    <small>Website External</small>
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
                                                    <small>Bacaan Langsung</small>
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
                                <x-input label="Upload File" type="file" name="file_materi" />
                                <small class="text-muted d-block mb-3" style="margin-top: -15px;">Max: 10MB. Format: PDF,
                                    DOCX, PPTX, JPG, PNG.</small>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="dapat_diunduh" name="dapat_diunduh"
                                        value="1" checked>
                                    <label class="form-check-label" for="dapat_diunduh">Izinkan siswa mengunduh file
                                        ini</label>
                                </div>
                            </div>

                            <div id="input-video" class="d-none dynamic-input">
                                <x-input label="URL Video (YouTube)" type="url" name="video_url"
                                    value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." />
                            </div>

                            <div id="input-link" class="d-none dynamic-input">
                                <x-input label="URL Link" type="url" name="link_url" value="{{ old('link_url') }}"
                                    placeholder="https://website-belajar.com/..." />
                            </div>

                            <div id="input-teks" class="d-none dynamic-input">
                                <x-textarea label="Konten Bacaan" name="konten" rows="10"
                                    placeholder="Tulis materi pembelajaran disini..." />
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
                                        <li>Pilih <strong>Link</strong> untuk memberikan tautan ke sumber eksternal.</li>
                                        <li>Pilih <strong>Teks</strong> untuk menulis materi bacaan langsung di sini.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan Materi</x-button>
                        <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
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
