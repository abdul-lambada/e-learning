@extends('layouts.app')

@section('title', 'Laporan Sistem')

@section('content')
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Siswa</h5>
                    <h2 class="text-white">{{ $stats['total_siswa'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Guru</h5>
                    <h2 class="text-white">{{ $stats['total_guru'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Kelas</h5>
                    <h2 class="text-white">{{ $stats['total_kelas'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Total Mapel</h5>
                    <h2 class="text-white">{{ $stats['total_mapel'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Laporan Aktivitas & Evaluasi</h5>
                </div>
                <div class="card-body">
                    <p>Fitur laporan grafik dan export Excel/PDF dalam tahap pengembangan.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.audit-log.index') }}" class="btn btn-outline-primary">Lihat Audit Log</a>
                        <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-success">Lihat Absensi
                            Global</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
