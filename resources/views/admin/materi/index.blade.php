@extends('layouts.admin')

@section('title', 'Monitor Materi Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Materi Pembelajaran (Monitoring)</h5>
                    <form action="{{ route('admin.materi.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari materi..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
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
                                        <form action="{{ route('admin.materi.destroy', $materi->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus materi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger"><i
                                                    class="bx bx-trash"></i></button>
                                        </form>
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
                <div class="card-footer">
                    {{ $materis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
