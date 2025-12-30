@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Absensi /</span> Hari Ini
            </h4>

            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <i class="bx bx-calendar me-2 fs-4"></i>
                <div>
                    Hari ini: <strong>{{ $dayName }}, {{ \Carbon\Carbon::parse($today)->format('d F Y') }}</strong>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Daftar Pertemuan Aktif</h5>
                </div>
                <div class="card-body pt-4">
                    @if ($pertemuanHariIni->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/img/illustrations/girl-doing-yoga-light.png') }}"
                                alt="No Data" width="200" class="mb-3">
                            <h4>Tidak Ada Jadwal Hari Ini</h4>
                            <p class="text-muted">Belum ada pertemuan yang dijadwalkan atau dibuka oleh guru hari ini.</p>
                        </div>
                    @else
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        <th>Waktu</th>
                                        <th>Status Sesi</th>
                                        <th>Kehadiran Anda</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pertemuanHariIni as $pertemuan)
                                        <tr>
                                            <td><strong>{{ $pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</strong>
                                            </td>
                                            <td>{{ $pertemuan->guruMengajar->guru->nama_lengkap }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pertemuan->waktu_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($pertemuan->waktu_selesai)->format('H:i') }}</td>
                                            <td>
                                                @if ($pertemuan->status == 'mulai')
                                                    <span class="badge bg-success">Berlangsung</span>
                                                @elseif ($pertemuan->status == 'selesai')
                                                    <span class="badge bg-secondary">Selesai</span>
                                                @else
                                                    <span class="badge bg-warning">{{ ucfirst($pertemuan->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($pertemuan->absensi_saya)
                                                    @if ($pertemuan->absensi_saya->status == 'hadir')
                                                        <span class="badge bg-label-success">Hadir</span>
                                                    @elseif ($pertemuan->absensi_saya->status == 'izin')
                                                        <span class="badge bg-label-info">Izin</span>
                                                    @elseif ($pertemuan->absensi_saya->status == 'sakit')
                                                        <span class="badge bg-label-warning">Sakit</span>
                                                    @elseif ($pertemuan->absensi_saya->status == 'alpha')
                                                        <span class="badge bg-label-danger">Alpha</span>
                                                    @endif
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pertemuan->absensi_saya->waktu_absen)->format('H:i') }}</small>
                                                @else
                                                    <span class="badge bg-label-secondary">Belum Absen</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($pertemuan->absensi_saya)
                                                    <button class="btn btn-sm btn-secondary" disabled>Sudah Mengisi</button>
                                                @elseif ($pertemuan->status == 'mulai')
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalAbsen{{ $pertemuan->id }}">
                                                        Isi Absensi
                                                    </button>

                                                    <!-- Modal Absen -->
                                                    <div class="modal fade" id="modalAbsen{{ $pertemuan->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form action="{{ route('siswa.absensi.store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="pertemuan_id"
                                                                        value="{{ $pertemuan->id }}">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Konfirmasi Kehadiran</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Anda akan melakukan absensi untuk mata pelajaran
                                                                            <strong>{{ $pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</strong>.
                                                                        </p>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Status
                                                                                Kehadiran</label>
                                                                            <select name="status" class="form-select"
                                                                                required>
                                                                                <option value="hadir">Hadir</option>
                                                                                <option value="izin">Izin</option>
                                                                                <option value="sakit">Sakit</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">Keterangan
                                                                                (Opsional)</label>
                                                                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Tulis alasan jika izin/sakit..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Kirim
                                                                            Absensi</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>Ditutup</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
