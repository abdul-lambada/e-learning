@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pengguna: {{ $user->nama_lengkap }}</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Informasi Akun -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Informasi Akun</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="nama_lengkap">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="username">Username <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ old('username', $user->username) }}"
                                        required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="peran">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('peran') is-invalid @enderror" id="peran"
                                        name="peran" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin"
                                            {{ old('peran', $user->getRoleNames()->first()) == 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="guru"
                                            {{ old('peran', $user->getRoleNames()->first()) == 'guru' ? 'selected' : '' }}>
                                            Guru</option>
                                        <option value="siswa"
                                            {{ old('peran', $user->getRoleNames()->first()) == 'siswa' ? 'selected' : '' }}>
                                            Siswa</option>
                                    </select>
                                    @error('peran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="aktif">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('aktif') is-invalid @enderror" id="aktif"
                                        name="aktif" required>
                                        <option value="1" {{ old('aktif', $user->aktif) == 1 ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="0" {{ old('aktif', $user->aktif) == 0 ? 'selected' : '' }}>
                                            Non-Aktif</option>
                                    </select>
                                    @error('aktif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi Pribadi -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Informasi Pribadi</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L"
                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="P"
                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        id="tempat_lahir" name="tempat_lahir"
                                        value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="no_telepon">No. Telepon</label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                        id="no_telepon" name="no_telepon"
                                        value="{{ old('no_telepon', $user->no_telepon) }}">
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="nis_field" style="display: none;">
                                    <label class="form-label" for="nis">NIS (Nomor Induk Siswa)</label>
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        id="nis" name="nis" value="{{ old('nis', $user->nis) }}">
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="nip_field" style="display: none;">
                                    <label class="form-label" for="nip">NIP (Nomor Induk Pegawai)</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                        id="nip" name="nip" value="{{ old('nip', $user->nip) }}">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="alamat">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Show/hide NIS/NIP based on role
        document.getElementById('peran').addEventListener('change', function() {
            const role = this.value;
            const nisField = document.getElementById('nis_field');
            const nipField = document.getElementById('nip_field');

            nisField.style.display = 'none';
            nipField.style.display = 'none';

            if (role === 'siswa') {
                nisField.style.display = 'block';
            } else if (role === 'guru') {
                nipField.style.display = 'block';
            }
        });

        // Trigger on page load
        document.getElementById('peran').dispatchEvent(new Event('change'));
    </script>
@endpush
