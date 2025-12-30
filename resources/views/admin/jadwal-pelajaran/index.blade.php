@extends('layouts.app')

@section('title', 'Manajemen Jadwal Pelajaran')

@section('content')
    <!-- Breadcrumbs -->
    @include('partials.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['name' => 'Jadwal Pelajaran'],
        ],
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Jadwal Pelajaran</h5>
            @can('kelola jadwal')
                <a href="{{ route('admin.jadwal-pelajaran.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Jadwal
                </a>
            @endcan
        </div>

        <div class="card-body">
            <!-- Filter -->
            <form method="GET" action="{{ route('admin.jadwal-pelajaran.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <select name="kelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelas_list as $k)
                                <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="hari" class="form-select">
                            <option value="">Semua Hari</option>
                            <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter me-1"></i> Filter
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
                            <th>Hari & Jam</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengajar</th>
                            <th>Semester</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $data)
                            <tr>
                                <td>{{ $loop->iteration + ($jadwal->currentPage() - 1) * $jadwal->perPage() }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $data->hari }}</span>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($data->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($data->jam_selesai)->format('H:i') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $data->kelas->nama_kelas }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $data->mataPelajaran->nama_mapel }}</div>
                                    <small class="text-muted">{{ $data->mataPelajaran->kode_mapel }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png" alt
                                                class="rounded-circle">
                                        </div>
                                        {{ $data->guru->nama_lengkap }}
                                    </div>
                                </td>
                                <td>
                                    {{ $data->semester }} {{ $data->tahun_ajaran }}
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('kelola jadwal')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.jadwal-pelajaran.show', $data->id) }}">
                                                    <i class="bx bx-show-alt me-1"></i> Lihat Agenda
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.jadwal-pelajaran.edit', $data->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <button type="button" class="dropdown-item btn-delete"
                                                    data-url="{{ route('admin.jadwal-pelajaran.destroy', $data->id) }}"
                                                    data-name="{{ $data->mataPelajaran->nama_mapel }} - {{ $data->kelas->nama_kelas }}"
                                                    data-title="Hapus Jadwal">
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
                                        <i class="bx bx-calendar-x" style="font-size: 48px; color: #d1d5db;"></i>
                                        <p class="mb-0 mt-2 text-muted">Tidak ada jadwal pelajaran yang ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $jadwal->links() }}
            </div>
        </div>
    </div>
@endsection
