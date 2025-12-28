@extends('layouts.admin')

@section('title', 'Detail Jadwal Pelajaran')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manajemen Jadwal /</span> Detail Jadwal
        </h4>

        <div class="row">
            <!-- Schedule Info -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-xl bg-label-primary mx-auto rounded p-2 mb-2">
                                <i class="bx bx-calendar-check fs-1"></i>
                            </div>
                            <h5 class="mb-0">{{ $jadwalPelajaran->mataPelajaran->nama_mapel }}</h5>
                            <span class="text-muted">Kelas {{ $jadwalPelajaran->kelas->nama_kelas }}</span>
                        </div>

                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Guru Pengampu:</span>
                                    <span>{{ $jadwalPelajaran->guru->nama_lengkap }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Hari & Jam:</span>
                                    <span class="badge bg-label-success">{{ $jadwalPelajaran->hari }},
                                        {{ \Carbon\Carbon::parse($jadwalPelajaran->jam_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($jadwalPelajaran->jam_selesai)->format('H:i') }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Semester / Tahun:</span>
                                    <span>{{ $jadwalPelajaran->semester }} / {{ $jadwalPelajaran->tahun_ajaran }}</span>
                                </li>
                            </ul>
                            @can('kelola jadwal')
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.jadwal-pelajaran.edit', $jadwalPelajaran->id) }}"
                                        class="btn btn-primary">Edit Jadwal</a>
                                    <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                                        class="btn btn-outline-secondary">Kembali</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Learning History (Pertemuan) -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Riwayat Pertemuan</h5>
                        <span class="badge bg-label-primary rounded-pill">{{ $jadwalPelajaran->pertemuan->count() }}
                            Sesi</span>
                    </div>
                    <div class="card-body">
                        @if ($jadwalPelajaran->pertemuan->count() > 0)
                            <div class="timeline-basic mb-0">
                                @foreach ($jadwalPelajaran->pertemuan as $pertemuan)
                                    <div class="timeline-item timeline-item-transparent text-transparent">
                                        <span class="timeline-point timeline-point-primary"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0">{{ $pertemuan->judul_pertemuan }}</h6>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->format('d M Y') }}</small>
                                            </div>
                                            <p class="mb-2">
                                                <span class="badge bg-label-info">{{ $pertemuan->materi->count() }}
                                                    Materi</span>
                                                <span class="badge bg-label-warning">{{ $pertemuan->tugas->count() }}
                                                    Tugas</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bx bx-calendar-x fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada pertemuan yang dilaksanakan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
