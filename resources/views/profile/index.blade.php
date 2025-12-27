@extends($layout)

@section('title', 'Profil Saya')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-item nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Akun</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item nav-link" href="#password-section"><i class="bx bx-lock-alt me-1"></i> Keamanan</a>
                </li>
            </ul>
            <div class="card mb-4">
                <h5 class="card-header">Detail Profil</h5>
                <!-- Account -->
                <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                alt="user-avatar" class="d-block rounded" height="100" width="100"
                                id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Unggah foto baru</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="foto_profil" class="account-file-input"
                                        hidden accept="image/png, image/jpeg" />
                                </label>
                                <p class="text-muted mb-0">Format JPG atau PNG. Ukuran maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input class="form-control" type="text" id="nama_lengkap" name="nama_lengkap"
                                    value="{{ old('nama_lengkap', $user->nama_lengkap) }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" placeholder="john.doe@example.com" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="no_telepon">No. Telepon</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">ID (+62)</span>
                                    <input type="text" id="no_telepon" name="no_telepon" class="form-control"
                                        value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="8123456789" />
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Username</label>
                                <input class="form-control" type="text" value="{{ $user->username }}" disabled />
                                <small class="text-muted">Username tidak dapat diubah.</small>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Peran</label>
                                <input class="form-control" type="text" value="{{ ucfirst($user->peran) }}" disabled />
                            </div>
                            @if ($user->peran == 'siswa')
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">NIS</label>
                                    <input class="form-control" type="text" value="{{ $user->nis }}" disabled />
                                </div>
                            @elseif($user->peran == 'guru')
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">NIP</label>
                                    <input class="form-control" type="text" value="{{ $user->nip }}" disabled />
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
                <!-- /Account -->
            </div>

            <div class="card" id="password-section">
                <h5 class="card-header">Ganti Password</h5>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="current_password">Password Saat Ini</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password">Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('upload').onchange = function(evt) {
            const [file] = this.files
            if (file) {
                document.getElementById('uploadedAvatar').src = URL.createObjectURL(file)
            }
        }
    </script>
@endpush
