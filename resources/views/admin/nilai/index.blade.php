@extends('layouts.admin')

@section('title', 'Monitor Nilai')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Nilai Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.nilai.index') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <select name="kelas_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Lihat Nilai</button>
                        </div>
                    </form>
                </div>
                @if (request('kelas_id'))
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>NIS</th>
                                    <th>Jlh Tugas</th>
                                    <th>Jlh Kuis</th>
                                    <th>Jlh Ujian</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($siswas as $s)
                                    <tr>
                                        <td><strong>{{ $s->nama_lengkap }}</strong></td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->pengumpulanTugas->count() }}</td>
                                        <td>{{ $s->jawabanKuis->count() }}</td>
                                        <td>{{ $s->jawabanUjian->count() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Siswa tidak ditemukan di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $siswas->links() }}
                    </div>
                @else
                    <div class="card-body">
                        <div class="alert alert-info">Pilih kelas terlebih dahulu untuk melihat ringkasan nilai siswa.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
