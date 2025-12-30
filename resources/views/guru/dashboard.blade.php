@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-lg-8 mb-4 order-0">
            <div class="card bg-label-primary shadow-none border-0">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body py-4">
                            <h4 class="card-title text-primary mb-1">Selamat Datang, {{ auth()->user()->nama_lengkap }}!
                                👨‍🏫</h4>
                            <p class="mb-4">
                                Anda mengampu <span class="fw-bold">{{ $totalKelas }} kelas</span> dan
                                <span class="fw-bold">{{ $totalMapel }} mata pelajaran</span>.
                                @if ($jadwalHariIni->count() > 0)
                                    Hari ini Anda memiliki <span class="fw-bold">{{ $jadwalHariIni->count() }} sesi</span>
                                    mengajar.
                                @else
                                    Tidak ada jadwal mengajar tetap untuk hari ini.
                                @endif
                            </p>
                            <a href="{{ route('guru.jadwal.index') }}" class="btn btn-primary">Lihat Jadwal Saya</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/man-with-laptop-light.png"
                                height="150" alt="Dashboard Illustration" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <x-card class="h-100">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0 bg-label-warning p-1 rounded">
                                <i class='bx bx-file fs-3 text-warning'></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Perlu Dinilai</span>
                        <h3 class="card-title mb-2 text-warning">{{ $tugasPerluDinilai }}</h3>
                        <small class="text-muted text-nowrap">Tugas Siswa</small>
                    </x-card>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <x-card class="h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0 bg-label-info p-1 rounded">
                                    <i class='bx bx-user-check fs-3 text-info'></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Verifikasi</span>
                            <h3 class="card-title mb-2 text-info">{{ $absensiPerluVerifikasi }}</h3>
                            <small class="text-muted">Absensi</small>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Left Column -->
        <div class="col-lg-8">

            <!-- Alerts for Teacher -->
            @if ($absensiPerluVerifikasi > 0)
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <span class="badge badge-center rounded-pill bg-info me-3"><i class="bx bx-info-circle"></i></span>
                    <div class="d-flex flex-column ps-1">
                        <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">Verifikasi Absensi Diperlukan</h6>
                        <span>Ada {{ $absensiPerluVerifikasi }} permintaan izin/sakit siswa yang belum Anda
                            verifikasi.</span>
                    </div>
                    <a href="{{ route('guru.absensi.verifikasi.index') }}" class="btn btn-info btn-sm ms-auto">Buka</a>
                </div>
            @endif



            <!-- Upcoming Classes / Schedule Today -->
            <x-card title="Aktivitas Mengajar Hari Ini">
                <x-slot name="headerAction">
                    <span class="badge bg-label-secondary">{{ now()->format('d M, Y') }}</span>
                </x-slot>
                <div class="table-responsive">
                    <table class="table table-borderless border-top">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni as $j)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial rounded bg-label-primary me-2 p-2">
                                                <i class="bx bx-book-content"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $j->mataPelajaran->nama_mapel }}</h6>
                                                <small class="text-muted">Kelas {{ $j->kelas->nama_kelas }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $now = now()->toTimeString();
                                            $isNow = $now >= $j->jam_mulai && $now <= $j->jam_selesai;
                                        @endphp
                                        @if ($isNow)
                                            <span class="badge bg-label-success">Sedang Berlangsung</span>
                                        @else
                                            <span class="badge bg-label-secondary">Terjadwal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.jadwal.show', $j->id) }}"
                                            class="btn btn-sm btn-label-primary">Masuk</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class='bx bx-calendar-x fs-1 text-muted'></i>
                                        <p class="text-muted mt-2">Tidak ada jadwal mengajar untuk hari ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Recent Submissions Activity -->
            <x-card title="Pengumpulan Tugas Terbaru">
                <x-slot name="headerAction">
                    <small class="text-muted">5 Aktivitas Terakhir</small>
                </x-slot>
                <div class="list-group list-group-flush">
                    @forelse($pengumpulanTerbaru as $p)
                        <div
                            class="list-group-item list-group-item-action d-flex align-items-center px-0 py-3 border-bottom">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class='bx bx-user'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0 fw-bold">{{ $p->siswa->nama_lengkap }}</h6>
                                    <small class="text-muted">{{ $p->tugas->judul }} • Mapel:
                                        {{ $p->tugas->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</small>
                                </div>
                                <div class="user-progress d-flex align-items-center">
                                    <small class="text-muted me-3">{{ $p->created_at->diffForHumans() }}</small>
                                    <a href="{{ route('guru.tugas.show', $p->tugas_id) }}"
                                        class="btn btn-xs btn-outline-info">Nilai</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            Belum ada aktivitas pengumpulan tugas.
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats Cards -->
            <x-card title="Ringkasan Statistik">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar bg-label-primary me-3 p-2 rounded">
                        <i class='bx bxs-school fs-3'></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $totalKelas }}</h5>
                        <small>Total Kelas Diampu</small>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar bg-label-info me-3 p-2 rounded">
                        <i class='bx bxs-calendar-event fs-3'></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $totalPertemuan }}</h5>
                        <small>Total Sesi Pertemuan</small>
                    </div>
                </div>
            </x-card>

            <!-- Quick Actions -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('guru.jadwal.index') }}"
                            class="btn btn-primary d-flex align-items-center justify-content-center">
                            <i class="bx bx-plus me-2"></i> Buat Pertemuan Baru
                        </a>
                        <a href="{{ route('guru.laporan.nilai') }}"
                            class="btn btn-outline-primary d-flex align-items-center justify-content-center">
                            <i class="bx bx-bar-chart-alt me-2"></i> Lihat Rekap Nilai
                        </a>
                        <a href="{{ route('guru.laporan.absensi') }}"
                            class="btn btn-outline-primary d-flex align-items-center justify-content-center">
                            <i class="bx bx-list-check me-2"></i> Rekap Absensi
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status/Tip -->
            <div class="card bg-label-secondary">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Tips Pengajaran 💡</h6>
                    <p class="small mb-0">
                        Gunakan fitur <strong>Materi</strong> untuk mengunggah bahan ajar sebelum kelas dimulai agar siswa
                        dapat melakukan persiapan mandiri.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <!-- Weekly Activity Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Aktivitas Mingguan</h5>
                        <small class="text-muted">Statistik mengajar 7 hari terakhir</small>
                    </div>
                    <span class="badge bg-primary">
                        <i class="bx bx-trending-up me-1"></i> Minggu Ini
                    </span>
                </div>
                <div class="card-body">
                    <div id="guruWeeklyActivityChart"></div>
                </div>
            </div>
        </div>

        <!-- Task Status Distribution Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Pengumpulan Tugas</h5>
                    <small class="text-muted">Distribusi status tugas siswa</small>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div id="taskStatusChart" style="min-height: 220px;"></div>
                </div>
                <div class="card-footer border-top pt-3">
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <span class="badge bg-label-success rounded-pill px-2">Dinilai</span>
                            <h5 class="mb-0 mt-1">{{ $statusTugas['data'][0] ?? 0 }}</h5>
                        </div>
                        <div>
                            <span class="badge bg-label-warning rounded-pill px-2">Belum</span>
                            <h5 class="mb-0 mt-1">{{ $statusTugas['data'][1] ?? 0 }}</h5>
                        </div>
                        <div>
                            <span class="badge bg-label-danger rounded-pill px-2">Telat</span>
                            <h5 class="mb-0 mt-1">{{ $statusTugas['data'][2] ?? 0 }}</h5>
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
        .list-group-item:last-child {
            border-bottom: 0 !important;
        }

        .badge-center {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Data
            var chartData = {
                pertemuan: {!! json_encode($weeklyData['pertemuan'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                tugas: {!! json_encode($weeklyData['tugas'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                kuis: {!! json_encode($weeklyData['kuis'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                labels: {!! json_encode($weekLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
                statusData: {!! json_encode($statusTugas['data'] ?? [0, 0, 0]) !!},
                statusLabels: {!! json_encode($statusTugas['labels'] ?? ['Dinilai', 'Belum Dinilai', 'Terlambat']) !!}
            };

            // Weekly Activity Chart
            const weeklyOptions = {
                series: [{
                    name: 'Pertemuan',
                    data: chartData.pertemuan
                }, {
                    name: 'Tugas Masuk',
                    data: chartData.tugas
                }, {
                    name: 'Kuis Dikerjakan',
                    data: chartData.kuis
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    stacked: false
                },
                colors: ['#696cff', '#03c3ec', '#71dd37'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 4
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
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
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " aktivitas";
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3
                }
            };

            new ApexCharts(document.querySelector("#guruWeeklyActivityChart"), weeklyOptions).render();

            // Task Status Donut Chart
            const statusOptions = {
                series: chartData.statusData,
                chart: {
                    type: 'donut',
                    height: 220
                },
                labels: chartData.statusLabels,
                colors: ['#71dd37', '#ffab00', '#ff3e1d'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '12px'
                                },
                                value: {
                                    show: true,
                                    fontSize: '18px',
                                    fontWeight: 700
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
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
                }
            };

            new ApexCharts(document.querySelector("#taskStatusChart"), statusOptions).render();
        });
    </script>
@endpush
