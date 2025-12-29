@extends('layouts.guru')
@section('title', 'Daftar Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumbs -->
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [['name' => 'Dashboard', 'url' => route('guru.dashboard')], ['name' => 'Ujian']],
        ])

        <div class="row">
            <div class="col-12">
                <x-card title="Daftar Ujian">
                    @can('kelola ujian')
                        <x-slot name="headerAction">
                            <a href="{{ route('guru.ujian.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i> Buat Ujian Baru
                            </a>
                        </x-slot>
                    @endcan

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ujian</th>
                                    <th>Kelas & Mapel</th>
                                    <th>Jenis</th>
                                    <th>Durasi</th>
                                    <th>Soal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ujianList as $ujian)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $ujian->nama_ujian }}</strong><br>
                                            <small class="text-muted">{{ $ujian->kode_ujian }}</small>
                                        </td>
                                        <td>
                                            {{ $ujian->kelas->nama_kelas }}<br>
                                            <small>{{ $ujian->mataPelajaran->nama_mapel }}</small>
                                        </td>
                                        <td><span class="badge bg-label-info">{{ $ujian->jenis_ujian }}</span></td>
                                        <td>{{ $ujian->durasi_menit }} Menit</td>
                                        <td>{{ $ujian->jumlah_soal }}</td>
                                        <td>
                                            @if ($ujian->aktif)
                                                <span class="badge bg-label-success">Aktif</span>
                                            @else
                                                <span class="badge bg-label-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('guru.ujian.show', $ujian->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary" title="Detail & Soal">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            @can('kelola ujian')
                                                <a href="{{ route('guru.ujian.edit', $ujian->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                                    data-url="{{ route('guru.ujian.destroy', $ujian->id) }}"
                                                    data-name="{{ $ujian->nama_ujian }}" data-title="Hapus Ujian"
                                                    title="Hapus">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="bx bx-file" style="font-size: 48px; color: #d1d5db;"></i>
                                                <p class="mb-0 mt-2 text-muted">Belum ada ujian yang dibuat.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <x-slot name="footer">
                        {{ $ujianList->links() }}
                    </x-slot>
                </x-card>
            </div>
        </div>
    </div>
@endsection
