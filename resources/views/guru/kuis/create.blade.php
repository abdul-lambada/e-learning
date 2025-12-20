@extends('layouts.guru')

@section('title', 'Buat Kuis Baru')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kuis /</span> Buat Kuis Baru</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Formulir Kuis</h5>
                    <div class="card-body">
                        <form action="{{ route('guru.kuis.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

                            <div class="mb-3">
                                <label for="judul_kuis" class="form-label">Judul Kuis</label>
                                <input type="text" class="form-control" id="judul_kuis" name="judul_kuis"
                                    placeholder="Contoh: Kuis Harian Matematika Bab 1" required>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Jelaskan tujuan kuis ini..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="instruksi" class="form-label">Instruksi Pengerjaan</label>
                                <textarea class="form-control" id="instruksi" name="instruksi" rows="4"
                                    placeholder="1. Berdoa sebelum mengerjakan&#10;2. Dikerjakan secara individu"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai Akses</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_mulai"
                                        name="tanggal_mulai" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai (Batas Akhir)</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_selesai"
                                        name="tanggal_selesai" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="durasi_menit" class="form-label">Durasi (Menit)</label>
                                    <input type="number" class="form-control" id="durasi_menit" name="durasi_menit"
                                        value="60" required min="1">
                                    <div class="form-text">Waktu pengerjaan setelah siswa mulai.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nilai_maksimal" class="form-label">Nilai Maksimal</label>
                                    <input type="number" class="form-control" id="nilai_maksimal" name="nilai_maksimal"
                                        value="100" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nilai_minimal_lulus" class="form-label">KKM / Passing Grade</label>
                                    <input type="number" class="form-control" id="nilai_minimal_lulus"
                                        name="nilai_minimal_lulus" value="75" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="max_percobaan" class="form-label">Maksimal Percobaan</label>
                                    <input type="number" class="form-control" id="max_percobaan" name="max_percobaan"
                                        value="1" required min="1">
                                    <div class="form-text">Berapa kali siswa boleh mencoba.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Pengaturan Tambahan</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="acak_soal" name="acak_soal"
                                        value="1">
                                    <label class="form-check-label" for="acak_soal">Acak Soal</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="acak_jawaban"
                                        name="acak_jawaban" value="1">
                                    <label class="form-check-label" for="acak_jawaban">Acak Jawaban (Pilihan
                                        Ganda)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="tampilkan_timer"
                                        name="tampilkan_timer" value="1" checked>
                                    <label class="form-check-label" for="tampilkan_timer">Tampilkan Timer</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="izinkan_kembali"
                                        name="izinkan_kembali" value="1">
                                    <label class="form-check-label" for="izinkan_kembali">Izinkan Kembali ke Soal
                                        Sebelumnya</label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Simpan & Lanjut ke Soal</button>
                                <a href="{{ route('guru.pertemuan.show', $pertemuan->id) }}"
                                    class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
