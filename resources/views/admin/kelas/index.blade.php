@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kelas</h5>
            @can('tambah kelas')
                <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kelas
                </a>
            @endcan
        </div>

        <div class="card-body">
            <!-- Search & Filter -->
            <form method="GET" action="{{ route('admin.kelas.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama kelas, kode, tahun ajaran..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="tingkat" class="form-select">
                            <option value="">Semua Tingkat</option>
                            <option value="10" {{ request('tingkat') == '10' ? 'selected' : '' }}>Kelas 10</option>
                            <option value="11" {{ request('tingkat') == '11' ? 'selected' : '' }}>Kelas 11</option>
                            <option value="12" {{ request('tingkat') == '12' ? 'selected' : '' }}>Kelas 12</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Kelas</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Jumlah Siswa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $data)
                            <tr>
                                <td>{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                                <td><span class="fw-bold">{{ $data->kode_kelas }}</span></td>
                                <td>{{ $data->nama_kelas }}</td>
                                <td>{{ $data->tingkat }}</td>
                                <td>
                                    @if ($data->waliKelas)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png" alt
                                                    class="rounded-circle">
                                            </div>
                                            {{ $data->waliKelas->nama_lengkap }}
                                        </div>
                                    @else
                                        <span class="text-danger">Belum diset</span>
                                    @endif
                                </td>
                                <td>{{ $data->tahun_ajaran }}</td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $data->users->count() }} Siswa</span>
                                </td>
                                <td>
                                    @if ($data->aktif)
                                        <span class="badge bg-label-success">Aktif</span>
                                    @else
                                        <span class="badge bg-label-secondary">Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('ubah kelas')
                                                <a class="dropdown-item" href="{{ route('admin.kelas.edit', $data) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            @endcan
                                            @can('hapus kelas')
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $data->id }}">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <i class="bx bx-error-circle" style="font-size: 64px; color: #ea5455;"></i>
                                            </div>
                                            <p class="text-center">Apakah Anda yakin ingin menghapus kelas
                                                <strong>{{ $data->nama_kelas }}</strong>?</p>
                                            <div class="alert alert-warning">
                                                <small><i class="bx bx-info-circle"></i> Kelas tidak dapat dihapus jika
                                                    masih memiliki siswa.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.kelas.destroy', $data) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger">Ya, Hapus!</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="bx bx-buildings" style="font-size: 48px; color: #d1d5db;"></i>
                                        <p class="mb-0 mt-2 text-muted">Tidak ada data kelas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $kelas->links() }}
            </div>
        </div>
    </div>
@endsection
