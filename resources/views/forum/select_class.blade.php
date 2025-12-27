@extends($layout)

@section('title', 'Forum Diskusi')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forum /</span> Pilih Kelas</h4>

        <div class="row">
            @forelse($daftarKelas as $jadwal)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-chat"></i></span>
                                </div>
                                <h5 class="card-title mb-0">{{ $jadwal->mataPelajaran->nama_mapel }}</h5>
                            </div>
                            <h6>Kelas: {{ $jadwal->kelas->nama_kelas }}</h6>
                            <p class="card-text text-muted">
                                <i class="bx bx-user me-1"></i> Guru:
                                {{ $jadwal->guru->nama_lengkap ?? 'Tidak ada guru' }}<br>
                                <i class="bx bx-calendar me-1"></i> {{ $jadwal->hari }},
                                {{ $jadwal->jam_mulai->format('H:i') }}
                            </p>
                            <a href="{{ route('forum.index', ['jadwal_id' => $jadwal->id]) }}"
                                class="btn btn-outline-primary w-100">
                                Buka Forum Diskusi
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Anda tidak terdaftar di kelas manapun.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
