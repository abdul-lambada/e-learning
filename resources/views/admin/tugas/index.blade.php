@extends('layouts.admin')

@section('title', 'Monitor Tugas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tugas (Monitoring)</h5>
                    <form action="{{ route('admin.tugas.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari tugas..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tugas</th>
                                <th>Guru</th>
                                <th>Kelas / Mapel</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($tugas as $t)
                                <tr>
                                    <td>
                                        <strong>{{ $t->judul }}</strong>
                                    </td>
                                    <td>{{ $t->pertemuan->guruMengajar->guru->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-primary">{{ $t->pertemuan->guruMengajar->kelas->nama_kelas ?? '-' }}</span><br>
                                        <small>{{ $t->pertemuan->guruMengajar->mataPelajaran->nama_mapel ?? '-' }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($t->tanggal_deadline)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($t->aktif)
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                            data-url="{{ route('admin.tugas.destroy', $t->id) }}"
                                            data-name="{{ $t->judul }}" data-title="Hapus Tugas">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada tugas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $tugas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
