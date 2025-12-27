@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-lg-8 mb-4 order-0">
            <div class="card bg-label-secondary shadow-none border-0">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h4 class="card-title text-primary">Selamat Datang, {{ auth()->user()->nama_lengkap }}! 🎉</h4>
                            <p class="mb-4">
                                Anda masuk sebagai <span class="fw-bold">Administrator</span>.
                                Hari ini terdapat <span class="fw-bold">{{ $pertemuan_hari_ini }} pertemuan</span> belajar
                                sedang/akan berlangsung.
                                Sistem berjalan dengan normal.
                            </p>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Kelola Pengguna</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4 text-end">
                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/man-with-laptop-light.png"
                                height="150" alt="Dashboard Illustration" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Stats Header -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-2">
                                <div class="avatar flex-shrink-0 bg-label-primary p-1 rounded">
                                    <i class='bx bx-user fs-3'></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Guru</span>
                            <h3 class="card-title mb-1">{{ $total_guru }}</h3>
                            <small class="text-muted">Total Pengampu</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-2">
                                <div class="avatar flex-shrink-0 bg-label-info p-1 rounded">
                                    <i class='bx bxs-group fs-3 text-info'></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Siswa</span>
                            <h3 class="card-title mb-1">{{ $total_siswa }}</h3>
                            <small class="text-muted">Total Terdaftar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Row -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted d-block">Kelas</span>
                            <div class="d-flex align-items-center mt-1">
                                <h4 class="mb-0 me-2">{{ $total_kelas }}</h4>
                                <span class="badge bg-label-success">Aktif</span>
                            </div>
                        </div>
                        <div class="avatar bg-label-warning p-2">
                            <i class="bx bx-buildings"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted d-block">Mata Pelajaran</span>
                            <div class="d-flex align-items-center mt-1">
                                <h4 class="mb-0 me-2">{{ $total_mapel }}</h4>
                                <span class="badge bg-label-info">Mapel</span>
                            </div>
                        </div>
                        <div class="avatar bg-label-info p-2">
                            <i class="bx bx-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted d-block">Absen Hari Ini</span>
                            <div class="d-flex align-items-center mt-1">
                                <h4 class="mb-0 me-2">{{ $absensi_hari_ini }}</h4>
                                <span class="badge bg-label-danger">Siswa</span>
                            </div>
                        </div>
                        <div class="avatar bg-label-danger p-2">
                            <i class="bx bx-check-double"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted d-block">Tugas Berjalan</span>
                            <div class="d-flex align-items-center mt-1">
                                <h4 class="mb-0 me-2">{{ $tugas_aktif }}</h4>
                                <span class="badge bg-label-primary">Tugas</span>
                            </div>
                        </div>
                        <div class="avatar bg-label-primary p-2">
                            <i class="bx bx-task"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Users -->
        <div class="col-md-6 col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Pengguna Terdaftar Baru</h5>
                        <small class="text-muted">Pantau pendaftaran terbaru</small>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-label-primary">Semua User</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover border-top">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users_terbaru as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar avatar-sm me-2 bg-label-{{ $user->hasRole('admin') ? 'danger' : ($user->hasRole('guru') ? 'primary' : 'success') }}">
                                                <span
                                                    class="avatar-initial rounded-circle">{{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}</span>
                                            </div>
                                            <span class="fw-bold">{{ $user->nama_lengkap }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-label-{{ $user->hasRole('admin') ? 'danger' : ($user->hasRole('guru') ? 'primary' : 'success') }}">
                                            {{ $user->roles->first()->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Aktivitas Terkini</h5>
                        <small class="text-muted">Log sistem terbaru</small>
                    </div>
                    <a href="{{ route('admin.audit-log.index') }}" class="btn btn-sm btn-label-secondary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @foreach ($logs as $log)
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span
                                        class="avatar-initial rounded bg-label-{{ $log->user->peran == 'admin' ? 'danger' : 'primary' }}">
                                        <i class="bx bx-user"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $log->aktivitas }}</h6>
                                        <small class="text-muted">{{ $log->user->nama_lengkap }}</small>
                                    </div>
                                    <div class="user-progress text-end">
                                        <small
                                            class="fw-semibold d-block text-nowrap">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .cursor-pointer {
            cursor: pointer;
            transition: all 0.2s;
        }

        .cursor-pointer:hover {
            transform: scale(1.02);
        }
    </style>
@endpush
