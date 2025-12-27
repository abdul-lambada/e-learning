@extends('layouts.guru')

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
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0 bg-label-warning p-1 rounded">
                                    <i class='bx bx-file fs-3 text-warning'></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Perlu Dinilai</span>
                            <h3 class="card-title mb-2 text-warning">{{ $tugasPerluDinilai }}</h3>
                            <small class="text-muted text-nowrap">Tugas Siswa</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
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
                    </div>
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

            @if ($isWaliKelas)
                <!-- Wali Kelas Card -->
                <div class="card border-primary mb-4 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar avatar-lg bg-label-primary me-3 p-2 rounded">
                            <i class="bx bx-group fs-2"></i>
                        </div>
                        <div class="me-3">
                            <h5 class="mb-0 fw-bold">Wali Kelas: {{ $totalSiswaBinaan }} Siswa</h5>
                            <small class="text-muted">Pantau perkembangan akademik dan kehadiran siswa binaan Anda.</small>
                        </div>
                        <a href="{{ route('guru.wali-kelas.index') }}" class="btn btn-primary btn-sm ms-auto">Kelola
                            Siswa</a>
                    </div>
                </div>
            @endif

            <!-- Upcoming Classes / Schedule Today -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Aktivitas Mengajar Hari Ini</h5>
                    <span class="badge bg-label-secondary">{{ now()->format('d M, Y') }}</span>
                </div>
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
            </div>

            <!-- Recent Submissions Activity -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pengumpulan Tugas Terbaru</h5>
                    <small class="text-muted">5 Aktivitas Terakhir</small>
                </div>
                <div class="card-body pt-0">
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
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats Cards -->
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <h5 class="card-title mb-0">Ringkasan Statistik</h5>
                </div>
                <div class="card-body">
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
                </div>
            </div>

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
@endsection

@push('page-style')
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
