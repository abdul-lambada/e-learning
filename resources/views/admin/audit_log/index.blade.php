@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Log Aktivitas Pengguna</h4>

        <div class="card">
            <h5 class="card-header">Riwayat Aktivitas Terbaru</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Peran</th>
                            <th>Aktivitas</th>
                            <th>Modul</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="avatar-wrapper me-2">
                                            <div class="avatar avatar-xs">
                                                <img src="{{ $log->user->foto_profil ? asset('storage/' . $log->user->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                                    alt="Avatar" class="rounded-circle">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $log->user->nama_lengkap ?? 'Unknown' }}</span>
                                            <small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span
                                        class="badge bg-label-{{ $log->user->peran == 'admin' ? 'danger' : ($log->user->peran == 'guru' ? 'primary' : 'warning') }}">{{ ucfirst($log->user->peran) }}</span>
                                </td>
                                <td>{{ $log->aktivitas }}</td>
                                <td>{{ $log->modul ?: '-' }}</td>
                                <td><small>{{ $log->ip_address }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">Belum ada riwayat aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
