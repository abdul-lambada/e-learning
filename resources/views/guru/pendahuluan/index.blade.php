@extends('layouts.app')
@section('title', 'Pendahuluan Mata Pelajaran')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($mapelList as $mapel)
                <div class="col">
                    <x-card :title="$mapel->nama_mapel" class="h-100">
                        <p class="text-muted small">{{ Str::limit($mapel->deskripsi, 100) }}</p>
                        <a href="{{ route('guru.pendahuluan.show', $mapel->id) }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="bx bx-list-ul me-1"></i> Kelola Pendahuluan
                        </a>
                    </x-card>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">Data mata pelajaran tidak ditemukan.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
