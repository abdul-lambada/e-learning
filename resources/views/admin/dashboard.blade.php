@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->nama_lengkap }}! 🎉</h5>
                            <p class="mb-4">
                                Anda login sebagai <span class="fw-bold">Administrator</span>.
                                Kelola sistem E-Learning dengan mudah dan efisien.
                            </p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Lihat Statistik</a>
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
                                    <i class='bx bxs-user-circle' style="font-size: 40px; color: #696cff;"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Siswa</span>
                            <h3 class="card-title mb-2">{{ $total_siswa }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Siswa Aktif</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class='bx bxs-user-badge' style="font-size: 40px; color: #03c3ec;"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Guru</span>
                            <h3 class="card-title mb-2">{{ $total_guru }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Guru Aktif</small>
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
                            <i class='bx bxs-buildings' style="font-size: 40px; color: #ffab00;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Total Kelas</span>
                    <h3 class="card-title text-nowrap mb-2">{{ $total_kelas }}</h3>
                    <small class="text-muted">Kelas Aktif</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-book' style="font-size: 40px; color: #28c76f;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Mata Pelajaran</span>
                    <h3 class="card-title text-nowrap mb-2">{{ $total_mapel }}</h3>
                    <small class="text-muted">Mapel Aktif</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-calendar-check' style="font-size: 40px; color: #ea5455;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Absensi Hari Ini</span>
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Dari {{ $total_siswa }} siswa</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-file-doc' style="font-size: 40px; color: #7367f0;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Tugas Aktif</span>
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Tugas Berjalan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Aktivitas Terbaru</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="activityDropdown" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="activityDropdown">
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-user"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">User baru terdaftar</h6>
                                    <small class="text-muted">Siswa baru: Andi Wijaya</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">Baru saja</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-book"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Mata pelajaran baru</h6>
                                    <small class="text-muted">Matematika ditambahkan</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">2 jam lalu</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-buildings"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Kelas dibuat</h6>
                                    <small class="text-muted">X IPA 1 berhasil dibuat</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">5 jam lalu</small>
                                </div>
                            </div>
                        </li>
                    </ul>
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
                        @can('tambah pengguna')
                            <a href="#" class="btn btn-primary">
                                <i class="bx bx-user-plus me-1"></i> Tambah Pengguna
                            </a>
                        @endcan

                        @can('tambah kelas')
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bx bx-buildings me-1"></i> Tambah Kelas
                            </a>
                        @endcan

                        @can('tambah mata pelajaran')
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bx bx-book me-1"></i> Tambah Mata Pelajaran
                            </a>
                        @endcan

                        @can('lihat laporan')
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="bx bx-file me-1"></i> Lihat Laporan
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
