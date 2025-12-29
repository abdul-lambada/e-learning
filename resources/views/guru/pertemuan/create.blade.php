@extends('layouts.guru')

@section('title', 'Buat Pertemuan Baru')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Buat Pertemuan Baru: {{ $jadwal->mataPelajaran->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.jadwal.show', $jadwal->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.pertemuan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="guru_mengajar_id" value="{{ $jadwal->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Pertemuan</h6>

                            <x-input label="Pertemuan Ke" type="number" name="pertemuan_ke"
                                value="{{ old('pertemuan_ke', $pertemuanKe) }}" required />

                            <x-input label="Topik / Judul Pertemuan" name="judul_pertemuan"
                                value="{{ old('judul_pertemuan') }}" placeholder="Contoh: Pengenalan Aljabar" required />

                            <x-textarea label="Deskripsi / Tujuan Pembelajaran" name="deskripsi" rows="3"
                                value="{{ old('deskripsi') }}" />
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Waktu & Tanggal</h6>

                            <x-input label="Tanggal" type="date" name="tanggal_pertemuan"
                                value="{{ old('tanggal_pertemuan', date('Y-m-d')) }}" required />

                            <div class="row">
                                <div class="col-md-6">
                                    <x-input label="Jam Mulai" type="time" name="jam_mulai"
                                        value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-input label="Jam Selesai" type="time" name="jam_selesai"
                                        value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                                        required />
                                </div>
                            </div>

                            <x-select label="Status" name="status" required>
                                <option value="dijadwalkan" {{ old('status') == 'dijadwalkan' ? 'selected' : '' }}>
                                    Dijadwalkan</option>
                                <option value="berlangsung" {{ old('status') == 'berlangsung' ? 'selected' : '' }}>
                                    Berlangsung</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </x-select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan</x-button>
                        <a href="{{ route('guru.jadwal.show', $jadwal->id) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
