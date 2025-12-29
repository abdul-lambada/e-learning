@extends('layouts.guru')
@section('title', 'Laporan Pembelajaran (Jurnal)')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card title="Jurnal Mengajar Guru">
                <x-slot name="headerAction">
                    <x-button onclick="window.print()" color="label-primary" class="d-print-none" icon="bx-printer">
                        Cetak Jurnal
                    </x-button>
                </x-slot>

                <div class="mb-4 p-3 bg-lighter rounded border-start border-4 border-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-1 text-muted">Guru Pengampu</h6>
                            <h5 class="mb-0 fw-bold">{{ auth()->user()->nama_lengkap }}</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="mb-1 text-muted">NIP / ID Guru</h6>
                            <h5 class="mb-0 fw-bold">{{ auth()->user()->nip ?? '-' }}</h5>
                        </div>
                    </div>
                </div>

                <div class="table-responsive text-nowrap mx-n4">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th>Tanggal & Waktu</th>
                                <th>Kelas / Mapel</th>
                                <th>Materi / Topik Pembelajaran</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jurnal as $j)
                                <tr>
                                    <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $j->tanggal_pertemuan->format('d/m/Y') }}</div>
                                        <small class="text-muted">
                                            <i class="bx bx-time me-1"></i>
                                            {{ $j->jam_mulai ? \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '-' }}
                                            WIB
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $j->guruMengajar->kelas->nama_kelas }}</div>
                                        <small>{{ $j->guruMengajar->mataPelajaran->nama_mapel }}</small>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 300px;">
                                            {{ $j->judul_pertemuan }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-success fs-6">
                                            {{ $j->hadir_count }} Siswa
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($j->status == 'selesai')
                                            <span class="badge bg-success">
                                                <i class="bx bx-check-double me-1"></i> Selesai
                                            </span>
                                        @elseif($j->aktif)
                                            <span class="badge bg-primary">
                                                <i class="bx bx-play-circle me-1"></i> Berlangsung
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bx bx-calendar me-1"></i> Terjadwal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bx bx-calendar-x fs-1 mb-2"></i><br>
                                            Belum ada riwayat pertemuan/jurnal mengajar.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="col-12 mt-5 d-none d-print-block">
                    <div class="row">
                        <div class="col-7"></div>
                        <div class="col-5 text-center">
                            <p class="mb-5">Dicetak pada: {{ date('d/m/Y H:i') }}<br>Guru Pengampu,</p>
                            <h6 class="fw-bold mb-0 text-decoration-underline">{{ auth()->user()->nama_lengkap }}
                            </h6>
                            <p>NIP. {{ auth()->user()->nip ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
