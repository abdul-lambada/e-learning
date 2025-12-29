@extends('layouts.guru')

@section('title', 'Verifikasi Absensi')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card title="Verifikasi Ketidakhadiran Siswa">
                <x-slot name="headerAction">
                    <span class="badge bg-label-primary fs-6">
                        <i class="bx bx-info-circle me-1"></i> {{ $absensi->total() }} Menunggu Verifikasi
                    </span>
                </x-slot>

                <div class="table-responsive text-nowrap mx-n4">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Mata Pelajaran</th>
                                <th>Pertemuan</th>
                                <th class="text-center">Status Inputan</th>
                                <th>Keterangan Siswa</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $a)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($a->siswa->nama_lengkap, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $a->siswa->nama_lengkap }}</span>
                                                <small class="text-muted">{{ $a->siswa->nis }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-primary">
                                            {{ $a->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}
                                        </div>
                                        <small
                                            class="text-muted">{{ $a->pertemuan->guruMengajar->kelas->nama_kelas }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">Pertemuan {{ $a->pertemuan->pertemuan_ke }}</div>
                                        <small class="text-muted">
                                            <i class="bx bx-calendar-alt me-1"></i>
                                            {{ $a->pertemuan->tanggal_pertemuan->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if ($a->status == 'izin')
                                            <span class="badge bg-label-info">
                                                <i class="bx bx-paper-plane me-1"></i> Izin
                                            </span>
                                        @elseif($a->status == 'sakit')
                                            <span class="badge bg-label-warning">
                                                <i class="bx bx-plus-medical me-1"></i> Sakit
                                            </span>
                                        @else
                                            <span class="badge bg-label-danger">
                                                <i class="bx bx-x-circle me-1"></i> Alpha
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 200px;">
                                            {{ $a->keterangan ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <x-button size="sm" color="label-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalVerify{{ $a->id }}" icon="bx-check-double">
                                            Verifikasi
                                        </x-button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalVerify{{ $a->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('guru.absensi.verifikasi.update', $a->id) }}"
                                                    method="POST" class="modal-content text-start">
                                                    @csrf
                                                    <div class="modal-header bg-label-primary py-3">
                                                        <h5 class="modal-title fw-bold">Verifikasi Absensi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex align-items-center mb-4 p-3 bg-lighter rounded">
                                                            <div class="avatar avatar-md me-3">
                                                                <span class="avatar-initial rounded bg-primary">
                                                                    {{ strtoupper(substr($a->siswa->nama_lengkap, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 fw-bold">{{ $a->siswa->nama_lengkap }}</h6>
                                                                <small class="text-muted">{{ $a->siswa->nis }} |
                                                                    {{ $a->pertemuan->guruMengajar->kelas->nama_kelas }}</small>
                                                            </div>
                                                        </div>

                                                        <x-select label="Ubah Status (Jika perlu)" name="status">
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
                                                                (Tanpa Keterangan)
                                                            </option>
                                                        </x-select>

                                                        <x-textarea label="Catatan Verifikasi" name="catatan" rows="3"
                                                            placeholder="Contoh: Bukti surat dokter valid"
                                                            value="{{ $a->keterangan }}" />
                                                    </div>
                                                    <div class="modal-footer border-top shadow-none">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <x-button type="submit" icon="bx-check">Simpan &
                                                            Verifikasi</x-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bx bx-check-shield fs-1 mb-2"></i><br>
                                            Semua ketidakhadiran telah diverifikasi.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $absensi->links() }}
                </div>
            </x-card>
        </div>
    </div>
@endsection
