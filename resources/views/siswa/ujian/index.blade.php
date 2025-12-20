@extends('layouts.siswa')
@section('title', 'Jadwal Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Evaluasi /</span> Jadwal Ujian</h4>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            @forelse($jadwals as $jadwal)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-label-primary">{{ $jadwal->ujian->jenis_ujian }}</span>
                                <small class="text-muted">{{ $jadwal->tanggal_ujian->format('d M Y') }}</small>
                            </div>
                            <h5 class="card-title">{{ $jadwal->ujian->nama_ujian }}</h5>
                            <h6 class="card-subtitle text-muted mb-3">{{ $jadwal->ujian->mataPelajaran->nama_mapel }}</h6>

                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="bx bx-time me-2"></i> {{ $jadwal->jam_mulai->format('H:i') }} -
                                    {{ $jadwal->jam_selesai->format('H:i') }}</li>
                                <li class="mb-2"><i class="bx bx-timer me-2"></i> Durasi:
                                    {{ $jadwal->ujian->durasi_menit }} Menit</li>
                                <li><i class="bx bx-map me-2"></i> Ruang: {{ $jadwal->ruangan }}</li>
                            </ul>

                            @php
                                $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse(
                                    $jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_mulai->format('H:i'),
                                );
                                $end = \Carbon\Carbon::parse(
                                    $jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_selesai->format('H:i'),
                                );
                                $isOpen = $now >= $start && $now <= $end;
                                $isFinished = $now > $end;
                            @endphp

                            @if ($isOpen)
                                <a href="{{ route('siswa.ujian.show', $jadwal->id) }}" class="btn btn-primary w-100">Masuk
                                    Ujian</a>
                            @elseif($isFinished)
                                <button class="btn btn-secondary w-100" disabled>Selesai</button>
                            @else
                                <button class="btn btn-outline-secondary w-100" disabled>Belum Mulai</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Tidak ada jadwal ujian yang aktif atau dijadwalkan dalam waktu dekat.
                    </div>
                </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center">
            {{ $jadwals->links() }}
        </div>
    </div>
@endsection
