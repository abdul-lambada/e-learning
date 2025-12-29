@extends('layouts.guru')

@section('title', 'Review Jawaban Siswa')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <div>
                <h4 class="fw-bold m-0"><span class="text-muted fw-light">Kuis / Hasil /</span> Review Jawaban</h4>
                <small class="text-muted">Siswa: {{ $jawabanKuis->siswa->nama_lengkap }}</small>
            </div>
            <a href="{{ route('guru.kuis.hasil', $jawabanKuis->kuis_id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="col-md-3 mb-4">
                <x-card class="sticky-top" style="top: 100px;" title="Ringkasan Nilai">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pilihan Ganda:</span>
                        <span
                            class="fw-bold text-success">{{ $jawabanKuis->detailJawaban->where('soalKuis.tipe_soal', 'pilihan_ganda')->sum('nilai_diperoleh') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Essay / Uraian:</span>
                        <span
                            class="fw-bold text-warning">{{ $jawabanKuis->detailJawaban->where('soalKuis.tipe_soal', 'essay')->sum('nilai_diperoleh') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Nilai Akhir:</span>
                        <h3 class="mb-0 text-primary">{{ $jawabanKuis->nilai }}</h3>
                    </div>
                    <small class="text-muted d-block mt-2">Nilai Akhir = (Total Skor / Total Bobot Kuis) * 100</small>
                </x-card>
            </div>

            <div class="col-md-9">
                <form action="{{ route('guru.kuis.simpan_koreksi', $jawabanKuis->id) }}" method="POST">
                    @csrf
                    <x-card title="Lembar Jawaban">
                        <x-slot name="headerAction">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-save me-1"></i> Simpan Koreksi
                            </button>
                        </x-slot>

                        @foreach ($jawabanKuis->detailJawaban as $detail)
                            <div class="border rounded p-3 mb-3 {{ $detail->benar ? 'border-success' : ($detail->soalKuis->tipe_soal == 'essay' ? 'border-warning' : 'border-danger') }}"
                                style="border-width: 2px !important;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-label-primary">Soal {{ $loop->iteration }}
                                        ({{ $detail->soalKuis->tipe_soal }})</span>
                                    <span class="text-muted small">Bobot Soal:
                                        {{ $detail->soalKuis->bobot_nilai }}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Pertanyaan:</strong>
                                    <p class="mb-1">{!! nl2br(e($detail->soalKuis->pertanyaan)) !!}</p>
                                </div>

                                <div class="mb-3 bg-lighter p-3 rounded">
                                    <strong>Jawaban Siswa:</strong>
                                    @if ($detail->soalKuis->tipe_soal == 'pilihan_ganda')
                                        <p class="mb-0 fw-bold">{{ $detail->jawaban_dipilih }}
                                            @if ($detail->jawaban_dipilih == $detail->soalKuis->kunci_jawaban)
                                                <i class='bx bx-check text-success'></i>
                                            @else
                                                <i class='bx bx-x text-danger'></i> (Kunci:
                                                {{ $detail->soalKuis->kunci_jawaban }})
                                            @endif
                                        </p>
                                    @else
                                        <p class="mb-0">{{ $detail->jawaban_essay ?? '-' }}</p>
                                    @endif
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label">Nilai Diperoleh:</label>
                                        <input type="number" step="0.5" class="form-control"
                                            name="nilai_essay[{{ $detail->id }}]" value="{{ $detail->nilai_diperoleh }}"
                                            max="{{ $detail->soalKuis->bobot_nilai }}">
                                        <div class="form-text">Max: {{ $detail->soalKuis->bobot_nilai }}</div>
                                    </div>
                                    <div class="col-md-9">
                                        @if ($detail->soalKuis->pembahasan)
                                            <div class="accordion mt-2" id="acc-{{ $detail->id }}">
                                                <div class="accordion-item shadow-none border">
                                                    <h2 class="accordion-header" id="head-{{ $detail->id }}">
                                                        <button class="accordion-button collapsed py-2" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#col-{{ $detail->id }}">
                                                            Lihat Pembahasan
                                                        </button>
                                                    </h2>
                                                    <div id="col-{{ $detail->id }}" class="accordion-collapse collapse"
                                                        data-bs-parent="#acc-{{ $detail->id }}">
                                                        <div class="accordion-body small text-muted py-2">
                                                            {!! nl2br(e($detail->soalKuis->pembahasan)) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <x-slot name="footer">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i> Simpan Koreksi
                                </button>
                            </div>
                        </x-slot>
                    </x-card>
                </form>
            </div>
        </div>
    </div>
@endsection
