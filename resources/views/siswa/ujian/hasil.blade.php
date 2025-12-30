@extends('layouts.app')

@section('title', 'Hasil Ujian')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian /</span> Hasil Ujian</h4>

            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Riwayat Nilai Ujian</h5>
                </div>
                <div class="card-body pt-4">
                    @if ($riwayatUjian->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/page-misc-error-light.png') }}"
                                alt="No Data" width="150" class="mb-3">
                            <h4>Belum Ada Data Ujian</h4>
                            <p class="text-muted">Anda belum mengikuti ujian apapun atau hasil ujian belum tersedia.</p>
                        </div>
                    @else
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Nama Ujian</th>
                                        <th>Tanggal Pengerjaan</th>
                                        <th>Tipe</th>
                                        <th>Nilai</th>
                                        <th>Status Kelulusan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayatUjian as $hasil)
                                        @php
                                            $ujian = $hasil->jadwalUjian->ujian;
                                            $lulus = $hasil->nilai >= $ujian->nilai_minimal_lulus;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $ujian->mataPelajaran->nama_mapel }}</strong>
                                            </td>
                                            <td>{{ $ujian->nama_ujian }}</td>
                                            <td>{{ \Carbon\Carbon::parse($hasil->waktu_selesai)->format('d M Y, H:i') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-label-info">{{ $ujian->jenis_ujian }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-5 fw-bold {{ $lulus ? 'text-success' : 'text-danger' }}">
                                                    {{ $hasil->nilai }}
                                                </span>
                                                <span class="text-muted small">/ {{ $ujian->nilai_maksimal }}</span>
                                            </td>
                                            <td>
                                                @if ($lulus)
                                                    <span class="badge bg-success">LULUS</span>
                                                @else
                                                    <span class="badge bg-danger">TIDAK LULUS</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $riwayatUjian->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
