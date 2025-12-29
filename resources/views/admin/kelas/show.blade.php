@extends('layouts.admin')

@section('title', 'Detail Kelas')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manajemen Kelas /</span> Detail Kelas
        </h4>

        <div class="row">
            <!-- Class Info -->
            <div class="col-md-5 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-md bg-label-primary me-3 rounded p-2">
                                <i class="bx bx-buildings fs-3"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $kelas->nama_kelas }}</h4>
                                <small class="text-muted">{{ $kelas->kode_kelas }}</small>
                            </div>
                        </div>

                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Tingkat / Jurusan:</span>
                                    <span>Kelas {{ $kelas->tingkat }} - {{ $kelas->jurusan }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Tahun Ajaran:</span>
                                    <span>{{ $kelas->tahun_ajaran }}</span>
                                </li>

                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Status:</span>
                                    <span
                                        class="badge bg-label-{{ $kelas->aktif ? 'success' : 'secondary' }}">{{ $kelas->aktif ? 'Aktif' : 'Non-Aktif' }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold d-block mb-1">Keterangan:</span>
                                    <span>{{ $kelas->keterangan ?? '-' }}</span>
                                </li>
                            </ul>
                            @can('kelola kelas')
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="btn btn-primary">Edit Kelas</a>
                                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">Kembali</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student List -->
            <div class="col-md-7 col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Daftar Siswa</h5>
                        <span class="badge bg-label-primary rounded-pill">{{ $kelas->users->count() }} Siswa</span>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>L/P</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas->users as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span
                                                        class="avatar-initial rounded-circle bg-label-success">{{ substr($siswa->nama_lengkap, 0, 1) }}</span>
                                                </div>
                                                <a href="{{ route('admin.users.show', $siswa->id) }}"
                                                    class="text-body fw-bold">{{ $siswa->nama_lengkap }}</a>
                                            </div>
                                        </td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>{{ $siswa->jenis_kelamin }}</td>
                                        <td>
                                            @if ($siswa->aktif)
                                                <span class="badge bg-label-success">Aktif</span>
                                            @else
                                                <span class="badge bg-label-danger">Non-Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
