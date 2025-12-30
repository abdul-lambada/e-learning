@extends('layouts.app')

@section('title', 'Nilai Saya')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Siswa /</span> Nilai Akhir
            </h4>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Rekap Nilai Perkuliahan/Pelajaran</h5>
                    @if ($nilai->isNotEmpty())
                        <a href="{{ route('siswa.nilai.cetak') }}" class="btn btn-primary" target="_blank">
                            <i class="bx bx-printer me-1"></i> Cetak Laporan PDF
                        </a>
                    @endif
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Nilai Akhir</th>
                                <th>Predikat</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($nilai as $n)
                                <tr>
                                    <td><strong>{{ $n->mataPelajaran->nama_mapel }}</strong></td>
                                    <td>{{ $n->kelas->nama_kelas }}</td>
                                    <td><span class="fw-bold fs-5">{{ number_format($n->nilai_akhir, 2) }}</span></td>
                                    <td><span class="badge bg-label-primary">{{ $n->getPredikat() }}</span></td>
                                    <td>
                                        <span class="badge {{ $n->lulus ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $n->lulus ? 'Lulus' : 'Tidak Lulus' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#modal-{{ $n->id }}">
                                            <i class="bx bx-info-circle"></i> Komponen
                                        </button>

                                        <!-- Modal Component Details -->
                                        <div class="modal fade" id="modal-{{ $n->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rincian Nilai -
                                                            {{ $n->mataPelajaran->nama_mapel }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group list-group-flush">
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Absensi</span>
                                                                <span
                                                                    class="fw-bold">{{ number_format($n->nilai_absensi, 2) }}
                                                                    ({{ number_format($n->bobot_absensi, 0) }}%)
                                                                </span>
                                                            </li>
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Tugas</span>
                                                                <span
                                                                    class="fw-bold">{{ number_format($n->nilai_tugas, 2) }}
                                                                    ({{ number_format($n->bobot_tugas, 0) }}%)</span>
                                                            </li>
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Kuis</span>
                                                                <span
                                                                    class="fw-bold">{{ number_format($n->nilai_kuis, 2) }}
                                                                    ({{ number_format($n->bobot_kuis, 0) }}%)</span>
                                                            </li>
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span>Ujian (UTS/UAS)</span>
                                                                <span
                                                                    class="fw-bold">{{ number_format($n->nilai_ujian, 2) }}
                                                                    ({{ number_format($n->bobot_ujian, 0) }}%)</span>
                                                            </li>
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center bg-label-light">
                                                                <span class="fw-bold">Nilai Akhir</span>
                                                                <span
                                                                    class="h4 mb-0 fw-bold text-primary">{{ number_format($n->nilai_akhir, 2) }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bx bx-no-entry fs-1 text-muted"></i>
                                        <p class="mt-2">Belum ada nilai yang dipublikasikan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
