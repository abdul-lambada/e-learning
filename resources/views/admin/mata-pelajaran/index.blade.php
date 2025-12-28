@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen Mapel /</span> Daftar Mata Pelajaran
    </h4>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Mata Pelajaran</h5>
            @can('kelola mapel')
                <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Mapel
                </a>
            @endcan
        </div>

        <div class="card-body">
            <!-- Search & Filter -->
            <form method="GET" action="{{ route('admin.mata-pelajaran.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama mapel atau kode..."
                            value="{{ request('search') }}">
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
                            <th>Kode</th>
                            <th>Mata Pelajaran</th>
                            <th>Pertemuan</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapel as $data)
                            <tr>
                                <td>{{ $loop->iteration + ($mapel->currentPage() - 1) * $mapel->perPage() }}</td>
                                <td><span class="badge bg-label-primary">{{ $data->kode_mapel }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($data->gambar_cover)
                                            <img src="{{ Storage::url($data->gambar_cover) }}" alt="cover"
                                                class="rounded me-2" width="40" height="40"
                                                style="object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-secondary"><i
                                                        class="bx bx-book"></i></span>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold">{{ $data->nama_mapel }}</span>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($data->deskripsi, 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $data->jumlah_pertemuan }} Pertemuan</td>
                                <td>{{ $data->durasi_pertemuan }} Menit</td>
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
                                            @can('kelola mapel')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.mata-pelajaran.show', $data->id) }}">
                                                    <i class="bx bx-show-alt me-1"></i> Lihat Detail
                                                </a>
                                                @can('kelola mapel')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.mata-pelajaran.edit', $data->id) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </a>
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
                                                <p class="text-center">Apakah Anda yakin ingin menghapus mata pelajaran
                                                    <strong>{{ $data->nama_mapel }}</strong>?
                                                </p>
                                                <div class="alert alert-warning">
                                                    <small><i class="bx bx-info-circle"></i> Mapel tidak dapat dihapus jika
                                                        sedang diajarkan oleh guru.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.mata-pelajaran.destroy', $data) }}"
                                                    method="POST" class="d-inline">
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
                                                <i class="bx bx-book-open" style="font-size: 48px; color: #d1d5db;"></i>
                                                <p class="mb-0 mt-2 text-muted">Tidak ada data mata pelajaran</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $mapel->links() }}
                    </div>
                </div>
            </div>
        @endsection
