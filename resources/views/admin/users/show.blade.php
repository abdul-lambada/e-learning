@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manajemen Pengguna /</span> Detail Pengguna
        </h4>

        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded my-4" src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png"
                                    height="110" width="110" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h4 class="mb-2">{{ $user->nama_lengkap }}</h4>
                                    <span
                                        class="badge bg-label-{{ $user->peran == 'admin' ? 'danger' : ($user->peran == 'guru' ? 'primary' : 'success') }} mt-1">
                                        {{ ucfirst($user->peran) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap my-4 py-3 border-top border-bottom">
                            <div class="d-flex align-items-start me-4 mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded"><i class="bx bx-check"></i></span>
                                <div>
                                    <h5 class="mb-0">Status</h5>
                                    <span>{{ $user->aktif ? 'Aktif' : 'Non-Aktif' }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded"><i class="bx bx-calendar"></i></span>
                                <div>
                                    <h5 class="mb-0">Bergabung</h5>
                                    <span>{{ $user->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-2 border-bottom mb-4">Detail Info</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Username:</span>
                                    <span>{{ $user->username }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Email:</span>
                                    <span>{{ $user->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Jenis Kelamin:</span>
                                    <span>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">No. Telepon:</span>
                                    <span>{{ $user->no_telepon ?? '-' }}</span>
                                </li>
                                @if ($user->peran == 'siswa')
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">NIS:</span>
                                        <span>{{ $user->nis ?? '-' }}</span>
                                    </li>
                                @endif
                                @if ($user->peran == 'guru')
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">NIP:</span>
                                        <span>{{ $user->nip ?? '-' }}</span>
                                    </li>
                                @endif
                            </ul>
                            @can('kelola pengguna')
                                <div class="d-flex justify-content-center pt-3">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary me-3">Edit</a>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary">Kembali</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- Activity Timeline -->
                <div class="card mb-4">
                    <h5 class="card-header">Biodata Lengkap</h5>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Tempat Lahir</dt>
                            <dd class="col-sm-9">{{ $user->tempat_lahir ?? '-' }}</dd>

                            <dt class="col-sm-3">Tanggal Lahir</dt>
                            <dd class="col-sm-9">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : '-' }}
                            </dd>

                            <dt class="col-sm-3">Alamat</dt>
                            <dd class="col-sm-9">{{ $user->alamat ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

                @if ($user->peran == 'guru')
                    <div class="card mb-4">
                        <h5 class="card-header">Jadwal Mengajar</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless border-bottom">
                                <thead>
                                    <tr>
                                        <th>Mapel</th>
                                        <th>Kelas</th>
                                        <th>Hari</th>
                                        <th>Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->guruMengajar as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                            <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                            <td>{{ $jadwal->hari }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada jadwal.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($user->peran == 'siswa')
                    <div class="card mb-4">
                        <h5 class="card-header">Kelas Terdaftar</h5>
                        <div class="card-body">
                            @if ($user->kelas->count() > 0)
                                @foreach ($user->kelas as $k)
                                    <div class="alert alert-primary mb-0">
                                        <i class="bx bx-buildings me-1"></i>
                                        <strong>{{ $k->nama_kelas }}</strong> ({{ $k->tahun_ajaran }} -
                                        {{ $k->pivot->semester ?? '-' }})
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Siwa ini belum masuk kelas manapun.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <!--/ User Content -->
        </div>
    </div>
@endsection
