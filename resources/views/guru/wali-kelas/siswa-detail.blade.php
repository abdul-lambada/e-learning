@extends('layouts.guru')

@section('title', 'Detail Siswa - Wali Kelas')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Wali Kelas / {{ $kelas->nama_kelas }} /</span> Detail Siswa
            </h4>

            <div class="row">
                <!-- User Sidebar -->
                <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                    <!-- User Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex align-items-center flex-column">
                                    @if ($siswa->foto_profil)
                                        <img class="img-fluid rounded my-4" src="{{ Storage::url($siswa->foto_profil) }}"
                                            height="110" width="110" alt="User avatar" />
                                    @else
                                        <div class="avatar avatar-xl bg-label-primary my-4"
                                            style="height: 110px; width: 110px;">
                                            <span
                                                class="avatar-initial rounded fs-1">{{ substr($siswa->nama_lengkap, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="user-info text-center">
                                        <h4 class="mb-2">{{ $siswa->nama_lengkap }}</h4>
                                        <span class="badge bg-label-secondary">Siswa</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around flex-wrap my-4 py-3">
                                <div class="d-flex align-items-start me-4 mt-3 gap-3">
                                    <span class="badge bg-label-primary p-2 rounded"><i
                                            class="bx bx-check bx-sm"></i></span>
                                    <div>
                                        <h5 class="mb-0">{{ $absensiStats['hadir'] ?? 0 }}</h5>
                                        <span>Kehadiran</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mt-3 gap-3">
                                    <span class="badge bg-label-danger p-2 rounded"><i class="bx bx-x bx-sm"></i></span>
                                    <div>
                                        <h5 class="mb-0">{{ $absensiStats['alpha'] ?? 0 }}</h5>
                                        <span>Alpa</span>
                                    </div>
                                </div>
                            </div>
                            <h5 class="pb-2 border-bottom mb-4">Detail Informasi</h5>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Username:</span>
                                        <span>{{ $siswa->username }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Email:</span>
                                        <span>{{ $siswa->email }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Status:</span>
                                        <span class="badge {{ $siswa->aktif ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $siswa->aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">NIS:</span>
                                        <span>{{ $siswa->nis ?? '-' }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Gender:</span>
                                        <span>{{ $siswa->jenis_kelamin }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">No. Telp:</span>
                                        <span>{{ $siswa->no_telepon ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /User Card -->
                </div>
                <!--/ User Sidebar -->

                <!-- User Content -->
                <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                    <!-- Tabs Navigation -->
                    <div class="nav-gestures-wrapper mb-4">
                        <ul class="nav nav-pills flex-column flex-md-row mb-3">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0);"><i
                                        class="bx bx-bar-chart-alt-2 me-1"></i> Akademik</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Academic/Nilai Summary -->
                    <div class="card mb-4">
                        <h5 class="card-header">Ringkasan Nilai Semester Ini</h5>
                        <div class="table-responsive mb-3">
                            <table class="table table-hover border-top">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Nilai Akhir</th>
                                        <th>Predikat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nilaiSummary as $nilai)
                                        <tr>
                                            <td>{{ $nilai->mataPelajaran->nama_mapel }}</td>
                                            <td><span class="fw-bold">{{ number_format($nilai->nilai_akhir, 2) }}</span>
                                            </td>
                                            <td><span class="badge bg-label-primary">{{ $nilai->getPredikat() }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $nilai->lulus ? 'bg-label-success' : 'bg-label-danger' }}">
                                                    {{ $nilai->lulus ? 'Lulus' : 'Tidak Lulus' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Data nilai belum tersedia atau belum
                                                dihitung.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Attendance Stats for current class -->
                    <div class="card mb-4">
                        <h5 class="card-header">Statistik Kehadiran</h5>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $stats = [
                                        [
                                            'label' => 'Hadir',
                                            'key' => 'hadir',
                                            'class' => 'primary',
                                            'icon' => 'check-circle',
                                        ],
                                        [
                                            'label' => 'Sakit',
                                            'key' => 'sakit',
                                            'class' => 'warning',
                                            'icon' => 'plus-medical',
                                        ],
                                        ['label' => 'Izin', 'key' => 'izin', 'class' => 'info', 'icon' => 'envelope'],
                                        [
                                            'label' => 'Alpa',
                                            'key' => 'alpha',
                                            'class' => 'danger',
                                            'icon' => 'x-circle',
                                        ],
                                    ];
                                @endphp

                                @foreach ($stats as $s)
                                    <div class="col-sm-6 col-md-3 mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded bg-label-{{ $s['class'] }}">
                                                    <i class="bx bx-{{ $s['icon'] }} fs-4"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">{{ $absensiStats[$s['key']] ?? 0 }}</h5>
                                                <small>{{ $s['label'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('guru.wali-kelas.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <!--/ User Content -->
            </div>
        </div>
    </div>
@endsection
