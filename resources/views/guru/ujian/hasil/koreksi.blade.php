@extends('layouts.guru')
@section('title', 'Koreksi Jawaban')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian / Hasil /</span> Koreksi</h4>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Informasi Siswa</h6>
                        <p class="mb-1"><strong>Nama:</strong> {{ $jawaban->siswa->nama_lengkap }}</p>
                        <p class="mb-1"><strong>Nilai Saat Ini:</strong> {{ number_format($jawaban->nilai, 2) }}</p>
                        <p class="mb-1"><strong>Status:</strong> {{ ucfirst($jawaban->status) }}</p>
                        <hr>
                        <a href="{{ route('guru.ujian.hasil.index', $jawaban->jadwal_ujian_id) }}"
                            class="btn btn-secondary w-100">Kembali</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <form action="{{ route('guru.ujian.hasil.update', $jawaban->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card mb-4">
                        <h5 class="card-header">Lembar Jawaban</h5>
                        <div class="card-body">
                            @foreach ($jawaban->detailJawaban as $detail)
                                <div class="mb-4 border-bottom pb-4">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold">
                                            Soal No. {{ $loop->iteration }}
                                            <span
                                                class="badge bg-label-info">{{ ucfirst(str_replace('_', ' ', $detail->soalUjian->tipe_soal)) }}</span>
                                        </h6>
                                        <span class="text-muted">Bobot: {{ $detail->soalUjian->bobot_nilai }}</span>
                                    </div>
                                    <div class="mb-2">
                                        {!! nl2br(e($detail->soalUjian->pertanyaan)) !!}
                                        @if ($detail->soalUjian->gambar_soal)
                                            <br><img src="{{ Storage::url($detail->soalUjian->gambar_soal) }}"
                                                class="img-thumbnail mt-2" style="max-height: 200px">
                                        @endif
                                    </div>

                                    <div class="bg-light p-3 rounded mb-3">
                                        <strong>Jawaban Siswa:</strong><br>
                                        @if ($detail->soalUjian->tipe_soal == 'pilihan_ganda')
                                            <span class="badge {{ $detail->benar ? 'bg-success' : 'bg-danger' }}">
                                                {{ $detail->jawaban_dipilih ?? 'Tidak Menjawab' }}
                                            </span>
                                            @if (!$detail->benar)
                                                <span class="text-muted ms-2">(Kunci:
                                                    {{ $detail->soalUjian->kunci_jawaban }})</span>
                                            @endif
                                        @else
                                            <p class="mb-0">{{ $detail->jawaban_essay ?? '-' }}</p>
                                        @endif
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label">Nilai Diperoleh</label>
                                            @if ($detail->soalUjian->tipe_soal == 'essay')
                                                <input type="number" step="0.01"
                                                    max="{{ $detail->soalUjian->bobot_nilai }}" min="0"
                                                    name="nilai_essay[{{ $detail->id }}]" class="form-control"
                                                    value="{{ $detail->nilai_diperoleh }}">
                                                <small class="text-muted">Max:
                                                    {{ $detail->soalUjian->bobot_nilai }}</small>
                                            @else
                                                <input type="text" class="form-control"
                                                    value="{{ $detail->nilai_diperoleh }}" disabled>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mb-3">
                                <label class="form-label">Catatan Pengawas / Guru</label>
                                <textarea class="form-control" name="catatan_pengawas" rows="3">{{ $jawaban->catatan_pengawas }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Simpan Penilaian</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
