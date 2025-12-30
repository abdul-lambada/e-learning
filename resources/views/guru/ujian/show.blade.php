@extends('layouts.app')
@section('title', 'Detail Ujian')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <x-card :title="$ujian->nama_ujian">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.ujian.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <div class="mb-3">
                    <span class="badge bg-label-primary">{{ $ujian->kode_ujian }}</span>
                </div>

                <div class="mb-2"><strong>Mata Pelajaran:</strong><br> {{ $ujian->mataPelajaran->nama_mapel }}</div>
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

                @can('kelola ujian')
                    <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="btn btn-warning w-100 mt-2">
                        <i class="bx bx-edit me-1"></i> Edit Konfigurasi
                    </a>
                @endcan
            </x-card>
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
                <div class="tab-content border-top-0 p-0">
                    <div class="tab-pane fade show active" id="soal" role="tabpanel">
                        <x-card>
                            <x-slot name="title">
                                <h6 class="m-0">Daftar Soal ({{ $ujian->soalUjian->count() }} /
                                    {{ $ujian->jumlah_soal }})</h6>
                            </x-slot>
                            <x-slot name="headerAction">
                                @can('kelola ujian')
                                    <a href="{{ route('guru.ujian.soal.create', $ujian->id) }}"
                                        class="btn btn-primary btn-sm">+ Kelola Soal</a>
                                @endcan
                            </x-slot>

                            @if ($ujian->soalUjian->count() > 0)
                                <div class="list-group list-group-flush mx-n4">
                                    @foreach ($ujian->soalUjian as $soal)
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                            <div class="w-75">
                                                <div class="fw-bold">No. {{ $soal->nomor_soal }}
                                                    <span
                                                        class="badge bg-label-info ms-1">{{ ucfirst(str_replace('_', ' ', $soal->tipe_soal)) }}</span>
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    {{ Str::limit(strip_tags($soal->pertanyaan), 150) }}</div>
                                            </div>
                                            <div class="btn-group">
                                                @can('kelola ujian')
                                                    <a href="{{ route('guru.ujian.soal.edit', [$ujian->id, $soal->id]) }}"
                                                        class="btn btn-sm btn-icon btn-outline-secondary"><i
                                                            class="bx bx-edit"></i></a>
                                                    <form
                                                        action="{{ route('guru.ujian.soal.destroy', [$ujian->id, $soal->id]) }}"
                                                        method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger"><i
                                                                class="bx bx-trash"></i></button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class='bx bx-info-circle mb-2 text-muted' style="font-size: 3rem;"></i><br>
                                    <p class="text-muted">Belum ada soal dibuat.<br>Gunakan tombol Kelola Soal untuk
                                        menambahkan.</p>
                                </div>
                            @endif
                        </x-card>
                    </div>
                    <div class="tab-pane fade" id="jadwal" role="tabpanel">
                        <x-card title="Jadwal Pelaksanaan">
                            <x-slot name="headerAction">
                                @can('kelola ujian')
                                    <a href="{{ route('guru.ujian.jadwal.create', $ujian->id) }}"
                                        class="btn btn-primary btn-sm">+ Jadwalkan</a>
                                @endcan
                            </x-slot>

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
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('guru.ujian.hasil.index', $jadwal->id) }}"
                                                            class="btn btn-xs btn-icon btn-outline-info"
                                                            title="Lihat Hasil"><i class="bx bx-spreadsheet"></i></a>
                                                        @can('kelola ujian')
                                                            <form
                                                                action="{{ route('guru.ujian.jadwal.destroy', [$ujian->id, $jadwal->id]) }}"
                                                                method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-xs btn-icon btn-outline-danger"><i
                                                                        class="bx bx-trash"></i></button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Belum dijadwalkan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
