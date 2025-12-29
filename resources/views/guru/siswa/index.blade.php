@extends('layouts.guru')

@section('title', 'Daftar Siswa Perwalian')

@section('content')
    <div class="row">
        @forelse($kelasWali as $kelas)
            <div class="col-12 mb-4">
                <x-card title="Kelas {{ $kelas->nama_kelas }} ({{ $kelas->tingkat }} {{ $kelas->jurusan }})">
                    <x-slot name="headerAction">
                        <span class="badge bg-label-primary fs-6">{{ $kelas->siswa->count() }} Siswa</span>
                    </x-slot>

                    <div class="table-responsive text-nowrap mx-n4">
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
                            <tbody>
                                @foreach ($kelas->siswa as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    @if ($siswa->foto_profil)
                                                        <img src="{{ Storage::url($siswa->foto_profil) }}" alt="avatar"
                                                            class="rounded-circle">
                                                    @else
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ $siswa->nama_lengkap }}</span>
                                                    <small class="text-muted">{{ $siswa->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($siswa->jenis_kelamin == 'L')
                                                <span class="badge bg-label-info">Laki-laki</span>
                                            @else
                                                <span class="badge bg-label-danger">Perempuan</span>
                                            @endif
                                        </td>
                                        <td>{{ $siswa->no_telepon ?? '-' }}</td>
                                        <td>
                                            @if ($siswa->aktif)
                                                <span class="badge bg-label-success">
                                                    <i class="bx bx-check-circle me-1"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-label-danger">
                                                    <i class="bx bx-x-circle me-1"></i> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        @empty
            <div class="col-12">
                <x-card class="text-center py-5">
                    <i class="bx bx-buildings mb-3" style="font-size: 4rem; color: #dce1e6;"></i>
                    <h5 class="fw-bold">Belum Ada Kelas Perwalian</h5>
                    <p class="text-muted mx-auto" style="max-width: 400px;">
                        Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun saat ini.
                        Silakan hubungi Administrator untuk informasi lebih lanjut.
                    </p>
                </x-card>
            </div>
        @endforelse
    </div>
@endsection
