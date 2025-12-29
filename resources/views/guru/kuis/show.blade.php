@extends('layouts.guru')

@section('title', 'Detail Kuis & Soal')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Kuis /</span> {{ $kuis->judul_kuis }}</h4>
            <div>
                <a href="{{ route('guru.pertemuan.show', $kuis->pertemuan_id) }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Info Kuis -->
            <div class="col-md-4 order-md-2 mb-4">
                <x-card title="Informasi Kuis">
                    <x-slot name="headerAction">
                        @can('kelola kuis')
                            <a href="{{ route('guru.kuis.edit', $kuis->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        @endcan
                    </x-slot>

                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Status:</strong>
                            @if ($kuis->aktif)
                                <span class="badge bg-label-success">Aktif</span>
                            @else
                                <span class="badge bg-label-danger">Non-Aktif</span>
                            @endif
                        </li>
                        <li class="mb-2"><strong>Waktu Mulai:</strong>
                            {{ \Carbon\Carbon::parse($kuis->tanggal_mulai)->format('d M Y H:i') }}</li>
                        <li class="mb-2"><strong>Batas Akhir:</strong>
                            {{ \Carbon\Carbon::parse($kuis->tanggal_selesai)->format('d M Y H:i') }}</li>
                        <li class="mb-2"><strong>Durasi:</strong> {{ $kuis->durasi_menit }} Menit</li>
                        <li class="mb-2"><strong>Jumlah Soal:</strong> {{ $kuis->soalKuis->count() }}</li>
                        <li class="mb-2"><strong>Total Bobot:</strong> {{ $kuis->soalKuis->sum('bobot_nilai') }}</li>
                        <li class="mb-2"><strong>KKM:</strong> {{ $kuis->nilai_minimal_lulus }}</li>
                    </ul>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('guru.kuis.hasil', $kuis->id) }}" class="btn btn-outline-primary mb-2">
                            <i class="bx bx-bar-chart-alt-2 me-1"></i> Hasil & Evaluasi
                        </a>
                        @can('kelola kuis')
                            <a href="{{ route('guru.kuis.soal.create', $kuis->id) }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Tambah Soal
                            </a>
                        @endcan
                    </div>
                </x-card>

                <x-card>
                    <h6 class="fw-bold">Deskripsi</h6>
                    <p class="small text-muted">{{ $kuis->deskripsi ?? '-' }}</p>

                    <h6 class="fw-bold">Instruksi</h6>
                    <p class="small text-muted mb-0">{!! nl2br(e($kuis->instruksi ?? '-')) !!}</p>
                </x-card>
            </div>

            <!-- Daftar Soal -->
            <div class="col-md-8 order-md-1">
                <x-card title="Daftar Soal" class="mb-4">
                    <div class="table-responsive">
                        @forelse($kuis->soalKuis->sortBy('nomor_soal') as $soal)
                            <div class="p-3 border-bottom position-relative hover-action-container">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-label-primary mb-2">Soal #{{ $soal->nomor_soal }}</span>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('kelola kuis')
                                                <a class="dropdown-item"
                                                    href="{{ route('guru.soal.edit', $soal->id) }}">Edit</a>
                                                <form action="{{ route('guru.soal.destroy', $soal->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus soal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    {!! nl2br(e($soal->pertanyaan)) !!}
                                </div>

                                @if ($soal->gambar_soal)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($soal->gambar_soal) }}" alt="Gambar Soal"
                                            style="max-height: 200px;" class="rounded border">
                                    </div>
                                @endif

                                @if ($soal->tipe_soal == 'pilihan_ganda')
                                    <ul class="list-group list-group-flush">
                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                            @php
                                                $colName = 'pilihan_' . strtolower($opt);
                                                $val = $soal->$colName;
                                                $isKey = $soal->kunci_jawaban == $opt;
                                            @endphp
                                            @if ($val)
                                                <li
                                                    class="list-group-item px-0 py-1 border-0 d-flex {{ $isKey ? 'text-success fw-bold' : '' }}">
                                                    <span class="me-2">{{ $opt }}.</span>
                                                    <span>
                                                        {{ $val }}
                                                        @if ($isKey)
                                                            <i class='bx bx-check-double ms-2'></i>
                                                        @endif
                                                    </span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="alert alert-secondary py-2 mb-0">
                                        <strong>Jenis:</strong> Essay / Uraian
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bx bx-list-plus text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2">Belum ada soal. Silakan tambah soal baru.</p>
                                @can('kelola kuis')
                                    <a href="{{ route('guru.kuis.soal.create', $kuis->id) }}"
                                        class="btn btn-primary mt-2">Tambah Soal Pertama</a>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
