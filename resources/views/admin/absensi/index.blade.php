@extends('layouts.admin')

@section('title', 'Monitor Absensi')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Absensi Siswa (Global)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.absensi.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="kelas_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Pertemuan</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($absensi as $a)
                                <tr>
                                    <td><strong>{{ $a->siswa->nama_lengkap ?? '-' }}</strong></td>
                                    <td>{{ $a->guruMengajar->kelas->nama_kelas ?? '-' }}</td>
                                    <td>{{ $a->guruMengajar->mataPelajaran->nama_mapel ?? '-' }}</td>
                                    <td>Pertemuan {{ $a->pertemuan->pertemuan_ke ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-label-{{ $a->status == 'hadir' ? 'success' : ($a->status == 'alpha' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Data absensi tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $absensi->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
