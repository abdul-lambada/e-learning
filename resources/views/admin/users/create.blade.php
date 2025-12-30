@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card title="Tambah Pengguna Baru">
                <x-slot name="headerAction">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </x-slot>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Informasi Akun -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Akun</h6>

                            <x-input name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap"
                                required />
                            <x-input name="email" type="email" label="Email" placeholder="email@example.com"
                                required />
                            <x-input name="username" label="Username" placeholder="Username unik" required />
                            <x-input name="password" type="password" label="Password" placeholder="Minimal 8 karakter"
                                required />

                            <x-select name="peran" label="Role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('peran') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="guru" {{ old('peran') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="siswa" {{ old('peran') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </x-select>
                        </div>

                        <!-- Informasi Pribadi -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">Informasi Pribadi</h6>

                            <x-select name="jenis_kelamin" label="Jenis Kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </x-select>

                            <x-input name="tempat_lahir" label="Tempat Lahir" />
                            <x-input name="tanggal_lahir" type="date" label="Tanggal Lahir" />
                            <x-input name="no_telepon" label="No. Telepon" />

                            <div id="nis_field" style="display: none;">
                                <x-input name="nis" label="NIS (Nomor Induk Siswa)" />
                            </div>

                            <div id="nip_field" style="display: none;">
                                <x-input name="nip" label="NIP (Nomor Induk Pegawai)" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-button icon="bx-save">Simpan Pengguna</x-button>
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

        // Trigger on page load if old value exists
        if (document.getElementById('peran').value) {
            document.getElementById('peran').dispatchEvent(new Event('change'));
        }
    </script>
@endpush
