@extends('layouts.guru')

@section('title', 'Manajemen Komponen Nilai')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-2">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Pengaturan /</span> Komponen Nilai</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKomponenModal">
                <i class="bx bx-plus me-1"></i> Atur Bobot Baru
            </button>
        </div>

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>KKM</th>
                            <th>Bobot (Tgs/Kuis/Ujian/Abs)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($komponens as $k)
                            <tr>
                                <td><strong>{{ $k->mataPelajaran->nama_mapel }}</strong></td>
                                <td>{{ $k->tahun_ajaran }}</td>
                                <td>{{ ucfirst($k->semester) }}</td>
                                <td><span class="badge bg-label-info">{{ $k->kkm }}</span></td>
                                <td>
                                    <small>
                                        T: {{ (int) $k->bobot_tugas }}% |
                                        K: {{ (int) $k->bobot_kuis }}% |
                                        U: {{ (int) $k->bobot_ujian }}% |
                                        A: {{ (int) $k->bobot_absensi }}%
                                    </small>
                                </td>
                                <td>
                                    @if ($k->aktif)
                                        <span class="badge bg-label-success">Aktif</span>
                                    @else
                                        <span class="badge bg-label-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('guru.komponen-nilai.edit', $k->id) }}"
                                            class="btn btn-sm btn-icon btn-outline-primary me-1">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('guru.komponen-nilai.destroy', $k->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus pengaturan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">Belum ada pengaturan bobot nilai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addKomponenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('guru.komponen-nilai.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Aturan Pembobotan Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="form-select" required>
                                @foreach ($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control"
                                value="{{ $activeAkademik->tahun_ajaran ?? '' }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="ganjil"
                                    {{ ($activeAkademik->semester ?? '') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="genap"
                                    {{ ($activeAkademik->semester ?? '') == 'genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <hr>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Bobot Tugas (%)</label>
                            <input type="number" name="bobot_tugas" class="form-control" value="20" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bobot Kuis (%)</label>
                            <input type="number" name="bobot_kuis" class="form-control" value="20" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bobot Ujian (%)</label>
                            <input type="number" name="bobot_ujian" class="form-control" value="40" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bobot Absensi (%)</label>
                            <input type="number" name="bobot_absensi" class="form-control" value="10" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bobot PM/Materi (%)</label>
                            <input type="number" name="bobot_pendahuluan" class="form-control" value="10" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">KKM</label>
                            <input type="number" name="kkm" class="form-control" value="75" required>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 py-2">
                        <small><i class="bx bx-info-circle me-1"></i> Total bobot harus berjumlah 100%.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
