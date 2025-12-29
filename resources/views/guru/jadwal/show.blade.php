@extends('layouts.guru')

@section('title', 'Detail Kelas')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <span class="text-muted fw-light">Jadwal /</span> {{ $jadwal->mataPelajaran->nama_mapel }} -
                    {{ $jadwal->kelas->nama_kelas }}
                </h4>
                <p class="mb-0 text-muted">
                    {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                </p>
            </div>
            <a href="{{ route('guru.jadwal.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Info -->
        <div class="col-md-3">
            <x-card>
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    @if ($jadwal->mataPelajaran->gambar_cover)
                        <img src="{{ Storage::url($jadwal->mataPelajaran->gambar_cover) }}" alt="cover"
                            class="d-block rounded" width="100" height="100" style="object-fit: cover">
                    @endif
                </div>
                <h5 class="mt-3 mb-1 fw-bold">{{ $jadwal->mataPelajaran->nama_mapel }}</h5>
                <p class="text-muted mb-4">{{ $jadwal->kelas->nama_kelas }}</p>

                <div class="d-grid gap-2">
                    <button class="btn btn-label-primary" type="button">
                        <i class="bx bx-user me-1"></i> Daftar Siswa
                    </button>
                    <button class="btn btn-label-success" type="button">
                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Rekap Nilai
                    </button>
                    <button class="btn btn-label-info" type="button">
                        <i class="bx bx-check-circle me-1"></i> Presensi
                    </button>
                </div>
            </x-card>
        </div>

        <!-- Main Content: Daftar Pertemuan -->
        <div class="col-md-9">
            <x-card title="Daftar Pertemuan">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pertemuan.create', ['jadwal_id' => $jadwal->id]) }}"
                        class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i> Buat Pertemuan Baru
                    </a>
                </x-slot>

                @if ($jadwal->pertemuan->count() > 0)
                    <div class="accordion" id="accordionPertemuan">
                        @foreach ($jadwal->pertemuan as $pertemuan)
                            <div class="accordion-item card mb-2 border shadow-none">
                                <h2 class="accordion-header" id="heading{{ $pertemuan->id }}">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $pertemuan->id }}" aria-expanded="false"
                                        aria-controls="collapse{{ $pertemuan->id }}">
                                        <div class="d-flex flex-column w-100">
                                            <div class="d-flex justify-content-between align-items-center me-3">
                                                <span class="fw-bold">Pertemuan {{ $pertemuan->pertemuan_ke }}:
                                                    {{ $pertemuan->judul_pertemuan }}</span>
                                                <div>
                                                    @if ($pertemuan->status == 'selesai')
                                                        <span class="badge bg-label-success me-2">Selesai</span>
                                                    @elseif($pertemuan->status == 'berlangsung')
                                                        <span class="badge bg-label-primary me-2">Berlangsung</span>
                                                    @else
                                                        <span class="badge bg-label-secondary me-2">Dijadwalkan</span>
                                                    @endif
                                                    <small
                                                        class="text-muted">{{ $pertemuan->tanggal_pertemuan->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $pertemuan->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $pertemuan->id }}" data-bs-parent="#accordionPertemuan">
                                    <div class="accordion-body">
                                        <p>{{ $pertemuan->deskripsi }}</p>

                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-show me-1"></i> Lihat Detail & Materi
                                            </a>
                                            <a href="{{ route('guru.pertemuan.edit', $pertemuan->id) }}"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="bx bx-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('guru.pertemuan.destroy', $pertemuan->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus pertemuan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-event" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="mt-3 text-muted">Belum ada pertemuan yang dibuat.</p>
                        <a href="{{ route('guru.pertemuan.create', ['jadwal_id' => $jadwal->id]) }}"
                            class="btn btn-outline-primary">
                            Buat Pertemuan Pertama
                        </a>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
