@extends('layouts.app')

@section('title', 'Informasi Kelas')

@section('content')
    <div class="row">
        <!-- Informasi Utama Kelas -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-start">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class='bx bx-buildings fs-1'></i></span>
                            </div>
                            <div class="me-2">
                                <h4 class="mb-1">{{ $kelas->nama_kelas }}</h4>
                                <div class="text-muted mb-1">{{ $kelas->tingkat }} | {{ $kelas->jurusan }}</div>
                                <span class="badge bg-label-success">Tahun Ajaran {{ $kelas->tahun_ajaran }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">Wali Kelas</h6>
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="avatar avatar-xs me-2">
                                    <img src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}" alt
                                        class="rounded-circle">
                                </div>
                                <span class="fw-semibold">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum ditentukan' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Pengajar & Mapel -->
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Mata Pelajaran & Guru</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Guru Pengajar</th>
                                <th>Jadwal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas->guruMengajar as $jadwal)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span
                                                    class="avatar-initial rounded-circle bg-label-info">{{ substr($jadwal->mataPelajaran->nama_mapel, 0, 1) }}</span>
                                            </div>
                                            <strong>{{ $jadwal->mataPelajaran->nama_mapel }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $jadwal->guru->nama_lengkap }}</td>
                                    <td>
                                        <span class="badge bg-label-secondary">
                                            {{ $jadwal->hari }}, {{ $jadwal->jam_mulai->format('H:i') }} -
                                            {{ $jadwal->jam_selesai->format('H:i') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada jadwal pelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Teman Sekelas -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Teman Sekelas ({{ $temanSekelas->count() }})</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($temanSekelas->take(10) as $teman)
                            <li class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-sm me-3">
                                    <img src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}" alt
                                        class="rounded-circle">
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $teman->nama_lengkap }}</span>
                                    <small class="text-muted">{{ $teman->nis }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted">Belum ada siswa lain di kelas ini.</li>
                        @endforelse

                        @if ($temanSekelas->count() > 10)
                            <li class="text-center mt-3">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTemanKelas">Lihat Semua</button>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Teman Sekelas (Full List) -->
    <div class="modal fade" id="modalTemanKelas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Teman Sekelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($temanSekelas as $teman)
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}" alt
                                        class="rounded-circle">
                                </div>
                                <div>
                                    <span class="fw-semibold">{{ $teman->nama_lengkap }}</span><br>
                                    <small class="text-muted">NIS: {{ $teman->nis }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
