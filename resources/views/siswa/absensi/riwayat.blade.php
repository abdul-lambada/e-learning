@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Absensi /</span> Riwayat
            </h4>

            <!-- Statistik -->
            <div class="row mb-4">
                <div class="col-6 col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Hadir</h5>
                            <h2 class="card-text mb-0 text-white">{{ $statistik['hadir'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Izin</h5>
                            <h2 class="card-text mb-0 text-white">{{ $statistik['izin'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Sakit</h5>
                            <h2 class="card-text mb-0 text-white">{{ $statistik['sakit'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title text-white">Alpha</h5>
                            <h2 class="card-text mb-0 text-white">{{ $statistik['alpha'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Riwayat Kehadiran</h5>
                </div>
                <div class="card-body pt-4">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Status</th>
                                    <th>Waktu Absen</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absensi as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->pertemuan->tanggal_pertemuan)->format('d F Y') }}
                                        </td>
                                        <td><strong>{{ $log->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</strong>
                                        </td>
                                        <td>{{ $log->pertemuan->guruMengajar->guru->nama_lengkap }}</td>
                                        <td>
                                            @if ($log->status == 'hadir')
                                                <span class="badge bg-label-success">Hadir</span>
                                            @elseif ($log->status == 'izin')
                                                <span class="badge bg-label-info">Izin</span>
                                            @elseif ($log->status == 'sakit')
                                                <span class="badge bg-label-warning">Sakit</span>
                                            @elseif ($log->status == 'alpha')
                                                <span class="badge bg-label-danger">Alpha</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($log->waktu_absen)->format('H:i') }}</td>
                                        <td>{{ $log->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data riwayat absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $absensi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
