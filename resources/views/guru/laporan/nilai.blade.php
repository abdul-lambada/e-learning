@extends('layouts.guru')

@section('title', 'Laporan Nilai Siswa')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Laporan /</span> Rekap Nilai</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('guru.laporan.nilai') }}" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Pilih Kelas & Mata Pelajaran</label>
                        <select name="jadwal_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Pilih Jadwal Mengajar --</option>
                            @foreach ($daftarKelas as $jadwal)
                                <option value="{{ $jadwal->id }}"
                                    {{ request('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->kelas->nama_kelas }} - {{ $jadwal->mataPelajaran->nama_mapel }}
                                    ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <a href="{{ route('guru.laporan.nilai') }}" class="btn btn-secondary me-2">Reset</a>
                        @if ($selectedJadwal)
                            <button type="button" onclick="window.print()" class="btn btn-primary">
                                <i class="bx bx-printer me-1"></i> Cetak / PDF
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if ($selectedJadwal)
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Laporan Nilai - {{ $selectedJadwal->kelas->nama_kelas }}</h5>
                    <small class="text-muted">{{ $selectedJadwal->mataPelajaran->nama_mapel }} | Guru:
                        {{ auth()->user()->nama_lengkap }}</small>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center">Nama Siswa</th>
                                <th rowspan="2" class="align-middle text-center">NIS</th>
                                <th colspan="2" class="text-center">Rata-rata</th>
                                <th rowspan="2" class="align-middle text-center bg-label-primary">Nilai Akhir</th>
                            </tr>
                            <tr>
                                <th class="text-center">Tugas (60%)</th>
                                <th class="text-center">Kuis/Ujian (40%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataNilai as $data)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $data['siswa']->nama_lengkap }}</td>
                                    <td class="text-center">{{ $data['siswa']->nis }}</td>
                                    <td class="text-center">{{ number_format($data['avg_tugas'], 2) }}</td>
                                    <td class="text-center">{{ number_format($data['avg_kuis'], 2) }}</td>
                                    <td class="text-center fw-bold bg-lighter">{{ number_format($data['nilai_akhir'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Data siswa tidak ditemukan di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-none d-print-block">
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6 text-center">
                        <p>Mengetahui,</p>
                        <br><br><br>
                        <p class="fw-bold">{{ auth()->user()->nama_lengkap }}</p>
                        <p>NIP. {{ auth()->user()->nip }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
