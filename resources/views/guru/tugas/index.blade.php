@extends('layouts.guru')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manajemen Pembelajaran /</span> Daftar Tugas
        </h4>

        <div class="card">
            <h5 class="card-header">Daftar Tugas Saya</h5>
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
                                            <a class="dropdown-item" href="{{ route('guru.tugas.show', $t->id) }}"><i
                                                    class="bx bx-show-alt me-1"></i> Detail & Nilai</a>
                                            @can('kelola tugas')
                                                <a class="dropdown-item" href="{{ route('guru.tugas.edit', $t->id) }}"><i
                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                <form action="{{ route('guru.tugas.destroy', $t->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i
                                                            class="bx bx-trash me-1"></i> Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada tugas yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-3">
                {{ $tugas->links() }}
            </div>
        </div>
    </div>
@endsection
