@extends('layouts.guru')
@section('title', 'Edit Soal Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian / Soal /</span> Edit</h4>

        <div class="card mb-4">
            <h5 class="card-header">Edit Soal No. {{ $soal->nomor_soal }}</h5>
            <div class="card-body">
                <form action="{{ route('guru.ujian.soal.update', [$ujian->id, $soal->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tipe Soal</label>
                        <select name="tipe_soal" id="tipe_soal" class="form-select" onchange="toggleTipeSoal()">
                            <option value="pilihan_ganda" {{ $soal->tipe_soal == 'pilihan_ganda' ? 'selected' : '' }}>
                                Pilihan Ganda</option>
                            <option value="essay" {{ $soal->tipe_soal == 'essay' ? 'selected' : '' }}>Essay / Uraian
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <textarea class="form-control" name="pertanyaan" rows="3" required>{{ $soal->pertanyaan }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Soal</label>
                        @if ($soal->gambar_soal)
                            <div class="mb-2">
                                <img src="{{ Storage::url($soal->gambar_soal) }}" alt="Gambar Soal"
                                    style="max-height: 150px;" class="rounded">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="hapus_gambar_soal" id="hapus_img">
                                    <label class="form-check-label" for="hapus_img">Hapus Gambar</label>
                                </div>
                            </div>
                        @endif
                        <input type="file" class="form-control" name="gambar_soal">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bobot Nilai</label>
                        <input type="number" step="0.5" class="form-control" name="bobot_nilai"
                            value="{{ $soal->bobot_nilai }}" required>
                    </div>

                    <div id="pilihan_ganda_section" style="{{ $soal->tipe_soal == 'essay' ? 'display:none;' : '' }}">
                        <hr class="my-3">
                        <h6 class="mb-3">Pilihan Jawaban</h6>

                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            @php $field = 'pilihan_' . strtolower($opt); @endphp
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Pilihan {{ $opt }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="kunci_jawaban"
                                                value="{{ $opt }}"
                                                {{ $soal->kunci_jawaban == $opt ? 'checked' : '' }}
                                                aria-label="Is Correct">
                                        </div>
                                        <textarea class="form-control" name="pilihan_{{ strtolower($opt) }}" rows="1">{{ $soal->$field }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="essay_section" style="{{ $soal->tipe_soal == 'pilihan_ganda' ? 'display:none;' : '' }}">
                        <div class="alert alert-info">Untuk soal Essay, koreksi dilakukan secara manual oleh guru.</div>
                    </div>

                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="btn btn-label-secondary">Batal</a>
                </form>
            </div>
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
