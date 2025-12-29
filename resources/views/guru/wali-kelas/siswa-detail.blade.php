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
                <div class="col-xl-4 col-lg-5 col-md-5">
                    <x-card>
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
                        <div class="d-flex justify-content-around flex-wrap my-4 py-3 border-top border-bottom">
                            <div class="d-flex align-items-start me-4 mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded"><i class="bx bx-check bx-sm"></i></span>
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
                    </x-card>
                </div>
                <!--/ User Sidebar -->

                <!-- User Content -->
                <div class="col-xl-8 col-lg-7 col-md-7">
                    <!-- Tabs Navigation -->
                    <div class="nav-gestures-wrapper mb-4">
                        <ul class="nav nav-pills flex-column flex-md-row mb-3" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="pill"
                                    data-bs-target="#navs-akademik" aria-controls="navs-akademik" aria-selected="true">
                                    <i class="bx bx-bar-chart-alt-2 me-1"></i> Akademik
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="pill"
                                    data-bs-target="#navs-kehadiran" aria-controls="navs-kehadiran" aria-selected="false">
                                    <i class="bx bx-user-check me-1"></i> Kehadiran
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content p-0 bg-transparent shadow-none">
                        <!-- Academic/Nilai Summary -->
                        <div class="tab-pane fade show active" id="navs-akademik" role="tabpanel">
                            <x-card title="Ringkasan Nilai Semester Ini">
                                <div class="table-responsive mx-n4">
                                    <table class="table table-hover">
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
                                                    <td><span
                                                            class="fw-bold">{{ number_format($nilai->nilai_akhir, 2) }}</span>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-label-primary">{{ $nilai->getPredikat() }}</span>
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
                                                    <td colspan="4" class="text-center py-4">Data nilai belum tersedia
                                                        atau
                                                        belum dihitung.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </x-card>
                        </div>

                        <!-- Attendance Stats for current class -->
                        <div class="tab-pane fade" id="navs-kehadiran" role="tabpanel">
                            <x-card title="Statistik Kehadiran">
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
                                            [
                                                'label' => 'Izin',
                                                'key' => 'izin',
                                                'class' => 'info',
                                                'icon' => 'envelope',
                                            ],
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
                            </x-card>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('guru.wali-kelas.index') }}" class="btn btn-label-secondary">
                            <i class="bx bx-chevron-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <!--/ User Content -->
            </div>
        </div>
    </div>
@endsection
