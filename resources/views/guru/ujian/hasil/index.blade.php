@extends('layouts.guru')
@section('title', 'Hasil Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Ujian / {{ $jadwal->ujian->nama_ujian }} /</span> Hasil
        </h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Peserta ({{ $jadwal->tanggal_ujian->format('d M Y') }})</h5>
                <a href="{{ route('guru.ujian.show', $jadwal->ujian_id) }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Waktu Mulai</th>
                            <th>Durasi</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peserta as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->siswa->nama_lengkap }}</td>
                                <td>{{ $p->waktu_mulai->format('H:i') }}</td>
                                <td>{{ gmdate('H:i:s', $p->durasi_detik) }}</td>
                                <td>
                                    <strong
                                        class="{{ $p->lulus ? 'text-success' : 'text-danger' }}">{{ number_format($p->nilai, 2) }}</strong>
                                    @if (!$p->terverifikasi)
                                        <span class="badge bg-label-warning ms-1" title="Perlu Koreksi/Verifikasi">!</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->status == 'selesai')
                                        <span class="badge bg-label-success">Selesai</span>
                                    @elseif($p->status == 'sedang_dikerjakan')
                                        <span class="badge bg-label-primary">Mengerjakan</span>
                                    @else
                                        <span class="badge bg-label-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('guru.ujian.hasil.show', $p->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-check-square me-1"></i> Koreksi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada peserta yang mengerjakan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
