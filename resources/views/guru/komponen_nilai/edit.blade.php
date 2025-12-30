@extends('layouts.app')

@section('title', 'Edit Komponen Nilai')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <x-card :title="'Form Edit Bobot Nilai: ' . $komponen_nilai->mataPelajaran->nama_mapel">
                <form action="{{ route('guru.komponen-nilai.update', $komponen_nilai->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row text-primary">
                        <div class="col-md-6">
                            <x-input label="Tahun Ajaran" name="tahun_ajaran" :value="$komponen_nilai->tahun_ajaran" readonly disabled />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Semester" name="semester" :value="ucfirst($komponen_nilai->semester)" readonly disabled />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Bobot Pendahuluan (%)" type="number" name="bobot_pendahuluan" :value="$komponen_nilai->bobot_pendahuluan"
                                required min="0" max="100" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Bobot Absensi (%)" type="number" name="bobot_absensi" :value="$komponen_nilai->bobot_absensi"
                                required min="0" max="100" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-input label="Bobot Tugas (%)" type="number" name="bobot_tugas" :value="$komponen_nilai->bobot_tugas" required
                                min="0" max="100" />
                        </div>
                        <div class="col-md-4">
                            <x-input label="Bobot Kuis (%)" type="number" name="bobot_kuis" :value="$komponen_nilai->bobot_kuis" required
                                min="0" max="100" />
                        </div>
                        <div class="col-md-4">
                            <x-input label="Bobot Ujian (%)" type="number" name="bobot_ujian" :value="$komponen_nilai->bobot_ujian" required
                                min="0" max="100" />
                        </div>
                    </div>

                    <div class="bg-label-primary p-3 rounded mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label class="form-label fw-bold mb-0">KKM (Kriteria Ketuntasan Minimal)</label>
                                <small class="d-block text-muted">Batas nilai minimum untuk dinyatakan lulus</small>
                            </div>
                            <div style="width: 100px;">
                                <input type="number" name="kkm" class="form-control form-control-lg text-center"
                                    value="{{ old('kkm', $komponen_nilai->kkm) }}" required min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan Perubahan</x-button>
                        <a href="{{ route('guru.komponen-nilai.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-md-5">
            <x-card title="Panduan Pembobotan" class="bg-label-info border-none shadow-none">
                <div class="d-flex mb-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-info"><i class="bx bx-info-circle"></i></span>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Harus 100%</h6>
                        <small class="text-muted">Total seluruh bobot (Pendahuluan + Absensi + Tugas + Kuis + Ujian)
                            wajib berjumlah 100% agar perhitungan nilai akurat.</small>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-info"><i class="bx bx-calculator"></i></span>
                    </div>
                    <div>
                        <h6 class="mb-1">Kalkulasi Otomatis</h6>
                        <small class="text-muted">Perubahan bobot akan langsung mempengaruhi kalkulasi <strong>Nilai
                                Akhir</strong> pada laporan perkembangan siswa secara real-time.</small>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-info"><i class="bx bx-target-lock"></i></span>
                    </div>
                    <div>
                        <h6 class="mb-1">Fungsi KKM</h6>
                        <small class="text-muted">Nilai KKM digunakan sebagai acuan untuk menentukan status kelulusan
                            siswa pada setiap materi dan ujian.</small>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
