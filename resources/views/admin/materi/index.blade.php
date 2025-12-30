@extends('layouts.app')

@section('title', 'Monitor Materi Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card title="Materi Pembelajaran (Monitoring)">
                <x-slot name="headerAction">
                    <form action="{{ route('admin.materi.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari materi..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </x-slot>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Materi</th>
                                <th>Guru</th>
                                <th>Kelas / Mapel</th>
                                <th>Pertemuan</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($materis as $materi)
                                <tr>
                                    <td>
                                        <strong>{{ $materi->judul_materi }}</strong><br>
                                        <small class="text-muted">{{ $materi->tipe_materi }}</small>
                                    </td>
                                    <td>{{ $materi->pertemuan->guruMengajar->guru->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-primary">{{ $materi->pertemuan->guruMengajar->kelas->nama_kelas ?? '-' }}</span><br>
                                        <small>{{ $materi->pertemuan->guruMengajar->mataPelajaran->nama_mapel ?? '-' }}</small>
                                    </td>
                                    <td>Pertemuan {{ $materi->pertemuan->pertemuan_ke }}</td>
                                    <td>{{ $materi->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                            data-url="{{ route('admin.materi.destroy', $materi->id) }}"
                                            data-name="{{ $materi->judul_materi }}" data-title="Hapus Materi">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada materi pembelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $materis->links() }}
                </div>
            </x-card>
        </div>
    </div>
@endsection
