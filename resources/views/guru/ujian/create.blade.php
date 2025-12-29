@extends('layouts.guru')
@section('title', 'Buat Ujian Baru')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Konfigurasi Ujian">
                <form action="{{ route('guru.ujian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <x-select label="Mata Pelajaran & Kelas" name="option_id" required>
                                <option value="">-- Pilih Mapel & Kelas --</option>
                                @foreach ($formOptions as $key => $opt)
                                    <option value="{{ $key }}">{{ $opt['mapel']->nama_mapel }} -
                                        {{ $opt['kelas']->nama_kelas }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-md-6">
                            <x-select label="Jenis Ujian" name="jenis_ujian" required>
                                <option value="Harian">Ulangan Harian</option>
                                <option value="UTS">UTS (Tengah Semester)</option>
                                <option value="UAS">UAS (Akhir Semester)</option>
                                <option value="Lainnya">Lainnya</option>
                            </x-select>
                        </div>
                    </div>

                    <x-input label="Nama Ujian" name="nama_ujian" placeholder="Contoh: Ujian Akhir Semester Ganjil 2024"
                        required />

                    <x-textarea label="Deskripsi" name="deskripsi" rows="2" />

                    <x-textarea label="Instruksi Pengerjaan" name="instruksi" rows="2"
                        placeholder="Contoh: Kerjakan dengan jujur. Dilarang membuka buku." />

                    <div class="row">
                        <div class="col-md-3">
                            <x-input label="Durasi (Menit)" type="number" name="durasi_menit" value="90" required />
                        </div>
                        <div class="col-md-3">
                            <x-input label="Jumlah Soal" type="number" name="jumlah_soal" value="50" required />
                        </div>
                        <div class="col-md-3">
                            <x-input label="Nilai Maksimal" type="number" name="nilai_maksimal" value="100" required />
                        </div>
                        <div class="col-md-3">
                            <x-input label="Nilai Minimal Lulus (KKM)" type="number" name="nilai_minimal_lulus"
                                value="75" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="acak_soal" id="acak" checked
                                value="1">
                            <label class="form-check-label" for="acak">Acak Urutan Soal</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif" checked
                                value="1">
                            <label class="form-check-label" for="aktif">Aktifkan Sekarang</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan & Lanjut ke Soal</x-button>
                        <a href="{{ route('guru.ujian.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
