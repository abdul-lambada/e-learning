@extends('layouts.guru')

@section('title', 'Edit Pertemuan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Pertemuan: {{ $pertemuan->judul_pertemuan }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.jadwal.show', $pertemuan->guru_mengajar_id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.pertemuan.update', $pertemuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Pertemuan</h6>

                            <x-input label="Topik / Judul Pertemuan" name="judul_pertemuan"
                                value="{{ old('judul_pertemuan', $pertemuan->judul_pertemuan) }}" required />

                            <x-textarea label="Deskripsi / Tujuan Pembelajaran" name="deskripsi" rows="3"
                                value="{{ old('deskripsi', $pertemuan->deskripsi) }}" />

                            <x-select label="Status Pertemuan" name="aktif" required>
                                <option value="1" {{ old('aktif', $pertemuan->aktif) == 1 ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="0" {{ old('aktif', $pertemuan->aktif) == 0 ? 'selected' : '' }}>
                                    Non-Aktif (Sembunyikan)</option>
                            </x-select>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Waktu & Tanggal</h6>

                            <x-input label="Tanggal" type="date" name="tanggal_pertemuan"
                                value="{{ old('tanggal_pertemuan', $pertemuan->tanggal_pertemuan->format('Y-m-d')) }}"
                                required />

                            <div class="row">
                                <div class="col-md-6">
                                    <x-input label="Jam Mulai" type="time" name="jam_mulai"
                                        value="{{ old('jam_mulai', $pertemuan->jam_mulai->format('H:i')) }}" required />
                                </div>
                                <div class="col-md-6">
                                    <x-input label="Jam Selesai" type="time" name="jam_selesai"
                                        value="{{ old('jam_selesai', $pertemuan->jam_selesai->format('H:i')) }}" required />
                                </div>
                            </div>

                            <x-select label="Status Pelaksanaan" name="status" required>
                                <option value="dijadwalkan"
                                    {{ old('status', $pertemuan->status) == 'dijadwalkan' ? 'selected' : '' }}>
                                    Dijadwalkan</option>
                                <option value="berlangsung"
                                    {{ old('status', $pertemuan->status) == 'berlangsung' ? 'selected' : '' }}>
                                    Berlangsung</option>
                                <option value="selesai"
                                    {{ old('status', $pertemuan->status) == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </x-select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Update</x-button>
                        <a href="{{ route('guru.jadwal.show', $pertemuan->guru_mengajar_id) }}"
                            class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
