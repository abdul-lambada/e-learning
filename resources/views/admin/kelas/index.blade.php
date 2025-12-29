@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
    <!-- Breadcrumbs -->
    @include('partials.breadcrumbs', [
        'breadcrumbs' => [['name' => 'Dashboard', 'url' => route('admin.dashboard')], ['name' => 'Kelas']],
    ])

    <x-card title="Daftar Kelas">
        <x-slot name="headerAction">
            @can('kelola kelas')
                <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kelas
                </a>
            @endcan
        </x-slot>

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
                        <th>Kode Kelas</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
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
                                        <a class="dropdown-item" href="{{ route('admin.kelas.show', $data->id) }}">
                                            <i class="bx bx-show-alt me-1"></i> Lihat Detail
                                        </a>
                                        @can('kelola kelas')
                                            <a class="dropdown-item" href="{{ route('admin.kelas.edit', $data->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <button type="button" class="dropdown-item btn-delete"
                                                data-url="{{ route('admin.kelas.destroy', $data->id) }}"
                                                data-name="{{ $data->nama_kelas }}" data-title="Hapus Kelas">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
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
    </x-card>
@endsection
