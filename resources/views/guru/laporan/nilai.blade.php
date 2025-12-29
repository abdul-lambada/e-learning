@extends('layouts.guru')

@section('title', 'Laporan Nilai Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card title="Rekapitulasi Nilai Siswa">
                <x-slot name="headerAction">
                    <div class="d-print-none">
                        @if ($selectedJadwal)
                            <a href="{{ route('guru.laporan.nilai.cetak', $selectedJadwal->id) }}"
                                class="btn btn-label-danger">
                                <i class="bx bxs-file-pdf me-1"></i> Export PDF
                            </a>
                        @endif
                    </div>
                </x-slot>

                <form action="{{ route('guru.laporan.nilai') }}" method="GET" class="row g-3 mb-4 d-print-none">
                    <div class="col-md-9">
                        <x-select label="Pilih Jadwal Mengajar" name="jadwal_id" onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas & Mata Pelajaran --</option>
                            @foreach ($daftarKelas as $jadwal)
                                <option value="{{ $jadwal->id }}"
                                    {{ request('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->kelas->nama_kelas }} - {{ $jadwal->mataPelajaran->nama_mapel }}
                                    ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai->format('H:i') }})
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end mb-3">
                        <a href="{{ route('guru.laporan.nilai') }}" class="btn btn-label-secondary w-100">
                            <i class="bx bx-reset me-1"></i> Reset
                        </a>
                    </div>
                </form>

                @if ($selectedJadwal)
                    <div class="p-3 bg-label-primary rounded mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-1 fw-bold text-primary">{{ $selectedJadwal->kelas->nama_kelas }}</h5>
                                <p class="mb-0">{{ $selectedJadwal->mataPelajaran->nama_mapel }}</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                <small class="text-muted d-block">Guru Pengampu:</small>
                                <span class="fw-bold">{{ auth()->user()->nama_lengkap }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive text-nowrap mx-n4">
                        <table class="table table-hover table-bordered">
                            <thead>
                                @php $b = $dataNilai[0]['bobot'] ?? null; @endphp
                                <tr class="bg-light">
                                    <th rowspan="2" class="align-middle text-center" width="50">No</th>
                                    <th rowspan="2" class="align-middle">Nama Siswa</th>
                                    <th rowspan="2" class="align-middle text-center" width="100">NIS</th>
                                    <th colspan="4" class="text-center bg-label-secondary py-2">Komponen Nilai (%)</th>
                                    <th rowspan="2" class="align-middle text-center bg-primary text-white"
                                        width="120">Nilai Akhir</th>
                                </tr>
                                <tr class="bg-light">
                                    <th class="text-center small">Tugas ({{ ($b['tugas'] ?? 0) * 100 }}%)</th>
                                    <th class="text-center small">Kuis ({{ ($b['kuis'] ?? 0) * 100 }}%)</th>
                                    <th class="text-center small">Ujian ({{ ($b['ujian'] ?? 0) * 100 }}%)</th>
                                    <th class="text-center small">Absensi ({{ ($b['absensi'] ?? 0) * 100 }}%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataNilai as $data)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $data['siswa']->nama_lengkap }}</div>
                                        </td>
                                        <td class="text-center text-muted small">{{ $data['siswa']->nis }}</td>
                                        <td class="text-center">{{ number_format($data['avg_tugas'], 1) }}</td>
                                        <td class="text-center">{{ number_format($data['avg_kuis'], 1) }}</td>
                                        <td class="text-center">{{ number_format($data['avg_ujian'], 1) }}</td>
                                        <td class="text-center">{{ number_format($data['persen_hadir'], 1) }}%</td>
                                        <td class="text-center fw-bold fs-5 bg-lighter text-primary">
                                            {{ number_format($data['nilai_akhir'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bx bx-user-x fs-1 mb-2"></i><br>
                                                Data siswa tidak ditemukan di kelas ini.
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
                                <p class="mb-5">Mengetahui,<br>Guru Mata Pelajaran</p>
                                <h6 class="fw-bold mb-0 text-decoration-underline">{{ auth()->user()->nama_lengkap }}
                                </h6>
                                <p>NIP. {{ auth()->user()->nip ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 d-print-none">
                        <img src="{{ asset('assets/img/illustrations/sitting-girl-with-laptop-light.png') }}"
                            alt="select-class" width="200" class="mb-3">
                        <h5 class="text-muted">Silakan pilih jadwal mengajar untuk menampilkan laporan.</h5>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
