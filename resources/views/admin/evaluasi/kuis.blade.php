@extends('layouts.app')

@section('title', 'Monitor Kuis')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kuis (Monitoring)</h5>
                    <form action="{{ route('admin.kuis.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari kuis..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kuis</th>
                                <th>Guru</th>
                                <th>Kelas / Mapel</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($kuis as $k)
                                <tr>
                                    <td><strong>{{ $k->judul }}</strong></td>
                                    <td>{{ $k->pertemuan->guruMengajar->guru->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-primary">{{ $k->pertemuan->guruMengajar->kelas->nama_kelas ?? '-' }}</span><br>
                                        <small>{{ $k->pertemuan->guruMengajar->mataPelajaran->nama_mapel ?? '-' }}</small>
                                    </td>
                                    <td>{{ $k->durasi }} menit</td>
                                    <td>
                                        <span
                                            class="badge bg-label-{{ $k->aktif ? 'success' : 'secondary' }}">{{ $k->aktif ? 'Aktif' : 'Draft' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada kuis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $kuis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
