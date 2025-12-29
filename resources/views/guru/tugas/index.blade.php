@extends('layouts.guru')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumbs -->
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [['name' => 'Dashboard', 'url' => route('guru.dashboard')], ['name' => 'Tugas']],
        ])

        <x-card title="Daftar Tugas Saya">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jadwal</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($tugas as $t)
                            <tr>
                                <td><strong>{{ $t->judul_tugas }}</strong></td>
                                <td>{{ $t->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</td>
                                <td><span
                                        class="badge bg-label-primary">{{ $t->pertemuan->guruMengajar->kelas->nama_kelas }}</span>
                                </td>
                                <td>{{ $t->pertemuan->tanggal_pertemuan->format('d M Y') }}</td>
                                <td>
                                    @if ($t->tanggal_deadline->isPast())
                                        <span
                                            class="badge bg-label-danger">{{ $t->tanggal_deadline->format('d M Y H:i') }}</span>
                                    @else
                                        <span
                                            class="badge bg-label-success">{{ $t->tanggal_deadline->format('d M Y H:i') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('guru.tugas.show', $t->id) }}">
                                                <i class="bx bx-show-alt me-1"></i> Detail & Nilai
                                            </a>
                                            @can('kelola tugas')
                                                <a class="dropdown-item" href="{{ route('guru.tugas.edit', $t->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <button type="button" class="dropdown-item btn-delete"
                                                    data-url="{{ route('guru.tugas.destroy', $t->id) }}"
                                                    data-name="{{ $t->judul_tugas }}" data-title="Hapus Tugas">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="bx bx-task" style="font-size: 48px; color: #d1d5db;"></i>
                                        <p class="mb-0 mt-2 text-muted">Belum ada tugas yang dibuat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tugas->links() }}
            </div>
        </x-card>
    </div>
@endsection
