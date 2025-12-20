@extends('layouts.guru')
@section('title', 'Detail Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Ujian /</span> Detail</h4>
            <a href="{{ route('guru.ujian.index') }}" class="btn btn-label-secondary">Kembali</a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">{{ $ujian->nama_ujian }}</h5>
                        <span class="badge bg-label-primary mb-3">{{ $ujian->kode_ujian }}</span>

                        <div class="mb-2"><strong>Mata Pelajaran:</strong><br> {{ $ujian->mataPelajaran->nama_mapel }}
                        </div>
                        <div class="mb-2"><strong>Kelas:</strong><br> {{ $ujian->kelas->nama_kelas }}</div>
                        <div class="mb-2"><strong>Jenis:</strong><br> {{ $ujian->jenis_ujian }}</div>
                        <div class="mb-2"><strong>Durasi:</strong><br> {{ $ujian->durasi_menit }} Menit</div>
                        <div class="mb-2"><strong>Soal:</strong><br> {{ $ujian->jumlah_soal }} Butir / Max Nilai
                            {{ $ujian->nilai_maksimal }}</div>
                        <div class="mb-3"><strong>Status:</strong><br>
                            @if ($ujian->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </div>

                        <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="btn btn-warning w-100">Edit
                            Konfigurasi</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#soal">Soal Ujian</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#jadwal">Jadwal Pelaksanaan</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="soal" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <h6 class="m-0">Daftar Soal ({{ $ujian->soalUjian->count() }} /
                                    {{ $ujian->jumlah_soal }})</h6>
                                <a href="{{ route('guru.ujian.soal.create', $ujian->id) }}"
                                    class="btn btn-primary btn-sm">+ Kelola Soal</a>
                            </div>

                            @if ($ujian->soalUjian->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach ($ujian->soalUjian as $soal)
                                        <div
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div class="w-75">
                                                <strong>No. {{ $soal->nomor_soal }}
                                                    ({{ ucfirst(str_replace('_', ' ', $soal->tipe_soal)) }})
                                                </strong><br>
                                                <span
                                                    class="text-muted small">{{ Str::limit(strip_tags($soal->pertanyaan), 150) }}</span>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('guru.ujian.soal.edit', [$ujian->id, $soal->id]) }}"
                                                    class="btn btn-sm btn-outline-secondary"><i class="bx bx-edit"></i></a>
                                                <form
                                                    action="{{ route('guru.ujian.soal.destroy', [$ujian->id, $soal->id]) }}"
                                                    method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                            class="bx bx-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info py-3 text-center">
                                    <i class='bx bx-info-circle mb-2' style="font-size: 2rem;"></i><br>
                                    Belum ada soal dibuat.<br>Simpan konfigurasi ujian terlebih dahulu.
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="jadwal" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <h6 class="m-0">Jadwal Pelaksanaan</h6>
                                <a href="{{ route('guru.ujian.jadwal.create', $ujian->id) }}"
                                    class="btn btn-primary btn-sm">+ Jadwalkan</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Ruang</th>
                                            <th>Pengawas</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ujian->jadwalUjian as $jadwal)
                                            <tr>
                                                <td>{{ $jadwal->tanggal_ujian->format('d/m/Y') }}</td>
                                                <td>{{ $jadwal->jam_mulai->format('H:i') }} -
                                                    {{ $jadwal->jam_selesai->format('H:i') }}</td>
                                                <td>{{ $jadwal->ruangan }}</td>
                                                <td>{{ $jadwal->pengawasUser->nama_lengkap ?? '-' }}</td>
                                                <td class="text-center">
                                                    <form
                                                        action="{{ route('guru.ujian.jadwal.destroy', [$ujian->id, $jadwal->id]) }}"
                                                        method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-xs btn-icon btn-outline-danger"><i
                                                                class="bx bx-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum dijadwalkan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
