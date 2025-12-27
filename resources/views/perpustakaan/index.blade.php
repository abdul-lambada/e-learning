@extends($layout)

@section('title', 'Perpustakaan Digital')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Sumber Belajar /</span> Perpustakaan</h4>
            @if (auth()->user()->peran != 'siswa')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMateriModal">
                    <i class="bx bx-plus me-1"></i> Tambah Materi
                </button>
            @endif
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('perpustakaan.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari judul materi..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="PDF" {{ request('kategori') == 'PDF' ? 'selected' : '' }}>PDF / Document
                            </option>
                            <option value="Video" {{ request('kategori') == 'Video' ? 'selected' : '' }}>Video Tutorial
                            </option>
                            <option value="E-Book" {{ request('kategori') == 'E-Book' ? 'selected' : '' }}>E-Book</option>
                            <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Materials Grid -->
        <div class="row">
            @forelse($materis as $materi)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span
                                        class="avatar-initial rounded bg-label-{{ $materi->kategori == 'Video' ? 'danger' : ($materi->kategori == 'PDF' ? 'info' : 'success') }}">
                                        <i
                                            class="bx {{ $materi->kategori == 'Video' ? 'bx-video' : ($materi->kategori == 'PDF' ? 'bx-file' : 'bx-book') }}"></i>
                                    </span>
                                </div>
                                <h5 class="card-title mb-0 text-truncate" title="{{ $materi->judul }}">{{ $materi->judul }}
                                </h5>
                            </div>
                            <p class="card-text text-muted small mb-3" style="height: 40px; overflow: hidden;">
                                {{ $materi->deskripsi ?: 'Tidak ada deskripsi.' }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <small class="text-muted">Oleh: {{ $materi->user->nama_lengkap }}</small>
                                <span class="badge bg-label-secondary">{{ $materi->kategori }}</span>
                            </div>
                        </div>
                        <div class="card-footer border-top py-2 d-flex justify-content-between">
                            <div>
                                @if ($materi->file_path)
                                    <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank"
                                        class="btn btn-sm btn-label-primary">
                                        <i class="bx bx-download me-1"></i> Unduh
                                    </a>
                                @elseif($materi->url_external)
                                    <a href="{{ $materi->url_external }}" target="_blank"
                                        class="btn btn-sm btn-label-secondary">
                                        <i class="bx bx-link-external me-1"></i> Buka Link
                                    </a>
                                @endif
                            </div>
                            @if (auth()->id() == $materi->user_id || auth()->user()->peran == 'admin')
                                <form action="{{ route('perpustakaan.destroy', $materi) }}" method="POST"
                                    onsubmit="return confirm('Hapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bx bx-folder-open fs-1 text-muted"></i>
                        <h5 class="mt-3">Belum ada materi di perpustakaan.</h5>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $materis->links() }}
        </div>
    </div>

    <!-- Modal Add Materi -->
    @if (auth()->user()->peran != 'siswa')
        <div class="modal fade" id="addMateriModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('perpustakaan.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Materi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Judul Materi</label>
                                <input type="text" name="judul" class="form-control"
                                    placeholder="Contoh: Modul Biologi Sel" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="PDF">PDF / Document</option>
                                    <option value="Video">Video Tutorial</option>
                                    <option value="E-Book">E-Book</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File (Opsional)</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">URL External (Opsional)</label>
                                <input type="url" name="url_external" class="form-control"
                                    placeholder="https://youtube.com/...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi Singkat</label>
                                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Materi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
