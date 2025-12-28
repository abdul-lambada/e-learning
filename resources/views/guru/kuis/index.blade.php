@extends('layouts.guru')

@section('title', 'Daftar Kuis')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Manajemen Pembelajaran /</span> Daftar Kuis
        </h4>

        <div class="card">
            <h5 class="card-header">Daftar Kuis & Ujian Saya</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul Kuis</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jadwal & Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($kuis as $k)
                            <tr>
                                <td>
                                    <strong>{{ $k->judul_kuis }}</strong><br>
                                    <small class="text-muted">{{ $k->soalKuis->count() }} Soal</small>
                                </td>
                                <td>{{ $k->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</td>
                                <td><span
                                        class="badge bg-label-primary">{{ $k->pertemuan->guruMengajar->kelas->nama_kelas }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-nowrap"><i class="bx bx-calendar me-1"></i>
                                            {{ $k->tanggal_mulai->format('d M Y H:i') }}</span>
                                        <span class="text-nowrap"><i class="bx bx-time me-1"></i> {{ $k->durasi_menit }}
                                            Menit</span>
                                    </div>
                                </td>
                                <td>
                                    @if (!$k->aktif)
                                        <span class="badge bg-label-secondary">Non-Aktif</span>
                                    @elseif($k->tanggal_selesai->isPast())
                                        <span class="badge bg-label-danger">Selesai</span>
                                    @elseif($k->tanggal_mulai->isFuture())
                                        <span class="badge bg-label-warning">Belum Mulai</span>
                                    @else
                                        <span class="badge bg-label-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('guru.kuis.show', $k->id) }}"><i
                                                    class="bx bx-cog me-1"></i> Kelola & Soal</a>
                                            <a class="dropdown-item" href="{{ route('guru.kuis.hasil', $k->id) }}"><i
                                                    class="bx bx-bar-chart-alt-2 me-1"></i> Hasil Siswa</a>
                                            <a class="dropdown-item" href="{{ route('guru.kuis.edit', $k->id) }}"><i
                                                    class="bx bx-edit-alt me-1"></i> Edit Info</a>
                                            <form action="{{ route('guru.kuis.destroy', $k->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus kuis ini? Semua soal dan nilai siswa akan hilang.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i
                                                        class="bx bx-trash me-1"></i> Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada kuis yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-3">
                {{ $kuis->links() }}
            </div>
        </div>
    </div>
@endsection
