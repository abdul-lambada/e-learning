@extends('layouts.guru')
@section('title', 'Daftar Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Evaluasi /</span> Daftar Ujian</h4>
            @can('kelola ujian')
                <a href="{{ route('guru.ujian.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Buat Ujian
                    Baru</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ujian</th>
                            <th>Kelas & Mapel</th>
                            <th>Jenis</th>
                            <th>Durasi</th>
                            <th>Soal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ujianList as $ujian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $ujian->nama_ujian }}</strong><br><small
                                        class="text-muted">{{ $ujian->kode_ujian }}</small></td>
                                <td>{{ $ujian->kelas->nama_kelas }}<br><small>{{ $ujian->mataPelajaran->nama_mapel }}</small>
                                </td>
                                <td><span class="badge bg-label-info">{{ $ujian->jenis_ujian }}</span></td>
                                <td>{{ $ujian->durasi_menit }} Menit</td>
                                <td>{{ $ujian->jumlah_soal }}</td>
                                <td>{!! $ujian->aktif
                                    ? '<span class="badge bg-label-success">Aktif</span>'
                                    : '<span class="badge bg-label-secondary">Draft</span>' !!}</td>
                                <td>
                                    <a href="{{ route('guru.ujian.show', $ujian->id) }}"
                                        class="btn btn-sm btn-icon btn-outline-primary" title="Detail & Soal"><i
                                            class="bx bx-show"></i></a>
                                    @can('kelola ujian')
                                        <a href="{{ route('guru.ujian.edit', $ujian->id) }}"
                                            class="btn btn-sm btn-icon btn-outline-warning" title="Edit"><i
                                                class="bx bx-edit"></i></a>
                                        <form action="{{ route('guru.ujian.destroy', $ujian->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus ujian ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger"
                                                title="Hapus"><i class="bx bx-trash"></i></button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Belum ada ujian yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $ujianList->links() }}
            </div>
        </div>
    </div>
@endsection
