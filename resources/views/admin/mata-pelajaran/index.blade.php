@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
    <!-- Breadcrumbs -->
    @include('partials.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['name' => 'Mata Pelajaran'],
        ],
    ])

    <x-card title="Daftar Mata Pelajaran">
        <x-slot name="headerAction">
            @can('kelola mapel')
                <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Mapel
                </a>
            @endcan
        </x-slot>

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
                    <x-button icon="bx-search" class="w-100">Filter</x-button>
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
                                            class="rounded me-2" width="40" height="40" style="object-fit: cover;">
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
                                        <a class="dropdown-item"
                                            href="{{ route('admin.mata-pelajaran.show', $data->id) }}">
                                            <i class="bx bx-show-alt me-1"></i> Lihat Detail
                                        </a>
                                        @can('kelola mapel')
                                            <a class="dropdown-item"
                                                href="{{ route('admin.mata-pelajaran.edit', $data->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <button type="button" class="dropdown-item btn-delete"
                                                data-url="{{ route('admin.mata-pelajaran.destroy', $data->id) }}"
                                                data-name="{{ $data->nama_mapel }}" data-title="Hapus Mata Pelajaran">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
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
        {{ $mapel->links() }}
    </x-card>
@endsection
