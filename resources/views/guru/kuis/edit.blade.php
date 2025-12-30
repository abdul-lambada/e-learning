@extends('layouts.app')

@section('title', 'Edit Kuis')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Pengaturan Kuis">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.kuis.show', $kuis->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('guru.kuis.update', $kuis->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <x-input label="Judul Kuis" name="judul_kuis" value="{{ $kuis->judul_kuis }}" required />

                    <x-textarea label="Deskripsi Singkat" name="deskripsi" rows="3" value="{{ $kuis->deskripsi }}" />

                    <x-textarea label="Instruksi Pengerjaan" name="instruksi" rows="4"
                        value="{{ $kuis->instruksi }}" />

                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Tanggal Mulai Akses" type="datetime-local" name="tanggal_mulai"
                                value="{{ \Carbon\Carbon::parse($kuis->tanggal_mulai)->format('Y-m-d\TH:i') }}" required />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Tanggal Selesai (Batas Akhir)" type="datetime-local" name="tanggal_selesai"
                                value="{{ \Carbon\Carbon::parse($kuis->tanggal_selesai)->format('Y-m-d\TH:i') }}"
                                required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-input label="Durasi (Menit)" type="number" name="durasi_menit"
                                value="{{ $kuis->durasi_menit }}" required min="1" />
                        </div>
                        <div class="col-md-4">
                            <x-input label="Nilai Maksimal" type="number" name="nilai_maksimal"
                                value="{{ $kuis->nilai_maksimal }}" required />
                        </div>
                        <div class="col-md-4">
                            <x-input label="KKM / Passing Grade" type="number" name="nilai_minimal_lulus"
                                value="{{ $kuis->nilai_minimal_lulus }}" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-input label="Maksimal Percobaan" type="number" name="max_percobaan"
                                value="{{ $kuis->max_percobaan }}" required min="1" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Pengaturan Tambahan</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1"
                                {{ $kuis->aktif ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">Kuis Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="acak_soal" name="acak_soal" value="1"
                                {{ $kuis->acak_soal ? 'checked' : '' }}>
                            <label class="form-check-label" for="acak_soal">Acak Soal</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="acak_jawaban" name="acak_jawaban"
                                value="1" {{ $kuis->acak_jawaban ? 'checked' : '' }}>
                            <label class="form-check-label" for="acak_jawaban">Acak Jawaban (Pilihan Ganda)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="tampilkan_timer" name="tampilkan_timer"
                                value="1" {{ $kuis->tampilkan_timer ? 'checked' : '' }}>
                            <label class="form-check-label" for="tampilkan_timer">Tampilkan Timer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="izinkan_kembali" name="izinkan_kembali"
                                value="1" {{ $kuis->izinkan_kembali ? 'checked' : '' }}>
                            <label class="form-check-label" for="izinkan_kembali">Izinkan Kembali ke Soal Sebelumnya</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan Perubahan</x-button>
                        <a href="{{ route('guru.kuis.show', $kuis->id) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
