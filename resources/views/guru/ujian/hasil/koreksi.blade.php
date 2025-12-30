@extends('layouts.app')
@section('title', 'Koreksi Jawaban')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <x-card title="Informasi Siswa">
                <div class="text-center mb-4">
                    @if ($jawaban->siswa->foto_profil)
                        <img src="{{ Storage::url($jawaban->siswa->foto_profil) }}" class="rounded-circle mb-3" width="80"
                            height="80">
                    @else
                        <div class="avatar avatar-xl bg-label-primary mx-auto mb-3">
                            <span
                                class="avatar-initial rounded-circle">{{ substr($jawaban->siswa->nama_lengkap, 0, 2) }}</span>
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $jawaban->siswa->nama_lengkap }}</h5>
                    <small class="text-muted d-block mb-3">{{ $jawaban->siswa->nis }}</small>
                </div>

                <div class="info-list border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nilai Saat Ini:</span>
                        <span class="fw-bold fs-5">{{ number_format($jawaban->nilai, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-label-success">{{ ucfirst($jawaban->status) }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('guru.ujian.hasil.index', $jawaban->jadwal_ujian_id) }}"
                        class="btn btn-outline-secondary w-100">
                        <i class="bx bx-chevron-left me-1"></i> Kembali
                    </a>
                </div>
            </x-card>
        </div>

        <div class="col-md-8">
            <form action="{{ route('guru.ujian.hasil.update', $jawaban->id) }}" method="POST">
                @csrf @method('PUT')
                <x-card title="Lembar Jawaban">
                    @foreach ($jawaban->detailJawaban as $detail)
                        <div class="mb-4 @if (!$loop->last) border-bottom pb-4 @endif">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">
                                    Soal No. {{ $loop->iteration }}
                                    <span
                                        class="badge bg-label-info ms-1">{{ ucfirst(str_replace('_', ' ', $detail->soalUjian->tipe_soal)) }}</span>
                                </h6>
                                <span class="badge bg-label-secondary">Bobot: {{ $detail->soalUjian->bobot_nilai }}</span>
                            </div>

                            <div class="mb-3">
                                {!! nl2br(e($detail->soalUjian->pertanyaan)) !!}
                                @if ($detail->soalUjian->gambar_soal)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($detail->soalUjian->gambar_soal) }}"
                                            class="img-fluid rounded border" style="max-height: 250px">
                                    </div>
                                @endif
                            </div>

                            <div
                                class="bg-light p-3 rounded-3 mb-3 border-start border-4 {{ $detail->benar ? 'border-success' : 'border-danger' }}">
                                <div class="small text-muted mb-1">Jawaban Siswa:</div>
                                @if ($detail->soalUjian->tipe_soal == 'pilihan_ganda')
                                    <div class="fw-bold d-flex align-items-center">
                                        {{ $detail->jawaban_dipilih ?? 'Tidak Menjawab' }}
                                        @if ($detail->benar)
                                            <i class="bx bxs-check-circle text-success ms-2"></i>
                                        @else
                                            <i class="bx bxs-x-circle text-danger ms-2"></i>
                                            <span class="text-muted small ms-2 fw-normal">(Kunci:
                                                {{ $detail->soalUjian->kunci_jawaban }})</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="fw-medium text-dark">{{ $detail->jawaban_essay ?? '-' }}</div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-label-secondary border-0">Nilai Diperoleh</span>
                                        @if ($detail->soalUjian->tipe_soal == 'essay')
                                            <input type="number" step="0.01"
                                                max="{{ $detail->soalUjian->bobot_nilai }}" min="0"
                                                name="nilai_essay[{{ $detail->id }}]" class="form-control"
                                                value="{{ number_format($detail->nilai_diperoleh, 2) }}">
                                        @else
                                            <input type="text" class="form-control"
                                                value="{{ number_format($detail->nilai_diperoleh, 2) }}" disabled>
                                        @endif
                                    </div>
                                    @if ($detail->soalUjian->tipe_soal == 'essay')
                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                            Maksimum Bobot: {{ $detail->soalUjian->bobot_nilai }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-2 text-primary border-top pt-4">
                        <x-textarea label="Catatan Pengawas / Guru" name="catatan_pengawas" rows="2"
                            placeholder="Tuliskan catatan evaluasi atau alasan perubahan nilai jika ada...">{{ $jawaban->catatan_pengawas }}</x-textarea>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save" class="w-100">Simpan Penilaian</x-button>
                    </div>
                </x-card>
            </form>
        </div>
    </div>
@endsection
