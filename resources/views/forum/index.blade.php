@extends($layout)

@section('title', 'Topik Diskusi - ' . $jadwal->mataPelajaran->nama_mapel)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0">
                <span class="text-muted fw-light">Forum /</span> {{ $jadwal->mataPelajaran->nama_mapel }}
                <small class="text-muted fs-6">({{ $jadwal->kelas->nama_kelas }})</small>
            </h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTopicModal">
                <i class="bx bx-plus me-1"></i> Buat Topik Baru
            </button>
        </div>

        <div class="card mt-4">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50%">Topik</th>
                            <th>Penulis</th>
                            <th>Balasan</th>
                            <th>Terakhir Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($topiks as $topik)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($topik->pinned)
                                            <i class="bx bxs-pin text-primary me-2" title="Pinned"></i>
                                        @endif
                                        <a href="{{ route('forum.show', $topik) }}"
                                            class="fw-bold text-body d-block text-truncate" style="max-width: 400px;">
                                            {{ $topik->judul }}
                                        </a>
                                    </div>
                                    @if ($topik->pertemuan)
                                        <small class="text-muted">Terkait: Pertemuan
                                            {{ $topik->pertemuan->pertemuan_ke }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $topik->user->foto_profil ? asset('storage/' . $topik->user->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                            alt="Avatar" class="rounded-circle me-2" width="25">
                                        <span>{{ $topik->user->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary">{{ $topik->balasan_count }} balasan</span>
                                </td>
                                <td>
                                    <small>{{ $topik->updated_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('forum.show', $topik) }}"
                                        class="btn btn-sm btn-icon btn-outline-primary">
                                        <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                    @if (Auth::id() == $topik->user_id || Auth::user()->peran == 'admin')
                                        <form action="{{ route('forum.destroy', $topik) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger"
                                                onclick="return confirm('Hapus topik ini?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bx bx-message-square-detail fs-1 text-muted"></i>
                                    </div>
                                    <h5>Belum ada diskusi</h5>
                                    <p class="text-muted">Mulai diskusi pertama di kelas ini sekarang!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $topiks->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Create Topic -->
    <div class="modal fade" id="createTopicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Topik Diskusi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('forum.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="guru_mengajar_id" value="{{ $jadwal->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Judul Topik</label>
                                <input type="text" name="judul" class="form-control"
                                    placeholder="Tuliskan judul diskusi..." required>
                            </div>
                        </div>
                        @if ($jadwal->pertemuan->count() > 0)
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Terkait Pertemuan (Opsional)</label>
                                    <select name="pertemuan_id" class="form-select">
                                        <option value="">-- Tidak Terkait Pertemuan Spesifik --</option>
                                        @foreach ($jadwal->pertemuan as $p)
                                            <option value="{{ $p->id }}">Pertemuan {{ $p->pertemuan_ke }} -
                                                {{ $p->judul }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Isi / Konten Diskusi</label>
                                <textarea name="konten" class="form-control" rows="5"
                                    placeholder="Tuliskan pertanyaan atau informasi yang ingin didiskusikan..." required></textarea>
                            </div>
                        </div>
                        @if (Auth::user()->peran == 'guru')
                            <div class="row">
                                <div class="col mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="pinned" id="pinnedCheck">
                                        <label class="form-check-label" for="pinnedCheck">
                                            Sematkan Topik (Pin to top)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Publish Topik</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
