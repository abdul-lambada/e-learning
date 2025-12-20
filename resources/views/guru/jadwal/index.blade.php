@extends('layouts.guru')

@section('title', 'Jadwal Mengajar Saya')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="fw-bold py-3 mb-2">
                <span class="text-muted fw-light">Guru /</span> Jadwal Mengajar
            </h4>
            <p>Kelola kelas, pertemuan, dan materi pembelajaran Anda disini.</p>
        </div>
    </div>

    <div class="row">
        @forelse($jadwal as $data)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $data->mataPelajaran->nama_mapel }}</h5>
                        <span class="badge bg-label-primary">{{ $data->kelas->nama_kelas }}</span>
                    </div>

                    @if ($data->mataPelajaran->gambar_cover)
                        <img class="img-fluid" src="{{ Storage::url($data->mataPelajaran->gambar_cover) }}"
                            alt="Card image cap" style="height: 150px; object-fit: cover; width: 100%;">
                    @else
                        <div class="bg-label-secondary d-flex justify-content-center align-items-center"
                            style="height: 150px;">
                            <i class="bx bx-book-open" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <p class="card-text text-muted mb-3">{{ Str::limit($data->mataPelajaran->deskripsi, 80) }}</p>

                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <i class="bx bx-calendar me-2"></i> Hari
                                <span class="fw-bold">{{ $data->hari }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <i class="bx bx-time me-2"></i> Jam
                                <span>{{ \Carbon\Carbon::parse($data->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($data->jam_selesai)->format('H:i') }}</span>
                            </li>
                        </ul>

                        <a href="{{ route('guru.jadwal.show', $data->id) }}" class="btn btn-primary w-100">
                            <i class="bx bx-door-open me-1"></i> Masuk Kelas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/man-with-laptop-light.png"
                            alt="No Data" width="200" class="mb-3">
                        <h4>Belum ada jadwal mengajar</h4>
                        <p class="text-muted">Hubungi Administrator untuk mendapatkan jadwal mengajar Anda.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
