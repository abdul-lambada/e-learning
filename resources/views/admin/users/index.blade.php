@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pengguna</h5>
            @can('tambah pengguna')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Pengguna
                </a>
            @endcan
        </div>

        <div class="card-body">
            <!-- Search & Filter -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama, email, NIS, NIP..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif
                            </option>
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NIS/NIP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png" alt
                                                class="rounded-circle">
                                        </div>
                                        <div>
                                            <strong>{{ $user->nama_lengkap }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->hasRole('admin'))
                                        <span class="badge bg-label-danger">Admin</span>
                                    @elseif($user->hasRole('guru'))
                                        <span class="badge bg-label-info">Guru</span>
                                    @elseif($user->hasRole('siswa'))
                                        <span class="badge bg-label-success">Siswa</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->nis)
                                        <small class="text-muted">NIS: {{ $user->nis }}</small>
                                    @elseif($user->nip)
                                        <small class="text-muted">NIP: {{ $user->nip }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->aktif)
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
                                            @can('ubah pengguna')
                                                <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            @endcan
                                            @can('hapus pengguna')
                                                @if ($user->id !== auth()->id())
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $user->id }}">
                                                        <i class="bx bx-trash me-1"></i> Hapus
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
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
                                            <p class="text-center">Apakah Anda yakin ingin menghapus pengguna
                                                <strong>{{ $user->nama_lengkap }}</strong>?</p>
                                            <p class="text-center text-muted"><small>Tindakan ini tidak dapat
                                                    dibatalkan!</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ya, Hapus!</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="py-4">
                                        <i class="bx bx-user-x" style="font-size: 48px; color: #d1d5db;"></i>
                                        <p class="mb-0 mt-2 text-muted">Tidak ada data pengguna</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
