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
                            <h3 class="card-title mb-2">{{ $total_kelas }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Kelas Aktif</small>
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
                            <h3 class="card-title mb-2">{{ $total_mapel }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Mapel Aktif</small>
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
                            <i class='bx bxs-user-circle' style="font-size: 40px; color: #ffab00;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Total Siswa</span>
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Siswa Aktif</small>
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
                    <span class="d-block mb-1">Tugas Aktif</span>
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Menunggu Penilaian</small>
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
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Kuis Aktif</small>
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
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Jadwal Ujian</small>
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
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="scheduleDropdown" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="scheduleDropdown">
                            <a class="dropdown-item" href="javascript:void(0);">Lihat Semua Jadwal</a>
                            <a class="dropdown-item" href="javascript:void(0);">Export Jadwal</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        Belum ada jadwal mengajar untuk hari ini.
                    </div>

                    <!-- Example Schedule Item (Hidden by default) -->
                    <div class="d-none">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="bx bx-book"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Matematika - X IPA 1</h6>
                                        <small class="text-muted">Pertemuan ke-5: Trigonometri</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">08:00 - 09:30</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
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
                        @can('tambah pertemuan')
                            <a href="#" class="btn btn-primary">
                                <i class="bx bx-calendar-plus me-1"></i> Buat Pertemuan
                            </a>
                        @endcan

                        @can('tambah materi')
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bx bx-file-plus me-1"></i> Upload Materi
                            </a>
                        @endcan

                        @can('tambah tugas')
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bx bx-task me-1"></i> Buat Tugas Baru
                            </a>
                        @endcan

                        @can('tambah kuis')
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bx bx-edit me-1"></i> Buat Kuis
                            </a>
                        @endcan

                        @can('lihat absensi')
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="bx bx-check-circle me-1"></i> Rekap Absensi
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tugas Menunggu Penilaian -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Tugas Menunggu Penilaian</h5>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Judul Tugas</th>
                                    <th>Deadline</th>
                                    <th>Terkumpul</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="bx bx-check-circle" style="font-size: 48px; color: #28c76f;"></i>
                                            <p class="mb-0 mt-2">Tidak ada tugas yang menunggu penilaian</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
