@extends($layout)

@section('title', $topik->judul)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('forum.index', ['jadwal_id' => $topik->guru_mengajar_id]) }}">Forum</a></li>
                <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($topik->judul, 30) }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <!-- Original Topic Card -->
                <div class="card mb-4 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $topik->user->foto_profil ? asset('storage/' . $topik->user->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                    alt="Avatar" class="rounded-circle me-3" width="45">
                                <div>
                                    <h6 class="mb-0">{{ $topik->user->nama_lengkap }}</h6>
                                    <small class="text-muted">{{ ucfirst($topik->user->peran) }} •
                                        {{ $topik->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @if ($topik->pinned)
                                <span class="badge bg-label-primary"><i class="bx bxs-pin me-1"></i> Tersemat</span>
                            @endif
                        </div>
                        <h4 class="card-title">{{ $topik->judul }}</h4>
                        <p class="card-text" style="white-space: pre-line;">{!! nl2br(e($topik->konten)) !!}</p>

                        @if ($topik->pertemuan)
                            <div class="alert alert-secondary py-2 px-3 mb-0">
                                <small><i class="bx bx-info-circle me-1"></i> Topik ini terkait dengan <strong>Pertemuan
                                        {{ $topik->pertemuan->pertemuan_ke }}:
                                        {{ $topik->pertemuan->judul }}</strong></small>
                            </div>
                        @endif
                    </div>
                </div>

                <h5 class="mb-3">{{ $topik->balasan->count() }} Balasan</h5>

                <!-- Replies -->
                @foreach ($topik->balasan as $balasan)
                    <div class="card mb-3 shadow-none border">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $balasan->user->foto_profil ? asset('storage/' . $balasan->user->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                        alt="Avatar" class="rounded-circle me-2" width="35">
                                    <div>
                                        <small class="fw-bold d-block">{{ $balasan->user->nama_lengkap }}</small>
                                        <small class="text-muted"
                                            style="font-size: 0.75rem;">{{ $balasan->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if ($balasan->user->peran == 'guru')
                                    <span class="badge bg-label-success h-50">Guru</span>
                                @endif
                            </div>
                            <p class="card-text mb-0" style="white-space: pre-line;">{!! nl2br(e($balasan->konten)) !!}</p>
                        </div>
                    </div>
                @endforeach

                <!-- Reply Form -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Tulis Balasan</h6>
                        <form action="{{ route('forum.reply', $topik) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="konten" class="form-control" rows="4" placeholder="Tuliskan balasan Anda di sini..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
