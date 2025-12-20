@extends('layouts.guru')

@section('title', 'Daftar Siswa Perwalian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kelas Saya /</span> Daftar Siswa</h4>

        @forelse($kelasWali as $kelas)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelas {{ $kelas->nama_kelas }} ({{ $kelas->tingkat }} {{ $kelas->jurusan }})</h5>
                    <span class="badge bg-label-primary">{{ $kelas->siswa->count() }} Siswa</span>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>L/P</th>
                                <th>No. Telepon</th>
                                <th>Status Akun</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($kelas->siswa as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nis }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span
                                                    class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}</span>
                                            </div>
                                            <strong>{{ $siswa->nama_lengkap }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $siswa->no_telepon ?? '-' }}</td>
                                    <td>
                                        @if ($siswa->aktif)
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bx bx-buildings mb-3" style="font-size: 3rem; color: #dce1e6;"></i>
                    <h5>Belum Ada Kelas Perwalian</h5>
                    <p class="text-muted">Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
