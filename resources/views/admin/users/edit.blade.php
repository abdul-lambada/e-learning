@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Edit Pengguna: {{ $user->nama_lengkap }}">
                <x-slot name="headerAction">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Informasi Akun -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Akun</h6>

                            <x-input label="Nama Lengkap" name="nama_lengkap"
                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required />

                            <x-input label="Email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                required />

                            <x-input label="Username" name="username" value="{{ old('username', $user->username) }}"
                                required />

                            <x-input label="Password" type="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password" />

                            <x-select label="Role" name="peran" required id="peran">
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
                            </x-select>

                            <x-select label="Status" name="aktif" required>
                                <option value="1" {{ old('aktif', $user->aktif) == 1 ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="0" {{ old('aktif', $user->aktif) == 0 ? 'selected' : '' }}>
                                    Non-Aktif</option>
                            </x-select>
                        </div>

                        <!-- Informasi Pribadi -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Pribadi</h6>

                            <x-select label="Jenis Kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="P"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </x-select>

                            <x-input label="Tempat Lahir" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}" />

                            <x-input label="Tanggal Lahir" type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" />

                            <x-input label="No. Telepon" name="no_telepon"
                                value="{{ old('no_telepon', $user->no_telepon) }}" />

                            <div id="nis_field" style="display: none;">
                                <x-input label="NIS (Nomor Induk Siswa)" name="nis"
                                    value="{{ old('nis', $user->nis) }}" />
                            </div>

                            <div id="nip_field" style="display: none;">
                                <x-input label="NIP (Nomor Induk Pegawai)" name="nip"
                                    value="{{ old('nip', $user->nip) }}" />
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
                        <x-button type="submit" icon="bx-save">Update</x-button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
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
