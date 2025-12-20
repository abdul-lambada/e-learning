@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->nama_lengkap }}! 👋</h5>
                            <p class="mb-4">
                                Anda mengampu <span class="fw-bold">{{ $total_kelas }} kelas</span> dan
                                <span class="fw-bold">{{ $total_mapel }} mata pelajaran</span>.
                                Semangat mengajar hari ini!
                            </p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Lihat Jadwal Mengajar</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/man-with-laptop-light.png"
                                height="140" alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class='bx bxs-buildings' style="font-size: 40px; color: #696cff;"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Kelas Diampu</span>
                            <h3 class="card-title mb-2">{{ $totalKelas }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-check"></i> Aktif</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class='bx bxs-book' style="font-size: 40px; color: #03c3ec;"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Mata Pelajaran</span>
                            <h3 class="card-title mb-2">{{ $totalMapel }}</h3>
                            <small class="text-info fw-semibold">Total Mapel</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-calendar-event' style="font-size: 40px; color: #ffab00;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Total Pertemuan</span>
                    <h3 class="card-title text-nowrap mb-2">{{ $totalPertemuan }}</h3>
                    <small class="text-muted">Sesi dibuat</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-file-doc' style="font-size: 40px; color: #28c76f;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Perlu Dinilai</span>
                    <h3 class="card-title text-nowrap mb-2">{{ $tugasPerluDinilai }}</h3>
                    <small class="text-danger">Submission</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-edit' style="font-size: 40px; color: #ea5455;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Kuis Berlangsung</span>
                    <h3 class="card-title text-nowrap mb-2">-</h3>
                    <small class="text-muted">Coming Soon</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-notepad' style="font-size: 40px; color: #7367f0;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Ujian Mendatang</span>
                    <h3 class="card-title text-nowrap mb-2">-</h3>
                    <small class="text-muted">Coming Soon</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Jadwal Mengajar Hari Ini -->
        <div class="col-md-6 col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Jadwal Mengajar Hari Ini</h5>
                    <a href="{{ route('guru.jadwal.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @forelse($jadwalHariIni as $jadwal)
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1 border-bottom">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-book"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $jadwal->mataPelajaran->nama_mapel }}</h6>
                                        <small class="text-muted">Kelas {{ $jadwal->kelas->nama_kelas }}</small>
                                    </div>
                                    <div class="user-progress text-end">
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</h6>
                                        <a href="{{ route('guru.jadwal.show', $jadwal->id) }}"
                                            class="btn btn-xs btn-outline-primary mt-1">Masuk Kelas</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @empty
                        <div class="alert alert-info" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            Tidak ada jadwal mengajar hari ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('guru.jadwal.index') }}" class="btn btn-primary">
                            <i class="bx bx-calendar-plus me-1"></i> Lihat Jadwal & Buat Pertemuan
                        </a>
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="bx bx-check-circle me-1"></i> Rekap Absensi (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
