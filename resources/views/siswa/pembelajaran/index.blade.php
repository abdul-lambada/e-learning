@extends('layouts.app')

@section('title', 'Jadwal Pelajaran Saya')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="fw-bold py-3 mb-2">
                <span class="text-muted fw-light">Siswa /</span> Jadwal Pelajaran
            </h4>
            @if ($kelas)
                <p>Anda terdaftar di kelas <strong>{{ $kelas->nama_kelas }}</strong>. Berikut adalah mata pelajaran yang
                    tersedia.</p>
            @else
                <div class="alert alert-warning">
                    <i class="bx bx-error-circle me-1"></i> Anda belum terdaftar di kelas manapun. Hubungi Administrator.
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @forelse($jadwalPelajaran as $data)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $data->mataPelajaran->nama_mapel }}</h5>
                        <span class="badge bg-label-info">{{ $data->hari }}</span>
                    </div>

                    @if ($data->mataPelajaran->gambar_cover)
                        <img class="img-fluid" src="{{ Storage::url($data->mataPelajaran->gambar_cover) }}"
                            alt="Card image cap" style="height: 150px; object-fit: cover; width: 100%;">
                    @else
                        <div class="bg-label-primary d-flex justify-content-center align-items-center"
                            style="height: 150px;">
                            <i class="bx bx-book-open" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <p class="card-text text-muted mb-3">{{ Str::limit($data->mataPelajaran->deskripsi, 80) }}</p>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-xs me-2">
                                <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png" alt class="rounded-circle">
                            </div>
                            <small class="text-muted">Guru: {{ $data->guru->nama_lengkap }}</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small><i class="bx bx-time"></i> {{ \Carbon\Carbon::parse($data->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($data->jam_selesai)->format('H:i') }}</small>
                        </div>

                        <a href="{{ route('siswa.pembelajaran.show', $data->id) }}" class="btn btn-outline-primary w-100">
                            <i class="bx bx-door-open me-1"></i> Masuk Kelas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            @if ($kelas)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/girl-doing-yoga-light.png"
                                alt="No Data" width="200" class="mb-3">
                            <h4>Tidak ada jadwal pelajaran</h4>
                            <p class="text-muted">Belum ada mata pelajaran yang dijadwalkan untuk kelas Anda.</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforelse
    </div>
@endsection
