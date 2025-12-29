@extends('layouts.guru')
@section('title', 'Hasil Ujian')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card :title="'Daftar Peserta (' . $jadwal->tanggal_ujian->format('d M Y') . ')'">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.ujian.show', $jadwal->ujian_id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <div class="table-responsive text-nowrap mx-n4">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Waktu Mulai</th>
                                <th>Durasi</th>
                                <th>Nilai</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peserta as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $p->siswa->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $p->siswa->nis }}</small>
                                    </td>
                                    <td>{{ $p->waktu_mulai->format('H:i') }}</td>
                                    <td>{{ gmdate('H:i:s', $p->durasi_detik) }}</td>
                                    <td>
                                        <span class="fs-6 fw-bold {{ $p->lulus ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($p->nilai, 2) }}
                                        </span>
                                        @if (!$p->terverifikasi)
                                            <span class="badge badge-dot bg-warning ms-1"
                                                title="Perlu Koreksi/Verifikasi"></span>
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
                                    <td class="text-center">
                                        <a href="{{ route('guru.ujian.hasil.show', $p->id) }}"
                                            class="btn btn-sm btn-label-info">
                                            <i class="bx bx-check-square me-1"></i> Koreksi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Belum ada peserta yang mengerjakan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
@endsection
