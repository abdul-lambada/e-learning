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
                            <a href="{{ route('guru.laporan.nilai.cetak', $selectedJadwal->id) }}" class="btn btn-danger">
                                <i class="bx bxs-file-pdf me-1"></i> Export PDF
                            </a>
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
                    <table class="table table-hover table-striped table-bordered text-center">
                        <thead class="table-light">
                            @php $b = $dataNilai[0]['bobot'] ?? null; @endphp
                            <tr>
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Nama Siswa</th>
                                <th rowspan="2" class="align-middle">NIS</th>
                                <th colspan="4" class="text-center">Komponen Nilai (%)</th>
                                <th rowspan="2" class="align-middle bg-label-primary">Nilai Akhir</th>
                            </tr>
                            <tr>
                                <th class="text-center">Tugas ({{ ($b['tugas'] ?? 0) * 100 }}%)</th>
                                <th class="text-center">Kuis ({{ ($b['kuis'] ?? 0) * 100 }}%)</th>
                                <th class="text-center">Ujian ({{ ($b['ujian'] ?? 0) * 100 }}%)</th>
                                <th class="text-center">Absensi ({{ ($b['absensi'] ?? 0) * 100 }}%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataNilai as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $data['siswa']->nama_lengkap }}</td>
                                    <td>{{ $data['siswa']->nis }}</td>
                                    <td>{{ number_format($data['avg_tugas'], 1) }}</td>
                                    <td>{{ number_format($data['avg_kuis'], 1) }}</td>
                                    <td>{{ number_format($data['avg_ujian'], 1) }}</td>
                                    <td>{{ number_format($data['persen_hadir'], 1) }}%</td>
                                    <td class="fw-bold bg-lighter">{{ number_format($data['nilai_akhir'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Data siswa tidak ditemukan di kelas ini.
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
