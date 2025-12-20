@extends('layouts.guru')
@section('title', 'Tambah Soal Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian / Soal /</span> Tambah</h4>

        <div class="card mb-4">
            <h5 class="card-header">Buat Soal Baru - {{ $ujian->nama_ujian }}</h5>
            <div class="card-body">
                <form action="{{ route('guru.ujian.soal.store', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tipe Soal</label>
                        <select name="tipe_soal" id="tipe_soal" class="form-select" onchange="toggleTipeSoal()">
                            <option value="pilihan_ganda">Pilihan Ganda</option>
                            <option value="essay">Essay / Uraian</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <textarea class="form-control" name="pertanyaan" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Soal (Opsional)</label>
                        <input type="file" class="form-control" name="gambar_soal">
                        <div class="form-text">Format: JPG, PNG. Max: 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bobot Nilai</label>
                        <input type="number" step="0.5" class="form-control" name="bobot_nilai" value="2"
                            required>
                    </div>

                    <div id="pilihan_ganda_section">
                        <hr class="my-3">
                        <h6 class="mb-3">Pilihan Jawaban</h6>

                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Pilihan {{ $opt }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="kunci_jawaban"
                                                value="{{ $opt }}" aria-label="Is Correct">
                                        </div>
                                        <textarea class="form-control" name="pilihan_{{ strtolower($opt) }}" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <small class="text-muted">* Pilih radio button di sebelah kiri untuk menentukan Kunci
                            Jawaban.</small>
                    </div>

                    <div id="essay_section" style="display:none;">
                        <div class="alert alert-info">Untuk soal Essay, koreksi dilakukan secara manual oleh guru.</div>
                    </div>

                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary">Simpan Soal</button>
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
