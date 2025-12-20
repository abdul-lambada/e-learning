@extends('layouts.guru')

@section('title', 'Laporan Absensi Siswa')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Laporan /</span> Rekap Absensi</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('guru.laporan.absensi') }}" method="GET" class="row g-3">
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
                        <a href="{{ route('guru.laporan.absensi') }}" class="btn btn-secondary me-2">Reset</a>
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
                    <h5 class="mb-0">Laporan Absensi - {{ $selectedJadwal->kelas->nama_kelas }}</h5>
                    <small class="text-muted">{{ $selectedJadwal->mataPelajaran->nama_mapel }} | Guru:
                        {{ auth()->user()->nama_lengkap }}</small>
                    <div class="mt-1">
                        <small class="text-muted">Total Pertemuan:
                            {{ \App\Models\Pertemuan::where('guru_mengajar_id', $selectedJadwal->id)->count() }}</small>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center">Nama Siswa</th>
                                <th rowspan="2" class="align-middle text-center">NIS</th>
                                <th colspan="4" class="text-center">Detail Kehadiran</th>
                                <th rowspan="2" class="align-middle text-center bg-label-info">Persentase</th>
                            </tr>
                            <tr>
                                <th class="text-center text-success">Hadir</th>
                                <th class="text-center text-info">Izin</th>
                                <th class="text-center text-warning">Sakit</th>
                                <th class="text-center text-danger">Alpha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataAbsensi as $data)
                                @php
                                    $totalHadir = $data['hadir']; // + Izin/Sakit? Biasanya persentase kehadiran = (Hadir / Total) * 100
                                    // Atau (Hadir + Izin + Sakit) / Total?
                                    // Kita pakai Hadir murni saja sebagai default, atau Hadir Only.
                                    $persen =
                                        $data['total_pertemuan'] > 0
                                            ? ($totalHadir / $data['total_pertemuan']) * 100
                                            : 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $data['siswa']->nama_lengkap }}</td>
                                    <td class="text-center">{{ $data['siswa']->nis }}</td>
                                    <td class="text-center fw-bold">{{ $data['hadir'] }}</td>
                                    <td class="text-center">{{ $data['izin'] }}</td>
                                    <td class="text-center">{{ $data['sakit'] }}</td>
                                    <td class="text-center">{{ $data['alpha'] }}</td>
                                    <td
                                        class="text-center bg-lighter fw-bold {{ $persen < 75 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($persen, 0) }}%
                                    </td>
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
