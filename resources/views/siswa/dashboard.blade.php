@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-lg-8 mb-4 order-0">
            <div class="card bg-label-primary shadow-none border-0">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body py-4">
                            <h4 class="card-title text-primary mb-1">Selamat Datang Kembali,
                                {{ auth()->user()->nama_lengkap }}! 👋</h4>
                            <p class="mb-4">
                                @if ($kelas)
                                    Anda terdaftar di kelas <span class="fw-bold">{{ $kelas->nama_kelas }}</span>.
                                    @if ($jadwalHariIni->count() > 0)
                                        Hari ini ada <span class="fw-bold">{{ $jadwalHariIni->count() }}</span> mata
                                        pelajaran menanti Anda.
                                    @else
                                        Hari ini tidak ada jadwal pelajaran tetap.
                                    @endif
                                @else
                                    Akun Anda belum terhubung ke kelas manapun.
                                @endif
                            </p>
                            <a href="{{ route('siswa.pembelajaran.index') }}" class="btn btn-primary">Buka Ruang Belajar</a>
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

        <!-- Quick Stats Summary -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0 bg-label-success p-1 rounded">
                                    <i class='bx bx-check-circle fs-3 text-success'></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Kehadiran</span>
                            <h3 class="card-title mb-2 text-success">{{ number_format($persenHadir, 0) }}%</h3>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenHadir }}%"
                                    aria-valuenow="{{ $persenHadir }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0 bg-label-info p-1 rounded">
                                    <i class='bx bx-line-chart fs-3 text-info'></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Rata-rata</span>
                            <h3 class="card-title mb-2 text-info">{{ number_format($avgNilai, 1) }}</h3>
                            <small class="text-muted">Nilai Akhir</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Col -->
        <div class="col-lg-8">
            <!-- Upcoming Exams/Ujian -->
            @if ($ujianTerdekat->count() > 0)
                <div class="card border-primary mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary fw-bold"><i class="bx bx-error-circle me-1"></i> Jadwal Ujian Terdekat
                        </h5>
                        <span class="badge bg-danger">Penting</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless border-top">
                            <tbody class="table-border-bottom-0">
                                @foreach ($ujianTerdekat as $uj)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-initial rounded bg-label-danger me-3 p-2">
                                                    <i class="bx bx-notepad"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $uj->ujian->nama_ujian }}</h6>
                                                    <small
                                                        class="text-muted">{{ $uj->ujian->mataPelajaran->nama_mapel }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span
                                                    class="d-block fw-bold">{{ $uj->tanggal_ujian->format('d M Y') }}</span>
                                                <small>{{ $uj->jam_mulai->format('H:i') }} -
                                                    {{ $uj->jam_selesai->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('siswa.ujian.show', $uj->id) }}"
                                                class="btn btn-sm btn-outline-danger">Persiapan</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tugas Pending -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tugas yang Belum Selesai</h5>
                    <a href="{{ route('siswa.tugas.index') }}" class="btn btn-xs btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        @forelse($tugasPending as $t)
                            <div
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-transparent px-0 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-warning"><i
                                                class='bx bx-file'></i></span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $t->judul }}</h6>
                                        <small
                                            class="text-muted">{{ $t->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}
                                            • <span class="text-danger">Deadline:
                                                {{ $t->tanggal_deadline->diffForHumans() }}</span></small>
                                    </div>
                                </div>
                                <a href="{{ route('siswa.tugas.show', $t->id) }}"
                                    class="btn btn-sm btn-label-warning">Kumpulkan</a>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/girl-doing-yoga-light.png"
                                    width="120" alt="No pending tasks">
                                <p class="mt-2 mb-0">Semua tugas sudah dikumpulkan!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Nilai Terbaru -->
            <div class="card">
                <h5 class="card-header">Postingan Nilai Terbaru</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mapel</th>
                                <th>Item</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($nilaiTerbaru as $n)
                                <tr>
                                    <td>{{ $n['mapel'] }}</td>
                                    <td>
                                        <span class="badge bg-label-secondary me-1">{{ $n['jenis'] }}</span>
                                        <small class="text-truncate d-inline-block"
                                            style="max-width: 150px;">{{ $n['judul'] }}</small>
                                    </td>
                                    <td><span
                                            class="fw-bold fs-5 @if ($n['nilai'] >= 75) text-success @else text-warning @endif">{{ $n['nilai'] }}</span>
                                    </td>
                                    <td>
                                        @if ($n['nilai'] >= 75)
                                            <span class="text-success"><i class="bx bx-check me-1"></i> Tuntas</span>
                                        @else
                                            <span class="text-warning"><i class="bx bx-info-circle me-1"></i> Perlu
                                                Review</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada nilai yang
                                        dipublikasikan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Col -->
        <div class="col-lg-4">
            <!-- Today's Classes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelas Hari Ini</h5>
                    <span class="badge bg-label-primary rounded-pill">{{ now()->format('d M') }}</span>
                </div>
                <div class="card-body">
                    <ul class="timeline mb-0">
                        @forelse($jadwalHariIni as $j)
                            <li class="timeline-item timeline-item-transparent pb-3">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0 fw-bold text-primary">{{ $j->mataPelajaran->nama_mapel }}</h6>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}</small>
                                    </div>
                                    <p class="mb-2">{{ $j->guru->nama_lengkap }}</p>
                                    <a href="{{ route('siswa.pembelajaran.show', $j->id) }}"
                                        class="btn btn-xs btn-primary">Buka</a>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-3">
                                <i class='bx bx-coffee fs-1 text-muted'></i>
                                <p class="text-muted mt-2 small">Tidak ada jadwal kuliah hari ini.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-danger me-3 p-2 rounded">
                            <i class='bx bxs-game fs-3'></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $kuisAktif }}</h5>
                            <small>Kuis Berlangsung</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-primary me-3 p-2 rounded">
                            <i class='bx bxs-book-content fs-3'></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $kelas ? $kelas->guruMengajar()->count() : 0 }}</h5>
                            <small>Mata Pelajaran Diikuti</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivational Quote or Info -->
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Tips Hari Ini 💡</h5>
                    <p class="card-text small">
                        "Disiplin adalah kunci kesuksesan. Kumpulkan tugas tepat waktu untuk hasil maksimal."
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .timeline-event {
            border: 1px solid #f2f2f2;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .timeline-item-transparent:last-child {
            border-left-color: transparent !important;
        }
    </style>
@endpush
