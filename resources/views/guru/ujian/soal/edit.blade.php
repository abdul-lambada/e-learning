@extends('layouts.guru')
@section('title', 'Edit Soal Ujian')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card :title="'Edit Soal No. ' . $soal->nomor_soal">
                <form action="{{ route('guru.ujian.soal.update', [$ujian->id, $soal->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row text-primary">
                        <div class="col-md-6">
                            <x-select label="Tipe Soal" name="tipe_soal" onchange="toggleTipeSoal()">
                                <option value="pilihan_ganda" {{ $soal->tipe_soal == 'pilihan_ganda' ? 'selected' : '' }}>
                                    Pilihan Ganda</option>
                                <option value="essay" {{ $soal->tipe_soal == 'essay' ? 'selected' : '' }}>Essay / Uraian
                                </option>
                            </x-select>
                        </div>
                        <div class="col-md-6">
                            <x-input label="Bobot Nilai" type="number" step="0.5" name="bobot_nilai"
                                value="{{ $soal->bobot_nilai }}" required />
                        </div>
                    </div>

                    <x-textarea label="Pertanyaan" name="pertanyaan" rows="3"
                        required>{{ $soal->pertanyaan }}</x-textarea>

                    <div class="mb-4">
                        <label class="form-label">Gambar Soal</label>
                        @if ($soal->gambar_soal)
                            <div class="mb-3 p-2 border rounded bg-light d-inline-block position-relative">
                                <img src="{{ Storage::url($soal->gambar_soal) }}" alt="Gambar Soal"
                                    style="max-height: 200px;" class="rounded">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="hapus_gambar_soal" id="hapus_img">
                                    <label class="form-check-label text-danger fw-bold" for="hapus_img">Hapus Gambar
                                        Ini</label>
                                </div>
                            </div>
                        @endif
                        <input type="file" class="form-control" name="gambar_soal">
                        <div class="form-text">Pilih file baru jika ingin mengganti gambar. Format: JPG, PNG. Max: 2MB.
                        </div>
                    </div>

                    <div id="pilihan_ganda_section" style="{{ $soal->tipe_soal == 'essay' ? 'display:none;' : '' }}">
                        <hr class="my-4">
                        <h6 class="mb-3 fw-bold"><i class="bx bx-list-check me-1"></i> Pilihan Jawaban</h6>

                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            @php $field = 'pilihan_' . strtolower($opt); @endphp
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-2">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="kunci_jawaban"
                                            value="{{ $opt }}" id="kunci_{{ $opt }}"
                                            {{ $soal->kunci_jawaban == $opt ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="kunci_{{ $opt }}">
                                            Pilihan {{ $opt }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="pilihan_{{ strtolower($opt) }}" rows="1"
                                        placeholder="Ketik pilihan {{ $opt }} di sini...">{{ $soal->$field }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="essay_section" style="{{ $soal->tipe_soal == 'pilihan_ganda' ? 'display:none;' : '' }}">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="bx bx-info-circle me-2"></i>
                            <span>Untuk soal Essay, koreksi dilakukan secara manual oleh guru melalui menu Hasil
                                Ujian.</span>
                        </div>
                    </div>

                    <div class="mt-5">
                        <x-button type="submit" icon="bx-save">Simpan Perubahan</x-button>
                        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>

    <script>
        function toggleTipeSoal() {
            var tipe = document.getElementById('tipe_soal').value;
            if (tipe === 'pilihan_ganda') {
                document.getElementById('pilihan_ganda_section').style.display = 'block';
                document.getElementById('essay_section').style.display = 'none';
            } else {
                document.getElementById('pilihan_ganda_section').style.display = 'none';
                document.getElementById('essay_section').style.display = 'block';
            }
        }
    </script>
@endsection
