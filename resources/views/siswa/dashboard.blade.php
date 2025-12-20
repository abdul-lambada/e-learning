@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->nama_lengkap }}! 👋</h5>
                            <p class="mb-4">
                                @if ($kelas)
                                    Anda terdaftar di kelas <span class="fw-bold">{{ $kelas->nama_kelas }}</span>.
                                    Semangat belajar hari ini!
                                @else
                                    Anda belum terdaftar di kelas manapun. Silakan hubungi admin.
                                @endif
                            </p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Lihat Jadwal Pelajaran</a>
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
                                    <i class='bx bxs-task' style="font-size: 40px; color: #696cff;"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Tugas Aktif</span>
                            <h3 class="card-title mb-2">0</h3>
                            <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> Belum
                                Dikerjakan</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class='bx bxs-edit' style="font-size: 40px; color: #03c3ec;"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Kuis Tersedia</span>
                            <h3 class="card-title mb-2">0</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Siap
                                Dikerjakan</small>
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
                            <i class='bx bxs-book' style="font-size: 40px; color: #ffab00;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Mata Pelajaran</span>
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Mapel Aktif</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-check-circle' style="font-size: 40px; color: #28c76f;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Kehadiran</span>
                    <h3 class="card-title text-nowrap mb-2">0%</h3>
                    <small class="text-muted">Persentase Hadir</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bxs-bar-chart-alt-2' style="font-size: 40px; color: #ea5455;"></i>
                        </div>
                    </div>
                    <span class="d-block mb-1">Rata-rata Nilai</span>
                    <h3 class="card-title text-nowrap mb-2">0</h3>
                    <small class="text-muted">Dari semua mapel</small>
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
        <!-- Tugas Terbaru -->
        <div class="col-md-6 col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Tugas Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        Belum ada tugas yang tersedia saat ini.
                    </div>

                    <!-- Example Task Item (Hidden by default) -->
                    <div class="d-none">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-task"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Matematika - Tugas Bab 5</h6>
                                        <small class="text-muted">Deadline: 25 Desember 2025</small>
                                    </div>
                                    <div class="user-progress">
                                        <span class="badge bg-label-warning">Belum Dikerjakan</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">Jadwal Hari Ini</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        Tidak ada jadwal pelajaran hari ini.
                    </div>

                    <!-- Example Schedule (Hidden by default) -->
                    <div class="d-none">
                        <ul class="timeline">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Matematika</h6>
                                        <small class="text-muted">08:00 - 09:30</small>
                                    </div>
                                    <p class="mb-0">Ruang: X IPA 1</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Terbaru -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Nilai Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua Nilai</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Jenis</th>
                                    <th>Judul</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="bx bx-bar-chart-alt-2" style="font-size: 48px; color: #696cff;"></i>
                                            <p class="mb-0 mt-2">Belum ada nilai yang tersedia</p>
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
