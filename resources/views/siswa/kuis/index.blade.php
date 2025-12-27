@extends('layouts.siswa')

@section('title', 'Kuis Tersedia')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Siswa /</span> Daftar Kuis
            </h4>

            <div class="card">
                <h5 class="card-header">Kuis & Assessment</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Judul Kuis</th>
                                <th>Batas Waktu</th>
                                <th>Status</th>
                                <th>Skor Terakhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($kuisList as $k)
                                <tr>
                                    <td>{{ $k->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</td>
                                    <td><strong>{{ $k->nama_kuis }}</strong></td>
                                    <td>
                                        <div class="small">
                                            <i class="bx bx-calendar me-1"></i> {{ $k->tanggal_mulai->format('d/m/Y H:i') }}
                                            <br>
                                            <i class="bx bx-calendar-x me-1"></i>
                                            {{ $k->tanggal_selesai->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $now = now();
                                            $isStarted = $now->greaterThanOrEqualTo($k->tanggal_mulai);
                                            $isExpired = $now->greaterThan($k->tanggal_selesai);
                                        @endphp

                                        @if ($isExpired)
                                            <span class="badge bg-label-secondary">Sudah Berakhir</span>
                                        @elseif(!$isStarted)
                                            <span class="badge bg-label-info">Belum Dimulai</span>
                                        @else
                                            <span class="badge bg-label-success">Sedang Berlangsung</span>
                                        @endif

                                        @if ($k->pernah_mengerjakan)
                                            <span class="badge bg-label-primary ms-1">Pernah Mengerjakan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($k->skor_terakhir !== null)
                                            <span class="fw-bold">{{ number_format($k->skor_terakhir, 1) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('siswa.kuis.show', $k->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-right-arrow-alt me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted mb-0">Tidak ada kuis yang tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-3">
                    {{ $kuisList->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
