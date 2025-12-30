@extends('layouts.app')

@section('title', 'Analitik Pembelajaran')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <span class="text-muted fw-light">Analitik /</span> {{ $jadwal->mataPelajaran->nama_mapel }}
                </h4>
                <p class="mb-0 text-muted">
                    Kelas: {{ $jadwal->kelas->nama_kelas }} | KKM: <span
                        class="badge bg-label-primary">{{ $kkm }}</span>
                </p>
            </div>
            <a href="{{ route('guru.jadwal.show', $jadwal->id) }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Jadwal
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Chart Progress -->
        <div class="col-md-8 mb-4">
            <x-card title="Tren Rata-rata Nilai Tugas">
                @if (count($rataRataTugas) > 0)
                    <canvas id="tugasChart" style="max-height: 400px;"></canvas>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-bar-chart-alt-2 text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 text-muted">Belum ada data nilai tugas yang cukup.</p>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Early Warning System -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2 text-danger"><i class="bx bx-error me-1"></i> Perlu Perhatian</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="performanceList" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="performanceList">
                            <a class="dropdown-item" href="javascript:void(0);">Hubungi Wali Murid</a>
                            <a class="dropdown-item" href="javascript:void(0);">Kirim Peringatan</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (count($siswaBerisiko) > 0)
                        <ul class="p-0 m-0">
                            @foreach ($siswaBerisiko as $siswa)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-danger"><i
                                                class="bx bx-user"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $siswa['nama'] }}</h6>
                                            <small class="text-muted">Rerata: {{ $siswa['rata_rata'] }} (Di bawah
                                                KKM)</small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-semibold text-danger">{{ $siswa['tugas_pending'] }} Tugas
                                                Belum</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center">
                            <i class="bx bx-check-shield text-success display-1 mb-3"></i>
                            <h5>Semua Aman!</h5>
                            <p class="text-muted">Tidak ada siswa dengan rata-rata di bawah KKM saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (count($rataRataTugas) > 0)
                const ctx = document.getElementById('tugasChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labelsTugas) !!},
                        datasets: [{
                            label: 'Rata-rata Nilai Kelas',
                            data: {!! json_encode($rataRataTugas) !!},
                            borderColor: '#696cff',
                            backgroundColor: 'rgba(105, 108, 255, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }, {
                            label: 'Batas KKM',
                            data: Array({{ count($rataRataTugas) }}).fill({{ $kkm }}),
                            borderColor: '#ff3e1d',
                            borderDash: [5, 5],
                            pointRadius: 0,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            @endif
        });
    </script>
@endpush
