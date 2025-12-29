@extends('layouts.guru')

@section('title', 'Daftar Kuis')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumbs -->
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [['name' => 'Dashboard', 'url' => route('guru.dashboard')], ['name' => 'Kuis']],
        ])

        <div class="row">
            <div class="col-12">
                <x-card title="Daftar Kuis & Ujian Saya">
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
                                                <span class="text-nowrap"><i class="bx bx-time me-1"></i>
                                                    {{ $k->durasi_menit }}
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
                                                    <a class="dropdown-item" href="{{ route('guru.kuis.hasil', $k->id) }}">
                                                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Hasil Siswa
                                                    </a>
                                                    @can('kelola kuis')
                                                        <a class="dropdown-item" href="{{ route('guru.kuis.show', $k->id) }}">
                                                            <i class="bx bx-cog me-1"></i> Kelola & Soal
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('guru.kuis.edit', $k->id) }}">
                                                            <i class="bx bx-edit-alt me-1"></i> Edit Info
                                                        </a>
                                                        <button type="button" class="dropdown-item btn-delete"
                                                            data-url="{{ route('guru.kuis.destroy', $k->id) }}"
                                                            data-name="{{ $k->judul_kuis }}" data-title="Hapus Kuis">
                                                            <i class="bx bx-trash me-1"></i> Hapus
                                                        </button>
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="bx bx-help-circle" style="font-size: 48px; color: #d1d5db;"></i>
                                                <p class="mb-0 mt-2 text-muted">Belum ada kuis yang dibuat.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <x-slot name="footer">
                        {{ $kuis->links() }}
                    </x-slot>
                </x-card>
            </div>
        </div>
    </div>
@endsection
