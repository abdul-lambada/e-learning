@extends('layouts.guru')

@section('title', 'Verifikasi Absensi')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Absensi /</span> Verifikasi Ketidakhadiran
            </h4>

            <div class="card">
                <h5 class="card-header">Daftar Izin, Sakit, & Alpa yang Belum Diverifikasi</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Mata Pelajaran</th>
                                <th>Pertemuan</th>
                                <th>Status Input</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($absensi as $a)
                                <tr>
                                    <td>
                                        <strong>{{ $a->siswa->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $a->siswa->username }}</small>
                                    </td>
                                    <td>{{ $a->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</td>
                                    <td>
                                        Pertemuan {{ $a->pertemuan->pertemuan_ke }} <br>
                                        <small>{{ $a->pertemuan->tanggal_pertemuan->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        @if ($a->status == 'izin')
                                            <span class="badge bg-label-info">Izin</span>
                                        @elseif($a->status == 'sakit')
                                            <span class="badge bg-label-warning">Sakit</span>
                                        @else
                                            <span class="badge bg-label-danger">Alpha</span>
                                        @endif
                                    </td>
                                    <td>{{ $a->keterangan ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalVerify{{ $a->id }}">
                                            Verifikasi
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalVerify{{ $a->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('guru.absensi.verifikasi.update', $a->id) }}"
                                                    method="POST" class="modal-content">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Absensi -
                                                            {{ $a->siswa->nama_lengkap }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Ubah Status (Jika perlu):</label>
                                                            <select name="status" class="form-select">
                                                                <option value="hadir"
                                                                    {{ $a->status == 'hadir' ? 'selected' : '' }}>Hadir
                                                                </option>
                                                                <option value="izin"
                                                                    {{ $a->status == 'izin' ? 'selected' : '' }}>Izin
                                                                </option>
                                                                <option value="sakit"
                                                                    {{ $a->status == 'sakit' ? 'selected' : '' }}>Sakit
                                                                </option>
                                                                <option value="alpha"
                                                                    {{ $a->status == 'alpha' ? 'selected' : '' }}>Alpha
                                                                    (Tanpa Keterangan)</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan Verifikasi:</label>
                                                            <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Bukti surat dokter valid">{{ $a->keterangan }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan &
                                                            Verifikasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted mb-0">Tidak ada absensi yang menunggu verifikasi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer px-3 py-2">
                    {{ $absensi->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
