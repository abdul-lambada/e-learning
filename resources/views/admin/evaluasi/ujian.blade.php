@extends('layouts.app')

@section('title', 'Monitor Ujian')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ujian (Monitoring)</h5>
                    <form action="{{ route('admin.ujian.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari ujian..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ujian</th>
                                <th>Guru</th>
                                <th>Kelas / Mapel</th>
                                <th>Tipe</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($ujian as $u)
                                <tr>
                                    <td><strong>{{ $u->nama_ujian }}</strong></td>
                                    <td>{{ $u->importOleh->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-label-primary">{{ $u->kelas->nama_kelas ?? '-' }}</span><br>
                                        <small>{{ $u->mataPelajaran->nama_mapel ?? '-' }}</small>
                                    </td>
                                    <td>{{ strtoupper($u->tipe_ujian) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-{{ $u->aktif ? 'success' : 'secondary' }}">{{ $u->aktif ? 'Aktif' : 'Draft' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada ujian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $ujian->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
