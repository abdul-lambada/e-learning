@extends('layouts.app')
@section('title', 'Kelola Pendahuluan - ' . $mataPelajaran->nama_mapel)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Pendahuluan /</span> {{ $mataPelajaran->nama_mapel }}
            </h4>
            <a href="{{ route('guru.pendahuluan.create', $mataPelajaran->id) }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Item
            </a>
        </div>

        <div class="row">
            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="col-12">
                <x-card title="Daftar Item Pendahuluan">
                    <x-slot name="headerAction">
                        <a href="{{ route('guru.pendahuluan.create', $mataPelajaran->id) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus me-1"></i> Tambah Item
                        </a>
                    </x-slot>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Judul</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendahuluanList as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $item->judul }}</strong></td>
                                        <td>
                                            @if ($item->wajib_diselesaikan)
                                                <span class="badge bg-label-warning">Wajib</span>
                                            @else
                                                <span class="badge bg-label-secondary">Opsional</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->aktif)
                                                <span class="badge bg-label-success">Aktif</span>
                                            @else
                                                <span class="badge bg-label-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-label-primary">{{ $item->urutan }}</span></td>
                                        <td>
                                            <a href="{{ route('guru.pendahuluan.edit', $item->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-warning">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                                data-url="{{ route('guru.pendahuluan.destroy', $item->id) }}"
                                                data-name="{{ $item->judul }}" data-title="Hapus Item Pendahuluan">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada item pendahuluan
                                            untuk mata
                                            pelajaran ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
