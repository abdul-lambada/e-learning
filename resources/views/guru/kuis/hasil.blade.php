@extends('layouts.app')

@section('title', 'Hasil Evaluasi Kuis')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Kuis / {{ $kuis->judul_kuis }} /</span> Hasil Evaluasi
            </h4>
            <a href="{{ route('guru.kuis.show', $kuis->id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <x-card title="Daftar Pengerjaan Siswa">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Waktu Submit</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse ($hasil as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-primary">{{ substr($h->siswa->nama_lengkap, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="text-truncate fw-semibold">{{ $h->siswa->nama_lengkap }}</span>
                                                    <small class="text-truncate text-muted">{{ $h->siswa->nis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $h->waktu_selesai ? \Carbon\Carbon::parse($h->waktu_selesai)->format('d M H:i') : '-' }}
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold {{ $h->nilai >= $kuis->nilai_minimal_lulus ? 'text-success' : 'text-danger' }}">
                                                {{ $h->nilai ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($h->status == 'selesai')
                                                <span class="badge bg-label-success">Selesai</span>
                                            @elseif($h->status == 'sedang_dikerjakan')
                                                <span class="badge bg-label-warning">Sedang Mengerjakan</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ $h->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('guru.kuis.review', $h->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="bx bx-search-alt-2 me-1"></i> Review & Koreksi
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">Belum ada siswa yang mengerjakan kuis
                                            ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
