@extends('layouts.guru')
@section('title', 'Tambah Soal Ujian')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card :title="'Buat Soal Baru - ' . $ujian->nama_ujian">
                <form action="{{ route('guru.ujian.soal.store', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 text-primary">
                            <x-select label="Tipe Soal" name="tipe_soal" onchange="toggleTipeSoal()">
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="essay">Essay / Uraian</option>
                            </x-select>
                        </div>
                        <div class="col-md-6 text-primary">
                            <x-input label="Bobot Nilai" type="number" step="0.5" name="bobot_nilai" value="2"
                                required />
                        </div>
                    </div>

                    <x-textarea label="Pertanyaan" name="pertanyaan" rows="3" required />

                    <div class="mb-4">
                        <label class="form-label">Gambar Soal (Opsional)</label>
                        <input type="file" class="form-control" name="gambar_soal">
                        <div class="form-text">Format: JPG, PNG. Max: 2MB.</div>
                    </div>

                    <div id="pilihan_ganda_section">
                        <hr class="my-4">
                        <h6 class="mb-3 fw-bold"><i class="bx bx-list-check me-1"></i> Pilihan Jawaban</h6>

                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-2">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="kunci_jawaban"
                                            value="{{ $opt }}" id="kunci_{{ $opt }}">
                                        <label class="form-check-label fw-bold" for="kunci_{{ $opt }}">
                                            Pilihan {{ $opt }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="pilihan_{{ strtolower($opt) }}" rows="1"
                                        placeholder="Ketik pilihan {{ $opt }} di sini..."></textarea>
                                </div>
                            </div>
                        @endforeach
                        <div class="alert alert-primary d-flex align-items-center mb-0">
                            <i class="bx bx-info-circle me-2"></i>
                            <small>Pilih radio button di samping label "Pilihan X" untuk menentukan <strong>Kunci
                                    Jawaban</strong>.</small>
                        </div>
                    </div>

                    <div id="essay_section" style="display:none;">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="bx bx-info-circle me-2"></i>
                            <span>Untuk soal Essay, koreksi dilakukan secara manual oleh guru melalui menu Hasil
                                Ujian.</span>
                        </div>
                    </div>

                    <div class="mt-5">
                        <x-button type="submit" icon="bx-save">Simpan Soal</x-button>
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
