@extends('layouts.app')

@section('title', 'Buat Kuis Baru')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Buat Kuis Baru">
                <x-slot name="headerAction">
                    <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Batal
                    </a>
                </x-slot>

                <form action="{{ route('guru.kuis.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

                    <x-input label="Judul Kuis" name="judul_kuis" placeholder="Contoh: Kuis Harian Matematika Bab 1"
                        required />

                    <x-textarea label="Deskripsi Singkat" name="deskripsi" rows="3"
                        placeholder="Jelaskan tujuan kuis ini..." />

                    <x-textarea label="Instruksi Pengerjaan" name="instruksi" rows="4"
                        placeholder="1. Berdoa sebelum mengerjakan&#10;2. Dikerjakan secara individu" />

                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Tanggal Mulai Akses" type="datetime-local" name="tanggal_mulai" required />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Tanggal Selesai (Batas Akhir)" type="datetime-local" name="tanggal_selesai"
                                required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-input label="Durasi (Menit)" type="number" name="durasi_menit" value="60" required
                                min="1" />
                            <div class="form-text mt-n2 mb-3">Waktu pengerjaan setelah siswa mulai.</div>
                        </div>
                        <div class="col-md-4">
                            <x-input label="Nilai Maksimal" type="number" name="nilai_maksimal" value="100" required />
                        </div>
                        <div class="col-md-4">
                            <x-input label="KKM / Passing Grade" type="number" name="nilai_minimal_lulus" value="75"
                                required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-input label="Maksimal Percobaan" type="number" name="max_percobaan" value="1" required
                                min="1" />
                            <div class="form-text mt-n2 mb-3">Berapa kali siswa boleh mencoba.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Pengaturan Tambahan</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="acak_soal" name="acak_soal" value="1">
                            <label class="form-check-label" for="acak_soal">Acak Soal</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="acak_jawaban" name="acak_jawaban"
                                value="1">
                            <label class="form-check-label" for="acak_jawaban">Acak Jawaban (Pilihan Ganda)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="tampilkan_timer" name="tampilkan_timer"
                                value="1" checked>
                            <label class="form-check-label" for="tampilkan_timer">Tampilkan Timer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="izinkan_kembali" name="izinkan_kembali"
                                value="1">
                            <label class="form-check-label" for="izinkan_kembali">Izinkan Kembali ke Soal Sebelumnya</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan & Lanjut ke Soal</x-button>
                        <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
