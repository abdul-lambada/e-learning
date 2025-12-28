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

    <!-- Charts Row -->
    <div class="row">
        <!-- Weekly Activity Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Aktivitas Mingguan</h5>
                        <small class="text-muted">Statistik aktivitas 7 hari terakhir</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bx bx-calendar me-1"></i> Minggu Ini
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="weeklyActivityChart"></div>
                </div>
            </div>
        </div>

        <!-- User Distribution Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Distribusi Pengguna</h5>
                        <small class="text-muted">Total {{ $total_guru + $total_siswa }} pengguna</small>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div id="userDistributionChart" style="min-height: 250px;"></div>
                </div>
                <div class="card-footer border-top pt-3">
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <span class="badge bg-label-primary rounded-pill px-3">Guru</span>
                            <h4 class="mb-0 mt-2">{{ $total_guru }}</h4>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-label-info rounded-pill px-3">Siswa</span>
                            <h4 class="mb-0 mt-2">{{ $total_siswa }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/apex-charts/apex-charts.css" />
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

@push('scripts')
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Data (prepared from PHP)
            var chartData = {
                absensi: {!! json_encode($weeklyData['absensi'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                tugas: {!! json_encode($weeklyData['tugas'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                kuis: {!! json_encode($weeklyData['kuis'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                labels: {!! json_encode($weekLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
                userDist: {!! json_encode($userDistribution['data'] ?? [0, 0]) !!},
                userLabels: {!! json_encode($userDistribution['labels'] ?? ['Guru', 'Siswa']) !!}
            };

            // Weekly Activity Chart
            const weeklyActivityOptions = {
                series: [{
                    name: 'Absensi',
                    data: chartData.absensi
                }, {
                    name: 'Tugas Dikumpulkan',
                    data: chartData.tugas
                }, {
                    name: 'Kuis Dikerjakan',
                    data: chartData.kuis
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    sparkline: {
                        enabled: false
                    }
                },
                colors: ['#696cff', '#03c3ec', '#71dd37'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: chartData.labels,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.floor(val);
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                tooltip: {
                    shared: true,
                    intersect: false
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3
                }
            };

            const weeklyActivityChart = new ApexCharts(
                document.querySelector("#weeklyActivityChart"),
                weeklyActivityOptions
            );
            weeklyActivityChart.render();

            // User Distribution Chart
            const userDistributionOptions = {
                series: chartData.userDist,
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: chartData.userLabels,
                colors: ['#696cff', '#03c3ec'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 600
                                },
                                value: {
                                    show: true,
                                    fontSize: '22px',
                                    fontWeight: 700,
                                    formatter: function(val) {
                                        return val;
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '14px',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        }
                    }
                }]
            };

            const userDistributionChart = new ApexCharts(
                document.querySelector("#userDistributionChart"),
                userDistributionOptions
            );
            userDistributionChart.render();
        });
    </script>
@endpush
