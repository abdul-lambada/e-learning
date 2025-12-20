@extends('layouts.siswa')

@section('title', $kuis->judul_kuis)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Kuis /</span> {{ $kuis->judul_kuis }}</h4>
            <a href="{{ route('siswa.pembelajaran.pertemuan', $kuis->pertemuan_id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Instruksi & Deskripsi</h5>
                        <p class="card-text text-muted">{{ $kuis->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                        @if ($kuis->instruksi)
                            <div class="alert alert-warning">
                                <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-info-circle me-1"></i> Petunjuk
                                    Pengerjaan:</h6>
                                <p class="mb-0 text-sm">{!! nl2br(e($kuis->instruksi)) !!}</p>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-label-primary p-2"><i class="bx bx-time fs-4"></i></span>
                                    <div>
                                        <h6 class="mb-0">Durasi</h6>
                                        <small class="text-muted">{{ $kuis->durasi_menit }} Menit</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-label-info p-2"><i class="bx bx-list-check fs-4"></i></span>
                                    <div>
                                        <h6 class="mb-0">Jumlah Soal</h6>
                                        <small class="text-muted">{{ $kuis->soalKuis->count() }} Butir</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-label-success p-2"><i class="bx bx-check-shield fs-4"></i></span>
                                    <div>
                                        <h6 class="mb-0">Passing Grade</h6>
                                        <small class="text-muted">{{ $kuis->nilai_minimal_lulus }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-label-warning p-2"><i class="bx bx-refresh fs-4"></i></span>
                                    <div>
                                        <h6 class="mb-0">Kesempatan</h6>
                                        <small class="text-muted">Maksimal {{ $kuis->max_percobaan }}x percobaan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top text-end">
                        @if ($sedangMengerjakan)
                            <a href="{{ route('siswa.kuis.kerjakan', $sedangMengerjakan->id) }}" class="btn btn-primary">
                                <i class="bx bx-play-circle me-1"></i> Lanjutkan Mengerjakan
                            </a>
                        @elseif($sisaPercobaan > 0)
                            <form action="{{ route('siswa.kuis.start', $kuis->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin memulai kuis sekarang? Waktu akan berjalan setelah Anda menekan OK.')">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-play-circle me-1"></i> Mulai Mengerjakan
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="bx bx-block me-1"></i> Kesempatan Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <h5 class="card-header">Riwayat Percobaan</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ke-</th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $history)
                                    <tr>
                                        <td>{{ $history->percobaan_ke }}</td>
                                        <td>
                                            @if ($history->status == 'selesai')
                                                <span class="badge bg-label-success">Selesai</span>
                                            @elseif($history->status == 'sedang_dikerjakan')
                                                <span class="badge bg-label-warning">Berjalan</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ $history->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($history->status == 'selesai')
                                                <strong>{{ $history->nilai }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted small py-4">Belum ada riwayat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
