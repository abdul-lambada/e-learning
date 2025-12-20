@extends('layouts.guru')
@section('title', 'Jadwalkan Ujian')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ujian / Jadwal /</span> Tambah</h4>

        <div class="card mb-4">
            <h5 class="card-header">Jadwalkan: {{ $ujian->nama_ujian }}</h5>
            <div class="card-body">
                <form action="{{ route('guru.ujian.jadwal.store', $ujian->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Ujian</label>
                            <input type="date" class="form-control" name="tanggal_ujian" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ruangan</label>
                            <input type="text" class="form-control" name="ruangan" placeholder="Contoh: Lab Komputer 1"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pengawas</label>
                            <select name="pengawas_id" class="form-select">
                                <option value="{{ auth()->id() }}">Diri Sendiri ({{ auth()->user()->nama_lengkap }})
                                </option>
                                @foreach ($gurus as $guru)
                                    @if ($guru->id !== auth()->id())
                                        <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="btn btn-label-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
