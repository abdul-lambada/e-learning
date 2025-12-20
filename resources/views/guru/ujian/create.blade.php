@extends('layouts.guru')
@section('title', 'Buat Ujian Baru')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian /</span> Buat Baru</h4>

        <div class="card mb-4">
            <h5 class="card-header">Konfigurasi Ujian</h5>
            <div class="card-body">
                <form action="{{ route('guru.ujian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mata Pelajaran & Kelas</label>
                            <select name="option_id" class="form-select" required>
                                <option value="">-- Pilih Mapel & Kelas --</option>
                                @foreach ($formOptions as $key => $opt)
                                    <option value="{{ $key }}">{{ $opt['mapel']->nama_mapel }} -
                                        {{ $opt['kelas']->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Ujian</label>
                            <select name="jenis_ujian" class="form-select" required>
                                <option value="Harian">Ulangan Harian</option>
                                <option value="UTS">UTS (Tengah Semester)</option>
                                <option value="UAS">UAS (Akhir Semester)</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Ujian</label>
                        <input type="text" class="form-control" name="nama_ujian" required
                            placeholder="Contoh: Ujian Akhir Semester Ganjil 2024">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instruksi Pengerjaan</label>
                        <textarea class="form-control" name="instruksi" rows="3"
                            placeholder="Contoh: Kerjakan dengan jujur. Dilarang membuka buku."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Durasi (Menit)</label>
                            <input type="number" class="form-control" name="durasi_menit" value="90" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jumlah Soal</label>
                            <input type="number" class="form-control" name="jumlah_soal" value="50" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nilai Maksimal</label>
                            <input type="number" class="form-control" name="nilai_maksimal" value="100" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nilai Minimal Lulus (KKM)</label>
                            <input type="number" class="form-control" name="nilai_minimal_lulus" value="75" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="acak_soal" id="acak" checked>
                            <label class="form-check-label" for="acak">Acak Urutan Soal</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif" checked>
                            <label class="form-check-label" for="aktif">Aktifkan Sekarang</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan & Lanjut ke Soal</button>
                    <a href="{{ route('guru.ujian.index') }}" class="btn btn-label-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
