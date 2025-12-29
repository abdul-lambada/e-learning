@extends('layouts.guru')

@section('title', 'Wali Kelas')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Wali Kelas /</span> Overview Kelas
            </h4>

            @if (!$kelas)
                <div class="alert alert-warning">
                    <h6 class="alert-heading fw-bold mb-1">Perhatian!</h6>
                    <span>Anda belum ditugaskan sebagai wali kelas di kelas manapun. Silakan hubungi admin jika ini adalah
                        kesalahan.</span>
                </div>
            @else
                <!-- Kelas Selection if multiple -->
                @if ($kelasWali->count() > 1)
                    <x-card class="mb-4">
                        <label class="form-label">Pilih Kelas Binaan:</label>
                        <div class="d-flex gap-2">
                            @foreach ($kelasWali as $kw)
                                <a href="{{ route('guru.wali-kelas.show', $kw->id) }}"
                                    class="btn {{ $kelas->id == $kw->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ $kw->nama_kelas }}
                                </a>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                <div class="row">
                    <!-- Class Stats -->
                    <div class="col-md-4 mb-4">
                        <x-card class="text-center h-100">
                            <div class="avatar avatar-xl bg-label-primary mx-auto mb-3">
                                <i class="bx bx-buildings fs-2"></i>
                            </div>
                            <h5>Kelas {{ $kelas->nama_kelas }}</h5>
                            <span class="badge bg-label-secondary mb-3">{{ $kelas->tingkat }} -
                                {{ $kelas->jurusan }}</span>
                            <div class="d-flex justify-content-around mt-2">
                                <div>
                                    <h4 class="mb-0">{{ $kelas->siswa_count }}</h4>
                                    <small>Total Siswa</small>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $kelas->kapasitas }}</h4>
                                    <small>Kapasitas</small>
                                </div>
                            </div>
                        </x-card>
                    </div>

                    <!-- Student List -->
                    <div class="col-md-8 mb-4">
                        <x-card title="Daftar Siswa" class="h-100">
                            <x-slot name="headerAction">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-export me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                    class="bx bxs-file-pdf me-1"></i> PDF</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                    class="bx bxs-spreadsheet me-1"></i> Excel</a></li>
                                    </ul>
                                </div>
                            </x-slot>

                            <div class="table-responsive text-nowrap mx-n4">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>NIS</th>
                                            <th>Gender</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex justify-content-start align-items-center user-name">
                                                        <div class="avatar-wrapper">
                                                            <div class="avatar avatar-sm me-3">
                                                                @if ($student->foto_profil)
                                                                    <img src="{{ Storage::url($student->foto_profil) }}"
                                                                        alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <span
                                                                        class="avatar-initial rounded-circle bg-label-secondary">{{ substr($student->nama_lengkap, 0, 2) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-semibold">{{ $student->nama_lengkap }}</span>
                                                            <small class="text-muted">{{ $student->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->nis ?? '-' }}</td>
                                                <td>{{ $student->jenis_kelamin }}</td>
                                                <td>
                                                    <a href="{{ route('guru.wali-kelas.siswa.show', [$kelas->id, $student->id]) }}"
                                                        class="btn btn-sm btn-icon btn-outline-primary" title="Detail">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <x-slot name="footer">
                                {{ $students->links() }}
                            </x-slot>
                        </x-card>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
