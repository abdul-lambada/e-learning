@extends('layouts.guru')
@section('title', 'Pendahuluan Mata Pelajaran')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pendahuluan /</span> Pilih Mata Pelajaran</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($mapelList as $mapel)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $mapel->nama_mapel }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($mapel->deskripsi, 100) }}</p>
                            <a href="{{ route('guru.pendahuluan.show', $mapel->id) }}"
                                class="btn btn-outline-primary w-100 mt-2">
                                <i class="bx bx-list-ul me-1"></i> Kelola Pendahuluan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">Data mata pelajaran tidak ditemukan.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
