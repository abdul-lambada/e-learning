@extends('layouts.siswa')

@section('title', 'Detail Pembelajaran')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <span class="text-muted fw-light">Mata Pelajaran /</span> {{ $jadwal->mataPelajaran->nama_mapel }}
                </h4>
                <p class="mb-0 text-muted">
                    Guru: {{ $jadwal->guru->nama_lengkap }} | {{ $jadwal->hari }},
                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                </p>
            </div>
            <a href="{{ route('siswa.pembelajaran.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- List Pertemuan -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Daftar Materi & Pertemuan</h5>
                </div>

                <div class="card-body pt-4">
                    @if ($jadwal->pertemuan->count() > 0)
                        <div class="timeline-basic mb-2">
                            <ul class="timeline mb-0">
                                @foreach ($jadwal->pertemuan as $pertemuan)
                                    <li class="timeline-item timeline-item-transparent text-primary">
                                        <span class="timeline-point timeline-point-primary"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0">Pertemuan {{ $pertemuan->pertemuan_ke }}:
                                                    {{ $pertemuan->judul_pertemuan }}</h6>
                                                <small
                                                    class="text-muted">{{ $pertemuan->tanggal_pertemuan->format('d M Y') }}</small>
                                            </div>
                                            <p class="mb-2 text-muted small">{{ Str::limit($pertemuan->deskripsi, 100) }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if ($pertemuan->status == 'berlangsung')
                                                        <span class="badge bg-label-success">Sedang Berlangsung</span>
                                                    @elseif($pertemuan->status == 'selesai')
                                                        <span class="badge bg-label-secondary">Selesai</span>
                                                    @else
                                                        <span class="badge bg-label-warning">Dijadwalkan</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('siswa.pembelajaran.pertemuan', $pertemuan->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bx bx-play-circle me-1"></i> Buka Materi
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-time-five" style="font-size: 3rem; color: #d1d5db;"></i>
                            <p class="mt-3 text-muted">Belum ada pertemuan yang aktif.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mx-auto mb-3">
                        @if ($jadwal->mataPelajaran->gambar_cover)
                            <img src="{{ Storage::url($jadwal->mataPelajaran->gambar_cover) }}" alt="Avatar Image"
                                class="rounded" width="100%" style="max-height: 200px; object-fit: cover">
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $jadwal->mataPelajaran->nama_mapel }}</h5>
                    <p class="text-muted">{{ $jadwal->mataPelajaran->kode_mapel }}</p>

                    <div class="row text-start mt-4">
                        <div class="col-12 mb-2">
                            <i class="bx bx-user me-2 text-primary"></i> Guru:
                            <strong>{{ $jadwal->guru->nama_lengkap }}</strong>
                        </div>
                        <div class="col-12 mb-2">
                            <i class="bx bx-time me-2 text-primary"></i> Durasi:
                            <strong>{{ $jadwal->mataPelajaran->durasi_menit }} Menit</strong>
                        </div>
                        <div class="col-12 mb-2">
                            <i class="bx bx-target-lock me-2 text-primary"></i> Target:
                            <strong>{{ $jadwal->mataPelajaran->jumlah_pertemuan }} Pertemuan</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
