@extends('layouts.guru')

@section('title', 'Edit Materi Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Materi: {{ $materi->judul_materi }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pertemuan.show', $materi->pertemuan_id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <x-input label="Judul Materi" name="judul_materi"
                                value="{{ old('judul_materi', $materi->judul_materi) }}" required />

                            <x-textarea label="Deskripsi Singkat" name="deskripsi" rows="2"
                                value="{{ old('deskripsi', $materi->deskripsi) }}" />

                            <div class="mb-3">
                                <label class="form-label">Tipe Materi <span class="text-danger">*</span></label>
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
                                <x-input label="Upload File Baru" type="file" name="file_materi" />
                                <small class="text-muted d-block mb-3" style="margin-top: -15px;">Biarkan kosong jika tidak
                                    ingin mengubah file. File saat ini: {{ $materi->file_name }}</small>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="dapat_diunduh" name="dapat_diunduh"
                                        value="1" {{ old('dapat_diunduh', $materi->dapat_diunduh) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dapat_diunduh">Izinkan siswa mengunduh file
                                        ini</label>
                                </div>
                            </div>

                            <div id="input-video" class="d-none dynamic-input">
                                <x-input label="URL Video (YouTube)" type="url" name="video_url"
                                    value="{{ old('video_url', $materi->video_url) }}" />
                            </div>

                            <div id="input-link" class="d-none dynamic-input">
                                <x-input label="URL Link" type="url" name="link_url"
                                    value="{{ old('link_url', $materi->link_url) }}" />
                            </div>

                            <div id="input-teks" class="d-none dynamic-input">
                                <x-textarea label="Konten Bacaan" name="konten" rows="10"
                                    value="{{ old('konten', $materi->konten) }}" />
                            </div>

                            <x-select label="Status" name="aktif" required>
                                <option value="1" {{ old('aktif', $materi->aktif) == 1 ? 'selected' : '' }}>Aktif
                                    (Ditampilkan)</option>
                                <option value="0" {{ old('aktif', $materi->aktif) == 0 ? 'selected' : '' }}>
                                    Sembunyikan</option>
                            </x-select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Update Materi</x-button>
                        <a href="{{ route('guru.pertemuan.show', $materi->pertemuan_id) }}"
                            class="btn btn-outline-secondary">
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
