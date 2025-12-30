@extends('layouts.app')

@section('title', 'Manajemen Komponen Nilai')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card title="Daftar Pengaturan Komponen Nilai">
                <x-slot name="headerAction">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKomponenModal">
                        <i class="bx bx-plus me-1"></i> Atur Bobot Baru
                    </button>
                </x-slot>

                <div class="table-responsive text-nowrap mx-n4">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Tahun Ajaran</th>
                                <th>Semester</th>
                                <th class="text-center">KKM</th>
                                <th>Bobot (Tgs/Kuis/Ujian/Abs)</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($komponens as $k)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $k->mataPelajaran->nama_mapel }}</div>
                                        <small class="text-muted">{{ $k->mataPelajaran->kode_mapel }}</small>
                                    </td>
                                    <td>{{ $k->tahun_ajaran }}</td>
                                    <td>
                                        <span class="badge bg-label-info">{{ ucfirst($k->semester) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-primary">{{ $k->kkm }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1" style="font-size: 0.8rem;">
                                            <div class="d-flex justify-content-between">
                                                <span>Tugas:</span> <span
                                                    class="fw-bold">{{ (int) $k->bobot_tugas }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Kuis:</span> <span class="fw-bold">{{ (int) $k->bobot_kuis }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-primary">
                                                <span>Ujian:</span> <span
                                                    class="fw-bold">{{ (int) $k->bobot_ujian }}%</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-success">
                                                <span>Absensi:</span> <span
                                                    class="fw-bold">{{ (int) $k->bobot_absensi }}%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($k->aktif)
                                            <span class="badge bg-label-success">
                                                <i class="bx bx-check me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary">
                                                <i class="bx bx-x me-1"></i> Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('guru.komponen-nilai.edit', $k->id) }}"
                                                class="btn btn-sm btn-icon btn-label-primary">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-delete"
                                                data-url="{{ route('guru.komponen-nilai.destroy', $k->id) }}"
                                                data-name="{{ $k->mataPelajaran->nama_mapel }}"
                                                data-title="Hapus Pengaturan Komponen Nilai">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bx bx-info-circle fs-1 mb-2"></i><br>
                                            Belum ada pengaturan bobot nilai.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
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
