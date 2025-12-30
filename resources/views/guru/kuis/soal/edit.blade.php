@extends('layouts.app')

@section('title', 'Edit Soal')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Kuis / {{ $soal->kuis->judul_kuis }} /</span> Edit Soal
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Edit Soal #{{ $soal->nomor_soal }}</h5>
                    <div class="card-body">
                        <form action="{{ route('guru.soal.update', $soal->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Kiri: Input Soal -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label" for="pertanyaan">Pertanyaan</label>
                                        <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="4" required>{{ $soal->pertanyaan }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="gambar_soal">Gambar Soal (Opsional)</label>
                                        @if ($soal->gambar_soal)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($soal->gambar_soal) }}" height="100"
                                                    class="rounded border">
                                                <div class="form-text mt-1">
                                                    <input type="checkbox" name="hapus_gambar_soal" id="del_img"> <label
                                                        for="del_img">Hapus gambar saat ini</label>
                                                </div>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" id="gambar_soal" name="gambar_soal"
                                            accept="image/*">
                                        <div class="form-text">Upload gambar baru untuk mengganti. Format: JPG, PNG, JPEG.
                                            Max 2MB.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tipe Soal</label>
                                        <div class="form-check">
                                            <input name="tipe_soal" class="form-check-input" type="radio"
                                                value="pilihan_ganda" id="tipe_pg"
                                                {{ $soal->tipe_soal == 'pilihan_ganda' ? 'checked' : '' }}
                                                onchange="toggleTipeSoal()">
                                            <label class="form-check-label" for="tipe_pg"> Pilihan Ganda </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="tipe_soal" class="form-check-input" type="radio" value="essay"
                                                id="tipe_essay" {{ $soal->tipe_soal == 'essay' ? 'checked' : '' }}
                                                onchange="toggleTipeSoal()">
                                            <label class="form-check-label" for="tipe_essay"> Essay / Uraian </label>
                                        </div>
                                    </div>

                                    <!-- Container Pilihan Ganda -->
                                    <div id="container-pg">
                                        <hr>
                                        <label class="form-label mb-2">Pilihan Jawaban</label>

                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                            @php
                                                $col = 'pilihan_' . strtolower($opt);
                                            @endphp
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">{{ $opt }}</span>
                                                <input type="text" class="form-control" name="{{ $col }}"
                                                    value="{{ $soal->$col }}"
                                                    placeholder="Isi Jawaban {{ $opt }}">
                                            </div>
                                        @endforeach

                                        <div class="mb-3">
                                            <label class="form-label" for="kunci_jawaban">Kunci Jawaban</label>
                                            <select class="form-select" id="kunci_jawaban" name="kunci_jawaban">
                                                <option value="">Pilih Kunci Jawaban...</option>
                                                @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ $soal->kunci_jawaban == $opt ? 'selected' : '' }}>Pilihan
                                                        {{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kanan: Settings & Nav -->
                                <div class="col-md-4">
                                    <div class="card bg-lighter shadow-none border">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label" for="bobot_nilai">Bobot Nilai</label>
                                                <input type="number" class="form-control" id="bobot_nilai"
                                                    name="bobot_nilai" value="{{ $soal->bobot_nilai }}" min="0"
                                                    step="0.5">
                                                <div class="form-text">Nilai untuk soal ini jika benar.</div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="pembahasan">Pembahasan (Opsional)</label>
                                                <textarea class="form-control" id="pembahasan" name="pembahasan" rows="3">{{ $soal->pembahasan }}</textarea>
                                            </div>

                                            <div class="d-grid gap-2 mt-4">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                <a href="{{ route('guru.kuis.show', $soal->kuis_id) }}"
                                                    class="btn btn-outline-secondary">Batal</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTipeSoal() {
            const isPg = document.getElementById('tipe_pg').checked;
            const containerPg = document.getElementById('container-pg');
            const inputsPg = containerPg.querySelectorAll('input, select');

            if (isPg) {
                containerPg.style.display = 'block';
                inputsPg.forEach(el => el.required = true);
            } else {
                containerPg.style.display = 'none';
                inputsPg.forEach(el => el.required = false);
            }
        }

        // Init
        document.addEventListener('DOMContentLoaded', toggleTipeSoal);
    </script>
@endsection
