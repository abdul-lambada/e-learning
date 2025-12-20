@extends('layouts.guru')
@section('title', 'Laporan Pembelajaran (Jurnal)')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Laporan /</span> Jurnal Pembelajaran</h4>
            <button onclick="window.print()" class="btn btn-primary d-print-none"><i class="bx bx-printer me-1"></i>
                Cetak</button>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Jurnal Mengajar Guru</h5>
                <small class="text-muted">Nama: {{ auth()->user()->nama_lengkap }} | NIP: {{ auth()->user()->nip }}</small>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Topik / Materi</th>
                            <th class="text-center">Jml. Hadir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurnal as $j)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $j->tanggal_pertemuan->format('d/m/Y') }}</td>
                                <td>{{ $j->jam_mulai ? \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '-' }}</td>
                                <td>{{ $j->guruMengajar->kelas->nama_kelas }}</td>
                                <td>{{ $j->guruMengajar->mataPelajaran->nama_mapel }}</td>
                                <td class="text-wrap" style="max-width: 250px;">{{ $j->judul_pertemuan }}</td>
                                <td class="text-center">{{ $j->hadir_count }}</td>
                                <td>
                                    @if ($j->status == 'selesai')
                                        <span class="badge bg-label-success">Selesai</span>
                                    @elseif($j->aktif)
                                        <span class="badge bg-label-primary">Aktif</span>
                                    @else
                                        <span class="badge bg-label-secondary">Dijadwalkan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Belum ada riwayat pertemuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
