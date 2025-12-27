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
                                    <td>{{ $t->guruMengajar->guru->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-primary">{{ $t->guruMengajar->kelas->nama_kelas ?? '-' }}</span><br>
                                        <small>{{ $t->guruMengajar->mapel->nama_mapel ?? '-' }}</small>
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
                                        <form action="{{ route('admin.tugas.destroy', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger"><i
                                                    class="bx bx-trash"></i></button>
                                        </form>
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
