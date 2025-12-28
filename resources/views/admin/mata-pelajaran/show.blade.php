@extends('layouts.admin')

@section('title', 'Detail Mata Pelajaran')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manajemen Mapel /</span> Detail Mata Pelajaran
        </h4>

        <div class="row">
            <!-- Mapel Detail -->
            <div class="col-md-5 mb-4">
                <div class="card h-100">
                    @if ($mataPelajaran->gambar_cover)
                        <img class="card-img-top" src="{{ Storage::url($mataPelajaran->gambar_cover) }}" alt="Cover Image"
                            style="height: 200px; object-fit: cover;" />
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 200px;">
                            <i class="bx bx-book-open text-muted" style="font-size: 80px;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $mataPelajaran->nama_mapel }}</h5>
                        <p class="card-text text-muted mb-4">{{ $mataPelajaran->kode_mapel }}</p>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Status
                                <span
                                    class="badge bg-label-{{ $mataPelajaran->aktif ? 'success' : 'secondary' }}">{{ $mataPelajaran->aktif ? 'Aktif' : 'Non-Aktif' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Pertemuan
                                <span class="fw-bold">{{ $mataPelajaran->jumlah_pertemuan }} Sesi</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Durasi per Sesi
                                <span class="fw-bold">{{ $mataPelajaran->durasi_pertemuan }} Menit</span>
                            </li>
                        </ul>

                        <div class="d-grid gap-2">
                            @can('kelola mapel')
                                <a href="{{ route('admin.mata-pelajaran.edit', $mataPelajaran->id) }}"
                                    class="btn btn-primary">Edit Mapel</a>
                            @endcan
                            <a href="{{ route('admin.mata-pelajaran.index') }}"
                                class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guru Pengampu Info -->
            <div class="col-md-7 mb-4">
                <div class="card h-100">
                    <h5 class="card-header">Guru Pengampu</h5>
                    <div class="card-body">
                        <p class="mb-4">Daftar guru yang mengajar mata pelajaran ini di kelas aktif.</p>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Guru</th>
                                        <th>Kelas</th>
                                        <th>Jadwal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mataPelajaran->guruMengajar as $mengajar)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-primary">{{ substr($mengajar->guru->nama_lengkap ?? '?', 0, 1) }}</span>
                                                    </div>
                                                    {{ $mengajar->guru->nama_lengkap ?? 'Guru Dihapus' }}
                                                </div>
                                            </td>
                                            <td><span
                                                    class="badge bg-label-info">{{ $mengajar->kelas->nama_kelas ?? '-' }}</span>
                                            </td>
                                            <td>{{ $mengajar->hari }},
                                                {{ \Carbon\Carbon::parse($mengajar->jam_mulai)->format('H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Belum ada guru yang
                                                mengampu mapel ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
