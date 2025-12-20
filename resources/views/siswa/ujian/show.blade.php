@extends('layouts.siswa')
@section('title', 'Informasi Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian /</span> Informasi</h4>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $jadwal->ujian->nama_ujian }}</h5>
                        <span class="badge bg-primary">{{ $jadwal->ujian->jenis_ujian }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Mata Pelajaran:</strong><br>
                                {{ $jadwal->ujian->mataPelajaran->nama_mapel }}
                            </div>
                            <div class="col-md-6">
                                <strong>Kelas:</strong><br>
                                {{ $jadwal->ujian->kelas->nama_kelas }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Waktu Pelaksanaan:</strong><br>
                                {{ $jadwal->tanggal_ujian->format('l, d F Y') }}<br>
                                {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Durasi Pengerjaan:</strong><br>
                                {{ $jadwal->ujian->durasi_menit }} Menit
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Jumlah Soal:</strong><br> {{ $jadwal->ujian->jumlah_soal }} Butir
                            </div>
                            <div class="col-md-6">
                                <strong>Pengawas:</strong><br> {{ $jadwal->pengawasUser->nama_lengkap ?? '-' }}
                            </div>
                        </div>

                        @if ($jadwal->ujian->deskripsi)
                            <div class="alert alert-secondary">
                                <strong>Deskripsi:</strong><br>
                                {{ $jadwal->ujian->deskripsi }}
                            </div>
                        @endif

                        @if ($jadwal->ujian->instruksi)
                            <div class="alert alert-warning">
                                <strong>Instruksi:</strong><br>
                                {{ $jadwal->ujian->instruksi }}
                            </div>
                        @endif

                        <hr>

                        @php
                            $now = \Carbon\Carbon::now();
                            $start = \Carbon\Carbon::parse(
                                $jadwal->tanggal_ujian->format('Y-m-d') . ' ' . $jadwal->jam_mulai->format('H:i'),
                            );
                            $isStarted = $now >= $start;
                        @endphp

                        @if ($sedangMengerjakan)
                            <div class="alert alert-info">
                                Anda sedang dalam sesi pengerjaan ujian.
                            </div>
                            <a href="{{ route('siswa.ujian.kerjakan', $sedangMengerjakan->id) }}"
                                class="btn btn-primary w-100">Lanjutkan Mengerjakan</a>
                        @else
                            @if ($riwayat->count() > 0 && ($jadwal->ujian->max_percobaan > 0 && $riwayat->count() >= $jadwal->ujian->max_percobaan))
                                <div class="alert alert-success">
                                    Anda sudah menyelesaikan ujian ini.<br>
                                    Nilai Terakhir: <strong>{{ number_format($riwayat->last()->nilai, 2) }}</strong>
                                </div>
                                <a href="{{ route('siswa.ujian.index') }}" class="btn btn-secondary w-100">Kembali</a>
                            @else
                                @if ($isStarted)
                                    <p class="text-center mb-2">Pastikan koneksi internet stabil sebelum memulai.</p>
                                    <form action="{{ route('siswa.ujian.start', $jadwal->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100"
                                            onclick="return confirm('Mulai ujian sekarang? Waktu akan berjalan.')">Mulai
                                            Kerjakan</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning text-center">Ujian belum dimulai.</div>
                                    <a href="{{ route('siswa.ujian.index') }}" class="btn btn-secondary w-100">Kembali</a>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
