@extends('layouts.siswa')

@section('title', 'Tugas Saya')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Siswa /</span> Daftar Tugas
            </h4>

            <x-card title="Semua Tugas">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Judul Tugas</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($tugasList as $t)
                                <tr>
                                    <td>{{ $t->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</td>
                                    <td><strong>{{ $t->judul }}</strong></td>
                                    <td>
                                        <span
                                            class="{{ now()->greaterThan($t->tanggal_deadline) ? 'text-danger fw-bold' : '' }}">
                                            {{ $t->tanggal_deadline->format('d M Y, H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($t->pengumpulan)
                                            @if ($t->pengumpulan->status == 'dinilai')
                                                <span class="badge bg-label-success">Selesai & Dinilai</span>
                                            @else
                                                <span class="badge bg-label-primary">Sudah Dikumpulkan</span>
                                            @endif
                                        @else
                                            @if (now()->greaterThan($t->tanggal_deadline))
                                                <span class="badge bg-label-danger">Belum Mengumpulkan (Missed)</span>
                                            @else
                                                <span class="badge bg-label-warning">Belum Mengumpulkan</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($t->pengumpulan && $t->pengumpulan->nilai !== null)
                                            <span class="fw-bold fs-5">{{ number_format($t->pengumpulan->nilai, 0) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('siswa.tugas.show', $t->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bx {{ $t->pengumpulan ? 'bx-show' : 'bx-upload' }} me-1"></i>
                                            {{ $t->pengumpulan ? 'Lihat/Edit' : 'Kerjakan' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted mb-0">Tidak ada tugas yang tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $tugasList->links() }}
                </div>
            </x-card>
        </div>
    </div>
@endsection
